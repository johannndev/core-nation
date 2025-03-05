<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\AppSettingsHelper;
use App\Helpers\CCManagerHelper;
use App\Helpers\HashManagerHelper;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Helpers\TransactionsManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Notmatcheditem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiJubelioController extends Controller
{
    private function logJubelio($type,$storeName,$locationName,$invoice,$store,$location,$pesan){

        $dataDetail = [
            'store_name' => $storeName,
            'store_id' => $store,
            'location_name' => $locationName,
            'location_id' => $location,
            'pesan' => $pesan
        ];

        try {
            $dataStore = new Logjubelio();

            $dataStore->type = $type;
            $dataStore->invoice = $invoice;
            $dataStore->data = $dataDetail;
            $dataStore->save();

            return $data = [
                'status' => 'oke',
                'pesan' => 'success',
            ];
        } catch (\Exception $e) {
            // Log error agar bisa ditelusuri nanti

            return $data = [
                'status' => 'error',
                'pesan' => $e->getMessage(),
            ];

            
        }

        
    }

    public function order(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        if ($signature !== $sign) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $dataApi = $request->all(); 

        $dataJubelio = [];

        $matched = 0;
        $notMatched = 0;
        $store = [];
        $location = [];

        $urlDetail = "Url tidak ada";

        $logJubelio = [];

        $logStore = [];


        if($dataApi['status'] == "SHIPPED"){

            $tanggal = Carbon::parse($dataApi['transaction_date']);
            $threshold = Carbon::parse('2025-03-03');

            $limitTime = $tanggal->lessThan($threshold) ? 0 : 1;

            if($limitTime == 1){

                $jubelioSync = Jubeliosync::where('jubelio_store_id',$dataApi['store_id'])->where('jubelio_location_id',$dataApi['location_id'])->first();

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

                            

                        $logStore =  $this->logJubelio('RETURN',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Invoice transaksi sudah ada');


                            return response()->json([
                                'status' => 'ok',
                                'pesan' => 'Invoice transaksi sudah ada',
                                'logStore' => $logStore
                            ], 200);

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
                                
                                    $logStore = $this->logJubelio('SALE',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],$skuNotmatche);
            
                                }
            
                            }else{

                                $logStore = $this->logJubelio('SALE',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],$createData['message']);


                                return response()->json([
                                    'status' => 'ok',
                                    'pesan' => 'Gagal membuat data transaksi',
                                    'pesan_detail' => $createData['message'],
                                    'logStore' => $logStore
                                ], 200);

                            }

                        }

                        

                    
                    

                    }

                

                    $matched = $matched->count();
                    $notMatched = $notMatched->count();


                

                }else{

                    $logStore = $this->logJubelio('SALE',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Data sync dengan aria tidak ditemukan');
                    
                    return response()->json([
                        'status' => 'ok',
                        'pesan' => 'Data sync dengan aria tidak ditemukan',
                        'logStore' => $logStore
                    ], 200);
                }
            }else{

                return response()->json([
                    'status' => 'ok',
                    'pesan' => 'transaksi sebelum tanggal 03/03/25 tidak dibuat, tangggal transaksi '.$dataApi['transaction_date'],
                ], 200);

            }

        
        }elseif ($dataApi['status'] == "RETURNED") {

            $dataTransaksi = Transaction::where('invoice',$dataApi['salesorder_no'])->first();

            if($dataTransaksi){
                if($dataTransaksi->jubelio_return > 0){

                    return response()->json([
                        'status' => 'ok',
                        'detail' => 'Transaksi sudah return',
                        'pesan' => $dataApi['status'],
                    ], 200);

                }else{
                    $dataTransaksi->jubelio_return = 1;
                    $dataTransaksi->save();

                    return response()->json([
                        'status' => 'ok',
                        'detail' => 'Transaksi return',
                        'pesan' => $dataApi['status'],
                    ], 200);
                }

            }else{

                $this->logJubelio('RETURN',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Transaksi tidak ditemukan');

                return response()->json([
                    'status' => 'ok',
                    'detail' => 'Transaksi tidak ditemukan',
                    'pesan' => $dataApi['status'],
                ], 200);
            }

        }else{

            return response()->json([
                'status' => 'ok',
                'pesan' => $dataApi['status'],
            ], 200);

        }


     


       

        return response()->json([
            'status' => 'ok',
            'transaction_detail' =>$createData, 
            'url' => $urlDetail,
            'status_jubelio' => $dataApi['status'],
            'pesan' => 'Transaksi berhasil dikirim ke aria',
            'total_matched' => $matched,
            'total_not_matched' => $notMatched,
            
        ], 200);
    }

    public function retur(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        // $data = new Logjubelio();

        // $data->log = $request->items;

        // $data->save();

        $data = $request->all(); 

        return response()->json([
            'status' => 'ok',
            'signature' => $signature,
            'received_data' => $data
        ], 200);
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

                $paid = $dataJubelio->paid;
                //special case: paid is checked
                if($type == Transaction::TYPE_SELL && isset($paid) && $paid)
                {
                    //calculate total
                    $amount = isset($dataJubelio->amount) ? $dataJubelio->amount : 0;
                    if($amount <= 0) $amount = abs($transaction->total);

                    $payment = $transaction->attachIncome($transaction->date, $transaction->receiver_id, $dataJubelio->account,$amount);
                    $class['income'] = $payment->total;

                    //another special case, ongkir is filled, create journal
                    $settingApp = new AppSettingsHelper;
                    $ongkir = isset($dataJubelio->ongkir) ? $dataJubelio->ongkir : null;
                    if(!empty($ongkir))
                        $transaction->attachOngkir($transaction->date, $payment->receiver_id, abs($ongkir), $settingApp->getAppSettings('ongkir') );
                }

                


                InvoiceTrackerHelpers::flag($transaction);

                // dd($details);
                
                TransactionsManagerHelper::checkSell($transaction, $details);

                
                HashManagerHelper::save($transaction);
                $cc = new CCManagerHelper;
                $class['date'] = Carbon::createFromFormat('Y-m-d',$transaction->date)->startOfMonth()->toDateString();
                //update customer class
                switch ($transaction->type) {
                    case Transaction::TYPE_SELL:
                        $class['type'] = Transaction::TYPE_SELL;
                        $class['total'] = $transaction->total;
                        $class['customer'] = $transaction->receiver;
                        $cc->update($class);
                        break;
                    case Transaction::TYPE_RETURN:
                        $class['type'] = Transaction::TYPE_RETURN;
                        $class['total'] = $transaction->total;
                        $class['customer'] = $transaction->sender;
                        $cc->update($class);
                        break;
                    default:
                        break;
                }

                if($type == 2 || $type == 15){

                

                    // Query
                    $result = DB::table('transaction_details')
                    ->where('transaction_details.transaction_id',$transaction->id)
                    ->join('items', 'transaction_details.item_id', '=', 'items.id')
                    ->whereIn('transaction_details.transaction_type', [2, 15]) // Filter transaction_type 2 dan 15
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
                    ->lockForUpdate()
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


            } catch(ModelException $e) {
                
                DB::rollBack();

                if ($e->getCode() == 1213) {
                    $attempts++;
                    Log::warning("Deadlock terdeteksi, mencoba ulang ($attempts/$maxRetries)...");
    
                    // Tunggu sebentar sebelum retry (misalnya 100ms)
                    usleep(100000);
                } else {
                    return response()->json(['error' => $e->getMessage()], 500);
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
                ->lockForUpdate()
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
}
