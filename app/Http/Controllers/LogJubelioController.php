<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class LogJubelioController extends Controller
{
    protected function toggleSign($value) {
        return -$value;
    }
    
    public function index(Request $request){

        
        $dataList = Logjubelio::orderBy('created_at','desc');
        
        if($request->from && $request->to){
			$dataList = $dataList->whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to);
		}

		if($request->invoice){
			$dataList = $dataList->where('invoice',$request->invoice);
		}
        
        $dataList = $dataList->paginate(50)->withQueryString();

        // dd($dataList);

        return view('log.index',compact('dataList'));
    }

    public function viewJson($id){

        $response = Http::withHeaders([ 
                'Content-Type'=> 'application/json', 
                'authorization'=> Cache::get('jubelio_data')['token'], 
            ]) 
            ->get('https://api2.jubelio.com/sales/orders/'.$id); 

            $data = json_decode($response->body(), true);

        dd($data);
    }

    public function createManual($id){

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/sales/orders/'.$id); 

        $data = json_decode($response->body(), true);

        $adjust = $data['sub_total'] - $data['grand_total'];

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();
        
        $sid = $id;

        return view('log.manual',compact('jubelioSync','data','adjust','sid'));
    }

    public function postManual($id){



        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/sales/orders/'.$id); 

        $dataApi = json_decode($response->body(), true);

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])->where('jubelio_location_id',$dataApi['location_id'])->first();

        if($jubelioSync){

            // $produkIds = collect($dataApi['items'])->pluck('item_code')->unique(); // Hilangkan duplikasi ID
            $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();

            // Ambil hanya kolom yang diperlukan
            $existingProducts = Item::whereIn('code', $itemCodes)
                ->get(['id', 'code', 'name'])
                ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat
            
            // Proses matching dengan map agar lebih efisien
            $groupedData = collect($dataApi['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));
            
            $matched = $groupedData[0]->map(fn($item) => [
                'itemId'   => $existingProducts[$item['item_code']]->id,
                'code'     => $existingProducts[$item['item_code']]->code,
                'name'     => $existingProducts[$item['item_code']]->name,
                'quantity' => $item['qty'],
                'price'    => $item['price'],
                'discount' => 0,
                'subtotal' => $item['qty']*$item['price'],
            ])->values(); // Reset indeks array
            
            $notMatched = $groupedData[1]->values(); // Reset indeks array

            $createData = [];

            if($matched->count() > 0){

                $cekTransaksi = Transaction::where('invoice',$dataApi['salesorder_no'])->first();

                if($cekTransaksi){

                    

                // $logStore =  $this->logJubelio('RETURN',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Invoice transaksi sudah ada');

                    return  redirect()->back()->with('errorMessage','Invoice transaksi sudah ada');
                   

                }else{

                    // $ongkir = $dataApi['shipping_cost']-$dataApi['shipping_cost_discount'];

                    // $adjust = $dataApi['total_disc']+$dataApi['add_disc']+$ongkir+$dataApi['total_tax']+$dataApi['service_fee']+$dataApi['insurance_cost'];

                    $adjust = $dataApi['sub_total'] - $dataApi['grand_total'];

                    $dataJubelio = [
                        "date" => Carbon::now()->toDateString(),
                        "due" => null,
                        "warehouse" => $jubelioSync->warehouse_id,
                        "customer" => $jubelioSync->customer_id,
                        "invoice" => $dataApi['salesorder_no'],
                        "note" => "generated by jubelio",
                        "account" => "7204",
                        "amount" => null,
                        "paid" => null,
                        "addMoreInputFields" => $matched,
                        "disc" => "0",
                        "adjustment" =>  $this->toggleSign($adjust),
                        "ongkir" => "0"
                    ];

                    $dataCollect =  (object) $dataJubelio;

                    dd($dataCollect);

                    $createData =  $this->createTransaction(Transaction::TYPE_SELL, $dataCollect);

                
                    if($createData['status'] == "200" ){

                        $urlDetail = route('transaction.getDetail',$createData['transaction_id']);

                        // $dataLog = new Logjubelio();
                        
                        // $dataLog->transaction_id = $createData['transaction_id'];
                        // $dataLog->invoice_id = $dataApi['salesorder_no'];
                        // $dataLog->total_matched_item = $matched->count();
                        // $dataLog->total_not_matched = $notMatched->count();
                        // $dataLog->desc =  $createData['message'];
    
                        // $dataLog->save();
    
                        if($notMatched->count() > 0){
    
                            $notMactheArray = [];
    
                            foreach ($notMatched as $data) {
                                $notMactheArray[] = [
                                    'transaction_list' => $createData['transaction_id'],
                                    'item_code' => $data['item_code'],
                                    'item_name' =>  $data['item_name'],
                                    'channel' =>  $data['item_code'],
                                    'loc_name' =>  $dataApi['source_name'],
                                    'thumbnail' =>  $data['thumbnail'],
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ];
                            }
    
                            DB::table('notmatcheditems')->insert($notMactheArray);
    
                            $skuNotmatche = $notMatched->count()." SKU tidak ditemukan";

                            return  redirect()->back()->with('errorMessage',$skuNotmatche);
                        
                           
    
                        }
    
                    }else{

                        return  redirect()->back()->with('errorMessage',$createData['message']);
                        

                        

                    }

                }

                

            
            

            }

        

            $matched = $matched->count();
            $notMatched = $notMatched->count();


        

        }else{

            return  redirect()->back()->with('errorMessage','Data sync dengan aria tidak ditemukan');

        }
    }

    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}
