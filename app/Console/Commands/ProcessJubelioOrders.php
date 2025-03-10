<?php

namespace App\Console\Commands;

use App\Exceptions\ModelException;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessJubelioOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jubelio:process-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process orders from Jubelio API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Task dijalankan pada: ' . now());
      
        $logjubelio = Logjubelio::where('status',0)->where('cron_run',1)->orderBy('updated_at','desc')->first();

        if(count($logjubelio) > 0){

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authorization' => Cache::get('jubelio_data')['token'],
            ])->get('https://api2.jubelio.com/sales/orders/' . $logjubelio->order_id);

            $dataApi = $response->json();
            // dd($dataApi);
            // Cari mapping gudang dengan lock
            $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])
                ->where('jubelio_location_id', $dataApi['location_id'])
                ->lockForUpdate()
                ->first();

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

                if($notMatched->count() > 0){
                    // Ambil item_code dari notMatched
                    $item_codes = array_column($notMatched->toArray(), 'item_code');

                    // Ubah menjadi string dengan koma sebagai pemisah
                    $notMatchedString = implode(", ", $item_codes);

                    $logjubelio->update(['cron_failed' => 'SKU tidak di temukan: '.$notMatchedString ]);
                }

                if($matched->count() > 0){

                    $cekTransaksi = Transaction::where('invoice',$dataApi['salesorder_no'])->first();

                    if($cekTransaksi){

                        $logjubelio->update(['cron_failed' => 'Invoice transaksi sudah ada']);

                    

                    }else{

                        
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

                            $logjubelio->update(['cron_run' => 2,'status' => 2]);

        
                        }else{

                            $logjubelio->update(['cron_failed' => $createData['message']]);

                        }

                    }

                }

                

            

            }else{

                $logjubelio->update(['cron_failed' => 'Data sync dengan aria tidak ditemukan']);

               
            }

        }
       
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
}
