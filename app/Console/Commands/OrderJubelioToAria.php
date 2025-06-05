<?php

namespace App\Console\Commands;

use App\Exceptions\ModelException;
use App\Helpers\InvoiceTrackerHelpers;
use App\Helpers\StatManagerHelper;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Jubelioorder;
use App\Models\Jubeliosync;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderJubelioToAria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jubelio:order-jubelio-to-aria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Proese order jubelio ke aria transaction';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Proses order Jubelio ke Aria Transaction dijalankan pada: ' . now());

        $logjubelio = Jubelioorder::whereIn('type', ['SELL','RETURN'])
            ->where('status', 0)
            ->where('run_count', 0)
            ->orderBy('created_at', 'asc')
            ->first();

        if ($logjubelio->type == 'SELL') {
           
            if ($logjubelio) {
                $dataApi = json_decode($logjubelio->payload, true);

                if ($logjubelio->source != 1) {
                    $dataApi = $this->getOrder($logjubelio->jubelio_order_id);

                    if (isset($dataApi['error'])) {
                        $logjubelio->update([
                            'run_count' => $logjubelio->logjubelio + 1,
                            'error_type' => 3,
                            'error' => 'Gagal ambil data API: ' . $dataApi['message'],
                            'status' => 1,
                        ]);
                        $dataApi = null;
                    }
                }

                if ($dataApi) {
                    $arrayStoreId = $dataApi['store_id'];
                    $arrayLocationId = $dataApi['location_id'];
                    $arrayItems = $dataApi['items'];
                    $arrayRunCount = $logjubelio->logjubelio + 1;
                    $arrayInvoice = $dataApi['salesorder_no'];
                    $arraySubTotal = $dataApi['sub_total'];
                    $arrayGrandTotal = $dataApi['grand_total'];

                    $jubelioSync = Jubeliosync::where('jubelio_store_id', $arrayStoreId)
                        ->where('jubelio_location_id', $arrayLocationId)
                        ->lockForUpdate()
                        ->first();

                    if ($jubelioSync) {
                        $itemCodes = collect($arrayItems)
                            ->pluck('item_code')
                            ->map(fn($code) => strtoupper($code))
                            ->unique();

                        $existingProducts = Item::whereIn(DB::raw('UPPER(code)'), $itemCodes)
                            ->get(['id', 'code', 'name'])
                            ->keyBy(fn($item) => strtoupper($item->code));
                    
                        $groupedData = collect($arrayItems)->partition(function ($item) use ($existingProducts) {
                            return isset($existingProducts[strtoupper($item['item_code'])]);
                        });


                        $matched = $groupedData[0]->map(function ($item) use ($existingProducts) {
                            $upperCode = strtoupper($item['item_code']);
                            $product = $existingProducts[$upperCode];

                            return [
                                'itemId' => $product->id,
                                'code' => $product->code,
                                'name' => $product->name,
                                'quantity' => $item['qty'],
                                'price' => $item['price'],
                                'discount' => 0,
                                'subtotal' => $item['qty'] * $item['price'],
                            ];
                        })->values();

                        $notMatched = $groupedData[1]->values();

                        if ($notMatched->count() > 0) {
                            $item_codes = array_column($notMatched->toArray(), 'item_code');
                            $notMatchedString = implode(", ", $item_codes);

                            $logjubelio->update([
                                'run_count' => $arrayRunCount,
                                'error_type' => 1,
                                'error' => 'SKU tidak ditemukan: ' . $notMatchedString,
                                'status' => 1,
                            ]);
                    
                        } else {
                            $cekTransaksi = Transaction::where('type', Transaction::TYPE_SELL)
                                ->where('invoice', $arrayInvoice)
                                ->first();

                            if ($cekTransaksi) {
                                $logjubelio->update([
                                    'run_count' => $arrayRunCount,
                                    'error_type' => 2,
                                    'error' => 'Transaction sudah ada',
                                    'status' => 2,
                                ]);
                            } else {
                                if (count($arrayItems) !== $matched->count()) {
                                    $logjubelio->update([
                                        'run_count' => $arrayRunCount,
                                        'error_type' => 1,
                                        'error' => 'Jumlah item tidak sesuai, kemungkinan ada SKU tidak masuk.',
                                        'status' => 1,
                                    ]);
                                } else {
                                    // Proses create transaction hanya jika jumlahnya cocok
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
                                        "adjustment" => $this->toggleSign($adjust),
                                        "ongkir" => "0",
                                    ];

                                    $dataCollect = (object) $dataJubelio;

                                    $createData = $this->createTransaction(Transaction::TYPE_SELL, $dataCollect);

                                    if ($createData['status'] == "200") {
                                        $logjubelio->update([
                                            'run_count' => $arrayRunCount,
                                            'error_type' => 10,
                                            'error' => null,
                                            'execute_by' => 0,
                                            'status' => 2,
                                        ]);
                                    } else {
                                        $logjubelio->update([
                                            'run_count' => $arrayRunCount,
                                            'error_type' => 1,
                                            'error' => $createData['message'],
                                            'status' => 1,
                                        ]);
                                    }
                                }
                            }
                        }
                    } else {
                        $logjubelio->update([
                            'run_count' => $arrayRunCount,
                            'error_type' => 1,
                            'error' => 'Data sync dengan aria tidak ditemukan',
                            'status' => 1,
                        ]);
                    }
                }
            }
        }
        elseif($logjubelio->type == 'RETURN'){

            $dataApi = json_decode($logjubelio->payload, true);

            $arrayRunCount = $logjubelio->logjubelio + 1;

            $cekTransaksiSell = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

            if ($cekTransaksiSell) {

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
                    'quantity' => $item['qty_in_base'],
                    'price'    => $item['price'],
                    'discount' => 0,
                    'subtotal' => $item['qty_in_base']*$item['price'],
                ])->values(); // Reset indeks array
                
                $notMatched = $groupedData[1]->values(); // Reset indeks array

                $createData = [];

                 if ($notMatched->count() > 0) {
                    $item_codes = array_column($notMatched->toArray(), 'item_code');
                    $notMatchedString = implode(", ", $item_codes);

                    $logjubelio->update([
                        'run_count' => $arrayRunCount,
                        'error_type' => 1,
                        'error' => 'SKU tidak ditemukan: ' . $notMatchedString,
                        'status' => 1,
                    ]);
                }else{

                    if($matched->count() > 0){

                        $cekTransaksi = Transaction::where('type',Transaction::TYPE_RETURN)->where('invoice',$dataApi['return_no'])->first();

                        if($cekTransaksi){

                            $logjubelio->update([
                                'run_count' => $logjubelio->logjubelio + 1,
                                'error_type' => 2,
                                'error' => 'Invoice Retur sudah ada',
                                'status' => 2,
                            ]);

                            

                        }else{

                            $jubelioSync = Jubeliosync::where('jubelio_store_id',$dataApi['store_id'])->where('jubelio_location_id',$dataApi['location_id'])->first();

                            // $ongkir = $dataApi['shipping_cost']-$dataApi['shipping_cost_discount'];

                            // $adjust = $dataApi['total_disc']+$dataApi['add_disc']+$ongkir+$dataApi['total_tax']+$dataApi['service_fee']+$dataApi['insurance_cost'];

                            $adjust = $dataApi['sub_total'] - $dataApi['grand_total'];

                            $dataJubelio = [
                                "date" => Carbon::now()->toDateString(),
                                "due" => null,
                                "warehouse" => $jubelioSync->warehouse_id,
                                "customer" => $jubelioSync->customer_id,
                                "invoice" => $dataApi['return_no'],
                                "description" => $dataApi['salesorder_no'],
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

                            $createData =  $this->createTransaction(Transaction::TYPE_RETURN, $dataCollect);
                        
                            if($createData['status'] == "200" ){

                                
                                $logjubelio->update([
                                    'run_count' => $arrayRunCount,
                                    'error_type' => 10,
                                    'error' => null,
                                    'execute_by' => 0,
                                    'status' => 2,
                                ]);
                            

                            }else{

                                $logjubelio->update([
                                    'run_count' => $arrayRunCount,
                                    'error_type' => 1,
                                    'error' => $createData['message'],
                                    'status' => 1,
                                ]);

                            }

                        }

                    }

                }
                    

            
            }else{

                 $logjubelio->update([
                    'run_count' => $logjubelio->logjubelio + 1,
                    'error_type' => 3,
                    'error' => 'Transaksi sell tidak ada',
                    'status' => 1,
                ]);
                $dataApi = null;

            }

        } else {
            # code...
        }
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
            Log::error('Gagal mendapatkan order: ' . $e->getMessage());
            return [
                'error' => true,
                'message' => $e->getMessage(),
            ];
        }
    }

    protected function toggleSign($value) {
        return -$value;
    }

    protected function createTransaction($type = null, $dataJubelio)
    {
    
        $maxRetries = 5; // Jumlah maksimal percobaan
        $attempts = 0;

    
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
            $transaction->submit_type = 2;

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

        } catch(ModelException $e) {
            
            DB::rollBack();

            if ($e->getCode() == 1213) {
                
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
