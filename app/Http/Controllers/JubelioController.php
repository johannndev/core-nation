<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubelioorder;
use App\Models\Jubelioreturn;
use App\Models\Jubeliosync;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class JubelioController extends Controller
{
    public function order(Request $request)
    {
        $secret = 'corenation2025';
        $content = trim($request->getContent());
        $sign = hash_hmac('sha256', $content . $secret, $secret, false);
        $signature = $request->header('Sign');

        if ($signature !== $sign) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $dataApi = $request->all();

        if ($dataApi['status'] === "SHIPPED") {
            $tanggal = Carbon::parse($dataApi['transaction_date']);
            $threshold = Carbon::parse('2025-03-06');

            if ($tanggal->lessThan($threshold)) {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Transaksi sebelum tanggal 06 Maret 2025 tidak dibuat. Tanggal transaksi: ' . $tanggal->toDateTimeString(),
                ], 200);
            }

            $cekTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

            $exists = Jubelioorder::where('invoice',$dataApi['salesorder_no'])
                ->where('type', 'SELL')
                ->where('order_status', $dataApi['status'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data already exists',
                ], 200);
            }else{

                if($cekTransaksi){

                    DB::table('jubelioorders')->insert([
                        'jubelio_order_id'  => $dataApi['salesorder_id'],
                        'source'            => 1,
                        'invoice'           => $dataApi['salesorder_no'],
                        'type'              => 'SELL',
                        'order_status'      => $dataApi['status'],
                        'run_count'         => 0,
                        'error_type'        => null,
                        'error'             => null,
                        'payload'           => json_encode($dataApi),
                        'execute_by'        => null,
                        'status'            => 0,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Data saved successfully',
                    ], 200);

                }else{

                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Invoice sudah ada',
                    ], 200);
                    
                }

            }



        } elseif ($dataApi['status'] === "CANCELED") {
            $transaction = Transaction::where('type', Transaction::TYPE_SELL)
                ->where('invoice', $dataApi['salesorder_no'])
                ->first();

            if ($transaction) {
                if ($transaction->jubelio_return > 0) {
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'Transaksi sudah return',
                    ], 200);
                }

                $returnData = new Jubelioreturn();
                $returnData->order_id       = $dataApi['salesorder_id'];
                $returnData->transaction_id = $transaction->id;
                $returnData->method_pay     = $dataApi['payment_method'];
                $returnData->invoice        = $dataApi['salesorder_no'];
                $returnData->pesan          = $dataApi['cancel_reason_detail'];
                $returnData->location_name  = $dataApi['location_name'];
                $returnData->store_name     = $dataApi['source_name'];
                $returnData->save();

                return response()->json([
                    'status' => 'ok',
                    'message' => 'Data saved successfully',
                ], 200);
            }

            return response()->json([
                'status' => 'ok',
                'message' => 'Transaksi tidak ditemukan',
            ], 200);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Status ' . $dataApi['status'] . ' ok',
        ], 200);
    }


    public function index(Request $request){
        $dataList = Jubelioorder::orderBy('created_at','desc');

        if($request->invoice){
			$dataList = $dataList->where('invoice', 'like', '%'.$request->invoice.'%');
		}

        if($request->status == 'warning'){
            $dataList = $dataList->where('status',2)->where('error_type',2);
        }elseif($request->status == 'success'){
            $dataList = $dataList->where('status',2)->where('error_type',10);
        }elseif($request->status == 'error'){
            $dataList = $dataList->where('status',1)->where('error_type',1);
        }else{
            $dataList = $dataList->where('status',0);
        }

        $dataList = $dataList->paginate(200)->withQueryString();

        // dd($allRolesInDatabase);

        return view('jubelio.webhook.index',compact('dataList'));
    }

  
     public function detail($id){
        $data = Jubelioorder::find($id);

        $jsonData = json_decode($data->payload, true); // pastikan jadi array/objek PHP


        return view('jubelio.webhook.detail',compact('data','jsonData'));
    }

     private function getOrder($orderId){
        try {
            $token = Cache::get('jubelio_data')['token'] ?? null;

            if (!$token) {
                throw new \Exception('Token Jubelio tidak tersedia.');
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authorization' => $token,
            ])->get('https://api2.jubelio.com/sales/orders/' . $orderId);

            if ($response->failed()) {
                throw new \Exception('Gagal mengambil data order dari API Jubelio. Status: ' . $response->status());
            }

            $data = $response->json();

            if (!$data || !isset($data['salesorder_no'])) {
                throw new \Exception('Data order tidak valid atau kosong.');
            }

            return $data;
        } catch (\Exception $e) {
            
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function createManual($id){

        $logjubelio = Jubelioorder::findOrFail($id);

        if($logjubelio->source == 1){

            $data = json_decode($logjubelio->payload, true);

        }else{
            $data = $this->getOrder($logjubelio->jubelio_order_id);
        }


       

        $adjust = $data['sub_total'] - $data['grand_total'];

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();

        
        $sid = $id;

        return view('jubelio.webhook.manual',compact('jubelioSync','data','adjust','sid'));
    }

    public function storeManual($id)
    {
      
        $logjubelio = Jubelioorder::where('id',$id)->first();

        if($logjubelio){

            $dataApi = json_decode($logjubelio->payload, true);

            if($logjubelio->source == 1){

                 $dataApi = json_decode($logjubelio->payload, true);

            }else{
                $dataApi = $this->getOrder($logjubelio->jubelio_order_id);

                if (isset($dataApi['error'])) {
                   
                    return redirect()->route('jubelio.webhook.createManual',$logjubelio->id)->with('errorMessage', 'Gagal ambil data API: ' . $dataApi['message']);

                }
            }

            $arrayStoreId = $dataApi['store_id'];
            $arrayLocationId = $dataApi['location_id'];
            $arrayItems = $dataApi['items'];
            $arrayRunCount = $logjubelio->logjubelio+1;
            $arrayInvoice = $dataApi['salesorder_no'];
            $arraySubTotal = $dataApi['sub_total'];
            $arrayGrandTotal = $dataApi['grand_total'];
          
            // Cari mapping gudang dengan lock
            $jubelioSync = Jubeliosync::where('jubelio_store_id', $arrayStoreId)
                ->where('jubelio_location_id',  $arrayLocationId)
                ->lockForUpdate()
                ->first();

            if($jubelioSync){

                // $produkIds = collect($dataApi['items'])->pluck('item_code')->unique(); // Hilangkan duplikasi ID
                $itemCodes = collect($arrayItems)->pluck('item_code')->unique();

                // Ambil hanya kolom yang diperlukan
                $existingProducts = Item::whereIn('code', $itemCodes)
                    ->get(['id', 'code', 'name'])
                    ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat
                
                // Proses matching dengan map agar lebih efisien
                $groupedData = collect($arrayItems)->partition(fn($item) => isset($existingProducts[$item['item_code']]));
                
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

                if($notMatched->count() > 0){
                    // Ambil item_code dari notMatched
                    $item_codes = array_column($notMatched->toArray(), 'item_code');

                    // Ubah menjadi string dengan koma sebagai pemisah
                    $notMatchedString = implode(", ", $item_codes);

                    return redirect()->route('jubelio.webhook.createManual',$logjubelio->id)->with('errorMessage',  'SKU tidak di temukan: '.$notMatchedString);

                }

                if($matched->count() > 0){

                    $cekTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$arrayInvoice)->first();

                    if($cekTransaksi){

                        return redirect()->route('jubelio.webhook.createManual',$logjubelio->id)->with('errorMessage',  'Transaction sudah ada');

                    }else{

                        
                        $adjust = $arraySubTotal - $arrayGrandTotal;
                        

                        $dataJubelio = [
                            "date" => Carbon::now()->toDateString(),
                            "due" => null,
                            "warehouse" => $jubelioSync->warehouse_id,
                            "customer" => $jubelioSync->customer_id,
                            "invoice" => $arrayInvoice,
                            "note" => "generated by cron aria",
                            "account" => "7204",
                            "amount" => null,
                            "paid" => null,
                            "addMoreInputFields" => $matched,
                            "disc" => "0",
                            "adjustment" =>  $this->toggleSign($adjust),
                            "ongkir" => "0"
                        ];

                        $dataCollect =  (object) $dataJubelio;

                        $createData =  $this->createTransaction(Transaction::TYPE_SELL, $dataCollect);

                    
                        if($createData['status'] == "200" ){
                            
                            $logjubelio->update(['run_count' => $arrayRunCount, 'error_type' => 10, 'error' =>null,'execute_by' =>0, 'status' => 2]);

                            return redirect()->route('jubelio.webhook.order')->with('success',  'Transaction created');

                        }else{

                            return redirect()->route('jubelio.webhook.createManual',$logjubelio->id)->with('errorMessage', $createData['message']);

                        }

                    }

                }

                

            

            }else{

                return redirect()->route('jubelio.webhook.createManual',$logjubelio->id)->with('errorMessage', 'Data sync dengan aria tidak ditemukan');


               
            }

        }
    }

    protected function toggleSign($value) {
        return -$value;
    }

    protected function createTransaction($type = null, $dataJubelio)
    {
    
        $maxRetries = 5; // Jumlah maksimal percobaan
        $attempts = 0;

        while ($attempts < $maxRetries) {
            try {

                $class = array();

                DB::statement('SET TRANSACTION ISOLATION LEVEL READ COMMITTED');
                
                //start transaction
                DB::beginTransaction();

                $customer = Customer::find($dataJubelio->customer);
                $warehouse = Customer::find($dataJubelio->warehouse);

                // dd($customer,$warehouse);

                // $input = $dataJubelio;
                $transaction = new Transaction();
                $transaction->date = $dataJubelio->date;
                $transaction->type = $type;
                $transaction->adjustment	 = $dataJubelio->adjustment;
                $transaction->user_id =-100;

                //    if($dataJubelio->note){
                //        $transaction->description = $dataJubelio->note;
                //    }else{
                    
                //    }

                $transaction->description = " ";
                $transaction->invoice = $dataJubelio->invoice;

                if($dataJubelio->due){
                    $transaction->due = $dataJubelio->due;
                }else{
                    $transaction->due = '0000-00-00';
                }

                $transaction->detail_ids = ' ';
                
                $transaction->save();
                switch($type)
                {
                    case Transaction::TYPE_BUY:
                    case Transaction::TYPE_RETURN:
                        $transaction->sender_id = $customer->id;
                        $transaction->receiver_id = $warehouse->id;
                        break;
                    case Transaction::TYPE_SELL:
                    case Transaction::TYPE_RETURN_SUPPLIER:
                        $transaction->sender_id = $warehouse->id;
                        $transaction->receiver_id = $customer->id;
                        break;
                    default: //don't update stats for move, production
                        break;
                }
                
                $transaction->init($type);

                // dd($dataJubelio->addMoreInputFields);
                //gets the transaction id
                if(!$transaction->save())

                    
                    throw new ModelException($transaction->getErrors(), __LINE__);

                if(!$details = $transaction->createDetails($dataJubelio->addMoreInputFields))
                    throw new ModelException($transaction->getErrors(), __LINE__);
                

                //check ppn first
                $transaction->checkPPN($transaction->sender, $transaction->receiver);

            


                //add to customer stat
                // $sm = new StatManager;

                $sm = new StatManagerHelper();
                switch($type)
                {
                    case Transaction::TYPE_BUY:
                    case Transaction::TYPE_RETURN:
                        //add balance to sender(supplier)
                        $sender_balance = $sm->add($transaction->sender_id,$transaction,true); //skip 1 because the transaction is already created?
                        if($sender_balance === false)
                            throw new ModelException($sm->getErrors());

                        $transaction->sender_balance = $sender_balance;
                        break;
                    case Transaction::TYPE_SELL:
                    case Transaction::TYPE_RETURN_SUPPLIER:
                        $transaction->setAttribute('total',0 - $transaction->total); //make negative

                        //deduct balance from receiver(customer)
                        $receiver_balance = $sm->deduct($transaction->receiver_id,$transaction,true);
                        if($receiver_balance === false)
                            throw new ModelException($sm->getErrors());

                        $transaction->receiver_balance = $receiver_balance;

                        // $transaction->save();

                        // dd($receiver_balance,$transaction, $transaction->receiver_balance);
                        break;
                    default: //don't update stats for move, production
                        break;
                }

                

                if(!$transaction->save())
                    throw new $transaction->getErrors();

               
                


                InvoiceTrackerHelpers::flag($transaction);

            
                if($type == Transaction::TYPE_SELL || $type == Transaction::TYPE_RETURN){

                

                    // Query
                    $result = DB::table('transaction_details')
                    ->where('transaction_details.transaction_id',$transaction->id)
                    ->join('items', 'transaction_details.item_id', '=', 'items.id')
                    ->whereIn('transaction_details.transaction_type', [Transaction::TYPE_SELL, Transaction::TYPE_RETURN]) // Filter transaction_type 2 dan 15
                    ->selectRaw('
                        items.group_id,
                        MONTH(transaction_details.date) as bulan,
                        YEAR(transaction_details.date) as tahun,
                        transaction_details.sender_id,
                        transaction_details.transaction_type,
                        SUM(transaction_details.quantity) as sum_qty,
                        SUM(transaction_details.total) as sum_total
                    ')
                    ->groupBy('items.group_id', DB::raw('MONTH(transaction_details.date)'), DB::raw('YEAR(transaction_details.date)'), 'transaction_details.sender_id', 'transaction_details.transaction_type')
                    ->orderBy('items.group_id') // Optional: Untuk urutan hasil
                    ->sharedLock()
                    ->get();
            
                    $insertData = [];
                    foreach ($result as $row) {
                        $insertData[] = [
                            'group_id' => $row->group_id,
                            'bulan' => $row->bulan,
                            'tahun' => $row->tahun,
                            'sender_id' => $row->sender_id,
                            'type' => $row->transaction_type,
                            'sum_qty' => (int)$row->sum_qty,
                            'sum_total' => (int)$row->sum_total,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    // dd($insertData);

                    $this->updateOrCreateStatsalesOptimized($insertData);

                }

                //commit db transaction
                DB::commit();

                // $dataJubelio->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

                    return $data = [
                        'status' => '200',
                        'message' => 'ok',
                        'transaction_id' => $transaction->id,
                    ];

                //    return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');
                
                // return response()->json([
                //     'url' => route('transaction.getDetail',$transaction->id,$transaction->date),
                // ]);

                break;
            } catch(ModelException $e) {
                
                DB::rollBack();

                if ($e->getCode() == 1213) {
                    $attempts++;
                    Log::warning("Deadlock terdeteksi, mencoba ulang ($attempts/$maxRetries)...");
    
                    // Tunggu sebentar sebelum retry (misalnya 100ms)
                    usleep(200000);
                } else {
                    return $data = [
                        'status' => '500',
                        'message' => $e->getMessage(),
                    ];

                  
                }

                return $data = [
                    'status' => '422',
                    'message' => $e->getErrors()['error'][0],
                ];

                
                // return response()->json($e->getErrors(), 500);
            
            } catch(\Exception $e) {
                DB::rollBack();

                return $data = [
                        'status' => '422',
                        'message' => $e->getMessage(),
                    ];

                //    return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());

                // return response()->json($e->getMessage(), 500);
                
            }
        }
    }

    public function updateOrCreateStatsalesOptimized(array $data)
	{
		foreach ($data as $entry) {
			$existing = DB::table('stat_sells')
				->where('group_id', $entry['group_id'])
				->where('bulan', $entry['bulan'])
				->where('tahun', $entry['tahun'])
				->where('sender_id', $entry['sender_id'])
                ->sharedLock()
				->first();

			if ($existing) {
				// Jika data ditemukan, update sum_qty dan sum_total
				DB::table('stat_sells')
					->where('id', $existing->id)
					->incrementEach([
						'sum_qty' => $entry['sum_qty'],
						'sum_total' => $entry['sum_total']
					]);
			} else {
				// Jika tidak ditemukan, insert data baru
				DB::table('stat_sells')->insert([
					'group_id' => $entry['group_id'],
					'bulan' => $entry['bulan'],
					'tahun' => $entry['tahun'],
					'sender_id' => $entry['sender_id'],
					'type' => $entry['type'],
					'sum_qty' => $entry['sum_qty'],
					'sum_total' => $entry['sum_total'],
					'created_at' => now(),
					'updated_at' => now(),
				]);
			}
		}

		return response()->json(['message' => 'Data processed successfully'], 200);
	}

    public function createSolved($id){

        $logjubelio = Jubelioorder::findOrFail($id);

        if($logjubelio->source == 1){

            $data = json_decode($logjubelio->payload, true);

        }else{
            $data = $this->getOrder($logjubelio->jubelio_order_id);
        }

        $adjust = $data['sub_total'] - $data['grand_total'];

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();
        
        $sid = $id;

        return view('jubelio.webhook.solved',compact('jubelioSync','data','adjust','sid'));
    }

    public function storeSolved($id){

        $logjubelio = Jubelioorder::findOrFail($id);
        $logjubelio->error_type = 10;
        $logjubelio->error = null;
        $logjubelio->execute_by = Auth::id();
        $logjubelio->status = 2;

        $logjubelio->save();

        return redirect()->route('jubelio.webhook.order')->with('success','Jubelio order Solved');
    }



}
