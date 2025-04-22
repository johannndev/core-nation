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
use App\Models\Jubelioreturn;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Notmatcheditem;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Auth;

class ApiJubelioController extends Controller
{
    private function logJubelio($orderId,$error,$type,$storeName,$locationName,$invoice,$store,$location,$pesan,$deadLock = 0){

        // $dataDetail = [
        //     'store_name' => $storeName,
        //     'store_id' => $store,
        //     'location_name' => $locationName,
        //     'location_id' => $location,
        //     'pesan' => $pesan
        // ];

        try {
            $dataStore = new Logjubelio();

            $dataStore->order_id = $orderId;
            $dataStore->error = $error;
            $dataStore->type = $type;
            $dataStore->invoice = $invoice;
            $dataStore->pesan = $pesan;
            $dataStore->location_name = $locationName;
            $dataStore->store_name = $storeName;
            $dataStore->cron_run = $deadLock;
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

    // public function newOrder(Request $request){

    //     $secret = 'corenation2025';
    //     $content = trim($request->getContent());

    //     $sign = hash_hmac('sha256',$content . $secret, $secret, false);

    //     $signature = $request->header('Sign');

    //     if ($signature !== $sign) {
    //         return response()->json(['error' => 'Invalid signature'], 403);
    //     }

    //     $dataApi = $request->all();

    //     $maxRetries = 3;
    //     $retryCount = 0;

    //     while ($retryCount < $maxRetries) {
    //         DB::beginTransaction();

    //         try {

              
    //             // Ambil data dari API
               
    //             // dd($dataApi);
    //             // Cari mapping gudang dengan lock


    //             if($dataApi['status'] == "SHIPPED"){

    //                 $tanggal = Carbon::parse($dataApi['transaction_date']);
    //                 $threshold = Carbon::parse('2025-03-06');

    //                 $limitTime = $tanggal->lessThan($threshold) ? 0 : 1;

    //                 if($limitTime == 1){

    //                     $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])
    //                     ->where('jubelio_location_id', $dataApi['location_id'])
    //                     ->lockForUpdate()
    //                     ->first();
    
    //                 if (!$jubelioSync) {
    //                     throw new Exception('Data sync dengan aria tidak ditemukan.');
    //                 }
    
    //                 $sender = Customer::findOrFail($jubelioSync->warehouse_id);
    //                 $receiver = Customer::findOrFail($jubelioSync->customer_id);
    
    //                 // Proses item
    //                 $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();
    //                 $existingProducts = Item::whereIn('code', $itemCodes)
    //                     ->get(['id', 'code', 'name'])
    //                     ->keyBy('code');
    
    //                 [$matched, $notMatched] = collect($dataApi['items'])->partition(
    //                     fn($item) => isset($existingProducts[$item['item_code']])
    //                 );
    
    //                 if ($notMatched->isNotEmpty()) {
    //                     throw new Exception(implode(', ', $notMatched->pluck('item_code')->toArray()) . ' tidak ditemukan');
    //                 }
    
    //                 // Format data transaksi
    //                 $transactionDetails = $matched->map(function ($item) use ($existingProducts, $sender, $receiver) {
    //                     return [
    //                         'item_id' => $existingProducts[$item['item_code']]->id,
    //                         'quantity' => $item['qty'],
    //                         'price' => $item['price'],
    //                         'total' => $item['qty'] * $item['price'],
    //                         'date' => now()->toDateString(),
    //                         'transaction_type' => Transaction::TYPE_SELL,
    //                         'sender_id' => $sender->id,
    //                         'receiver_id' => $receiver->id,
                            
    //                     ];
    //                 });
    
    //                 // Validasi stok gudang dengan lock
    //                 $itemIds = $transactionDetails->pluck('item_id')->unique();
    //                 $warehouseItems = WarehouseItem::where('warehouse_id', $sender->id)
    //                     ->whereIn('item_id', $itemIds)
    //                     ->lockForUpdate()
    //                     ->get(['item_id', 'quantity']);
    
    //                 $stockIssues = [];
    //                 $updateCases = [];
    //                 $sumTotal = 0;
    //                 $sumQty = 0;
    
    //                 foreach ($transactionDetails as $detail) {
    //                     $stock = $warehouseItems->firstWhere('item_id', $detail['item_id']);
    //                     if (!$stock || $stock->quantity < $detail['quantity']) {
    //                         $stockIssues[] = "Stok {$detail['item_id']} tidak mencukupi";
    //                     }
    
    //                     $sumTotal += $detail['total'];
    //                     $sumQty += $detail['quantity'];
    //                     $updateCases[] = "WHEN item_id = {$detail['item_id']} THEN quantity - {$detail['quantity']}";
    //                 }
    
    //                 if (!empty($stockIssues)) {
    //                     throw new Exception(implode(', ', $stockIssues));
    //                 }
    
    //                 // Update stok gudang
    //                 WarehouseItem::where('warehouse_id', $sender->id)
    //                     ->whereIn('item_id', $itemIds)
    //                     ->update([
    //                         'quantity' => DB::raw("CASE " . implode(' ', $updateCases) . " END")
    //                     ]);
    
    //                      // Update balance dengan lock
    //                 $adjustmentFee = $dataApi['sub_total'] - $dataApi['grand_total'];
    
    //                 $grandTotal = $dataApi['grand_total'];
    //                 $ppnTotal = 0;
    
    //                 // if($receiver->ppn == 1){
    //                 //     $ppnTotal = abs(round(bcdiv(bcmul($grandTotal,0.11,5),1.11,5),2));
    //                 // }
    
    //                 // $hitung = $grandTotal + $ppnTotal;
    
    //                 $grandTotalConvert = ($logjubelio->type === 'SALE') ? -$grandTotal : $grandTotal;
    
    //                 $senderBalance = CustomerStat::where('customer_id', $jubelioSync->warehouse_id)->lockForUpdate()->first();
    //                 $receiverBalance = CustomerStat::where('customer_id', $jubelioSync->customer_id)->lockForUpdate()->first();
    
    //                 $newSenderBalance = $senderBalance->balance - $grandTotalConvert;
    //                 $newRecaiverBalance = $receiverBalance->balance + $grandTotalConvert;
    
    //                 $senderBalance->update(['balance' => $newSenderBalance]);
    //                 $receiverBalance->update(['balance' => $newRecaiverBalance]);
    
    
    //                 // Buat transaksi
    //                 $transactionData = [
    //                     'date' => now()->toDateString(),
    //                     'type' => Transaction::TYPE_SELL,
    //                     'sender_id' => $sender->id,
    //                     'receiver_id' => $receiver->id,
    //                     'sender_type' => $sender->type,
    //                     'receiver_type' => $receiver->type,
    //                     'adjustment' => $adjustmentFee,
    //                     'invoice' => $dataApi['salesorder_no'],
    //                     'total' => $grandTotalConvert,
    //                     'total_items' => $sumQty,
    
    //                     'sender_balance' => $newSenderBalance,
    //                     'receiver_balance' => $newRecaiverBalance,
    //                     'real_total' => $sumTotal,
    
    //                     'created_at' => now(),
    //                     'updated_at' => now()
    //                 ];
    
    //                 $transactionId = DB::table('transactions')->insertGetId($transactionData);
    
    //                 // Insert detail transaksi
    //                 $transactionDetails = $transactionDetails->map(function ($detail) use ($transactionId) {
    //                     $detail['transaction_id'] = $transactionId;
    //                     return $detail;
    //                 });
    
    //                 DB::table('transaction_details')->insert($transactionDetails->toArray());
    
    
    //                 // Update transaksi terkait dengan lock
    //                 // Transaction::where('receiver_id', $receiver->id)
    //                 //     ->where('date', '>', $transactionData['date'])
    //                 //     ->lockForUpdate()
    //                 //     ->increment('receiver_balance', $grandTotalConvert);
    
    
    //                 $result = DB::table('transaction_details')
    //                 ->where('transaction_details.transaction_id',$transactionId)
    //                 ->join('items', 'transaction_details.item_id', '=', 'items.id')
    //                 ->whereIn('transaction_details.transaction_type', [2, 15]) // Filter transaction_type 2 dan 15
    //                 ->selectRaw('
    //                     items.group_id,
    //                     MONTH(transaction_details.date) as bulan,
    //                     YEAR(transaction_details.date) as tahun,
    //                     transaction_details.sender_id,
    //                     transaction_details.transaction_type,
    //                     SUM(transaction_details.quantity) as sum_qty,
    //                     SUM(transaction_details.total) as sum_total
    //                 ')
    //                 ->groupBy('items.group_id', DB::raw('MONTH(transaction_details.date)'), DB::raw('YEAR(transaction_details.date)'), 'transaction_details.sender_id', 'transaction_details.transaction_type')
    //                 ->orderBy('items.group_id') // Optional: Untuk urutan hasil
    //                 ->sharedLock()
    //                 ->get();
            
    //                 $insertData = [];
    //                 foreach ($result as $row) {
    //                     $insertData[] = [
    //                         'group_id' => $row->group_id,
    //                         'bulan' => $row->bulan,
    //                         'tahun' => $row->tahun,
    //                         'sender_id' => $row->sender_id,
    //                         'type' => $row->transaction_type,
    //                         'sum_qty' => (int)$row->sum_qty,
    //                         'sum_total' => (int)$row->sum_total,
    //                         'created_at' => now(),
    //                         'updated_at' => now(),
    //                     ];
    //                 }
    
    //                 foreach ($insertData as $entry) {
    //                     $existing = DB::table('stat_sells')
    //                         ->where('group_id', $entry['group_id'])
    //                         ->where('bulan', $entry['bulan'])
    //                         ->where('tahun', $entry['tahun'])
    //                         ->where('sender_id', $entry['sender_id'])
    //                         ->sharedLock()
    //                         ->first();
            
    //                     if ($existing) {
    //                         // Jika data ditemukan, update sum_qty dan sum_total
    //                         DB::table('stat_sells')
    //                             ->where('id', $existing->id)
    //                             ->incrementEach([
    //                                 'sum_qty' => $entry['sum_qty'],
    //                                 'sum_total' => $entry['sum_total']
    //                             ]);
    //                     } else {
    //                         // Jika tidak ditemukan, insert data baru
    //                         DB::table('stat_sells')->insert([
    //                             'group_id' => $entry['group_id'],
    //                             'bulan' => $entry['bulan'],
    //                             'tahun' => $entry['tahun'],
    //                             'sender_id' => $entry['sender_id'],
    //                             'type' => $entry['type'],
    //                             'sum_qty' => $entry['sum_qty'],
    //                             'sum_total' => $entry['sum_total'],
    //                             'created_at' => now(),
    //                             'updated_at' => now(),
    //                         ]);
    //                     }
    //                 }
    
    //                 $logjubelio->status = 1;
                    
    //                 $logjubelio->save();
    
    //                 DB::commit();
    
    //                 return redirect()->route('transaction.getDetail',$transactionId);

    //                 }
    //             }

              

    //         } catch (QueryException $e) {
    //             DB::rollBack();

    //             dd($e);
                
    //             Log::error('Database Error: ' . $e->getMessage());

    //             if ($e->errorInfo[1] == 1213 && $retryCount < $maxRetries) {
    //                 $retryCount++;
    //                 usleep(100000);
    //                 continue;
    //             }

    //             return redirect()->back()->with('errorMessage', 'Terjadi kesalahan database');
    //         } catch (Exception $e) {
    //             DB::rollBack();
    //             Log::error('Error: ' . $e->getMessage());
    //             return redirect()->back()->with('errorMessage', $e->getMessage());
    //         }
    //     }

    //     return redirect()->back()->with('errorMessage', 'Gagal memproses setelah 3 kali percobaan');
        
    // }

    public function retur(Request $request){
        $secret = 'corenation2025';
        $content = trim($request->getContent());

        $sign = hash_hmac('sha256',$content . $secret, $secret, false);

        $signature = $request->header('Sign');

        if ($signature !== $sign) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        $dataApi = $request->all(); 

        $logStore = [];

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/sales/sales-returns/'. $dataApi['return_id']); 

        

        if ($response->failed()) {
            $statusCode = $response->status();
            
            // Tangani kasus jika ID tidak ditemukan (404)
            if ($statusCode === 404) {
                return response()->json([
                    'status' => 'ok',
                    'pesan' => 'Data retur tidak ditemukan.',
                    'logStore' => $logStore
                ], 200);
                
            }

            return response()->json([
                'status' => 'ok',
                'pesan' => 'Gagal mengambil data dari API. Response: ' . $response->body(),
                'logStore' => $logStore
            ], 200);
    
            
        }

        $data = json_decode($response->body(), true);
        
        $cekTransaksiSell = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$data['salesorder_no'])->first();

        if (!$cekTransaksiSell) {
            return response()->json([
                'status' => 'ok',
                'pesan' => 'Transaksi sell tidak ada.',
                'logStore' => $logStore
            ], 200);

            
        }


        // $produkIds = collect($dataApi['items'])->pluck('item_code')->unique(); // Hilangkan duplikasi ID
        $itemCodes = collect($data['items'])->pluck('item_code')->unique();

        // Ambil hanya kolom yang diperlukan
        $existingProducts = Item::whereIn('code', $itemCodes)
            ->get(['id', 'code', 'name'])
            ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat
        
        // Proses matching dengan map agar lebih efisien
        $groupedData = collect($data['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));
        
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

        if($matched->count() > 0){

            $cekTransaksi = Transaction::where('type',Transaction::TYPE_RETURN)->where('invoice',$dataApi['return_no'])->first();

            if($cekTransaksi){

                return response()->json([
                    'status' => 'ok',
                    'pesan' => 'Invoice Retur sudah ada',
                    'logStore' => $logStore
                ], 200);

            }else{

                $jubelioSync = Jubeliosync::where('jubelio_store_id',$data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();

                // $ongkir = $dataApi['shipping_cost']-$dataApi['shipping_cost_discount'];

                // $adjust = $dataApi['total_disc']+$dataApi['add_disc']+$ongkir+$dataApi['total_tax']+$dataApi['service_fee']+$dataApi['insurance_cost'];

                $adjust = $data['sub_total'] - $data['grand_total'];

                $dataJubelio = [
                    "date" => Carbon::now()->toDateString(),
                    "due" => null,
                    "warehouse" => $jubelioSync->warehouse_id,
                    "customer" => $jubelioSync->customer_id,
                    "invoice" => $dataApi['return_no'],
                    "description" => $data['salesorder_no'],
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

                $dataInvoice = $dataApi['return_no']." - ".$data['salesorder_no'];

            
                if($createData['status'] == "200" ){

                    

                    if($notMatched->count() > 0){

                        $notMactheArray = [];

                        foreach ($notMatched as $dataRow) {
                            $notMactheArray[] = [
                                'transaction_list' => $createData['transaction_id'],
                                'item_code' => $dataRow['item_code'],
                                'item_name' =>  $dataRow['item_name'],
                                'channel' =>  $dataRow['item_code'],
                                'loc_name' =>  $data['customer_name'],
                                'thumbnail' =>  '',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                        }

                        DB::table('notmatcheditems')->insert($notMactheArray);

                        $skuNotmatche = $notMatched->count()." SKU tidak ditemukan";
                    
                        $logStore = $this->logJubelio($dataApi['return_id'],'TRANSACTION','RETURN-SR',$data['customer_name'],$data['location_name'],$dataInvoice,$data['store_id'],$data['location_id'],$skuNotmatche,10);

                    }

                }else{

                    $logStore = $this->logJubelio($dataApi['return_id'],'SYSTEM','RETURN-SR',$data['customer_name'],$data['location_name'],$dataInvoice,$data['store_id'],$data['location_id'],$createData['message'],10);


                    return response()->json([
                        'status' => 'ok',
                        'pesan' => 'Gagal membuat data transaksi',
                        'pesan_detail' => $createData['message'],
                        'logStore' => $logStore
                    ], 200);

                }

            }

        }
        
        

    }

    protected function toggleSign($value) {
        return -$value;
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
            $threshold = Carbon::parse('2025-03-06');

            $limitTime = $tanggal->lessThan($threshold) ? 0 : 1;

            $cekCron = Logjubelio::where('order_id',$dataApi['salesorder_id'])->where('invoice',$dataApi['salesorder_no'])->where('type','SALE')->first();

            if($cekCron){

                return response()->json([
                    'status' => 'ok',
                    'pesan' => 'log cron sudah ada',
                    'logStore' => $logStore
                ], 200);

            }

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

                        $cekTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

                        if($cekTransaksi){

                            

                        // $logStore =  $this->logJubelio('RETURN',$dataApi['store_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Invoice transaksi sudah ada');


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
                                
                                    $logStore = $this->logJubelio($dataApi['salesorder_id'],'TRANSACTION','SALE',$dataApi['source_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],$skuNotmatche);
            
                                }
            
                            }else{

                                $logStore = $this->logJubelio($dataApi['salesorder_id'],'SYSTEM','SALE',$dataApi['source_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],$createData['message'],$createData['deadLock']);


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

                    $logStore = $this->logJubelio($dataApi['salesorder_id'],'TRANSACTION','SALE',$dataApi['source_name'],$dataApi['location_name'],$dataApi['salesorder_no'],$dataApi['store_id'],$dataApi['location_id'],'Data sync dengan aria tidak ditemukan');
                    
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

        
        }elseif ($dataApi['status'] == "CANCELED") {

            $dataTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

            if($dataTransaksi){
                if($dataTransaksi->jubelio_return > 0){

                    return response()->json([
                        'status' => 'ok',
                        'detail' => 'Transaksi sudah return',
                        'pesan' => $dataApi['status'],
                    ], 200);

                }else{

                    $returnData = new Jubelioreturn();
                    
                    $returnData->order_id = $dataApi['salesorder_id'];
                    $returnData->transaction_id = $dataTransaksi->id;
                    $returnData->method_pay = $dataApi['payment_method'];
                    $returnData->invoice = $dataApi['salesorder_no'];
                    $returnData->pesan = $dataApi['cancel_reason_detail'];
                    $returnData->location_name = $dataApi['location_name'];
                    $returnData->store_name = $dataApi['source_name'];

                    $returnData->save();

                    return response()->json([
                        'status' => 'ok',
                        'detail' => 'Transaksi return',
                        'pesan' => $dataApi['status'],
                    ], 200);
                }

            }else{

                // $returnData = new Jubelioreturn();
                    
                // $returnData->order_id = $dataApi['salesorder_id'];
                // $returnData->transaction_id = $dataTransaksi->id;
                // $returnData->method_pay = $dataApi['payment_method'];
                // $returnData->invoice = $dataApi['salesorder_no'];
                // $returnData->pesan = 'Transaksi tidak ditemukan';
                // $returnData->location_name = $dataApi['location_name'];
                // $returnData->store_name = $dataApi['source_name'];

                // $returnData->save();

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

                $transaction->description = $dataJubelio->description ?? '';
                $transaction->invoice = $dataJubelio->invoice;

                $transaction->submit_type = 2;

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
                        'deadLock' => 0
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
                        'status' => '422',
                        'message' => $e->getMessage(),
                        'deadLock' => 0
                    ];

                  
                }

                return $data = [
                    'status' => '422',
                    'message' => $e->getErrors()['error'][0],
                    'deadLock' => 0
                ];

                
                // return response()->json($e->getErrors(), 500);
            
            } catch(\Exception $e) {
                DB::rollBack();

                return $data = [
                        'status' => '422',
                        'message' => $e->getMessage(),
                        'deadLock' => 0
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

    private function qtyType($type, $qty){

        if($type == 1){
            $qtyValue = $qty;
        }else{
            $qtyValue = -$qty;
        }

        return $qtyValue;

    } 

    public function adjustStok($id, Request $request){

        try {
            DB::beginTransaction();
        
            $trans = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id', $id)->first();
        
            if ($trans->user_jubelio) {
                return redirect()->route('transaction.getDetail', $id);
            }
        
            $jubelioLocation = [];
        
            if ($request->whType == 1) {
                $jubelioLocation = Jubeliosync::where('warehouse_id', $trans->receiver_id)->first();
            } else if ($request->whType == 2) {
                $jubelioLocation = Jubeliosync::where('warehouse_id', $trans->sender_id)->first();
            }
        
            if (is_null($jubelioLocation)) {
                return redirect()->route('transaction.getDetail', $id)->with('fail', 'Type transaction tidak valid');
            }
        
            $now = Carbon::now('UTC');
            $formatted = $now->format('Y-m-d\TH:i:s.000\Z');
        
            $detailItem = [];
        
            foreach ($trans->transactionDetail as $row) {
                $detailItem[] = [
                    "item_adj_detail_id" => 0,
                    "item_id" => $row->item->jubelio_item_id,
                    "serial_no" => null,
                    "qty_in_base" => $this->qtyType($request->adjustType, $row->quantity),
                    "original_item_adj_detail_id" => 0,
                    "unit" => "Buah",
                    "amount" => $row->total,
                    "location_id" => $jubelioLocation->jubelio_location_id,
                    "account_id" => 75,
                    "description" => "Item " . $row->item->code,
                    "batch_no" => null,
                    "expired_date" => null,
                    "bin_id" => $jubelioLocation->jubelio_location_bin,
                    "cost" => 0,
                ];
            }
        
            $dataArray = [
                "item_adj_id" => 0,
                "item_adj_no" => "[auto]",
                "transaction_date" => $formatted,
                "note" => "Adjust form aria with order no. " . $trans->invoice,
                "location_id" => $jubelioLocation->jubelio_location_id,
                "is_opening_balance" => false,
                "items" => $detailItem
            ];
        
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'authorization' => Cache::get('jubelio_data')['token'],
            ])->post('https://api2.jubelio.com/inventory/adjustments/warehouse', $dataArray);
        
            if ($response->successful()) {
                $data = json_decode($response->body(), true);
        
                if ($request->side == 1) {
                    $trans->a_submit_by = Auth::user()->id;
                    $trans->a_reference_id = $data['id'];
                } elseif ($request->side == 2) {
                    $trans->b_submit_by = Auth::user()->id;
                    $trans->b_reference_id = $data['id'];
                }
        
                $trans->save();
                DB::commit();
        
                return redirect()->route('transaction.detailJubelioSync', $id)->with('success', 'Jubelio adjustment updated');
            } else {
                DB::rollBack();
        
                $error = json_decode($response->body(), true);

                // dd($error['code']);

                // throw new \Exception("Jubelio API Error: $error");

                $message = $error['message'] ?? 'Terjadi kesalahan.';
                $code = $error['code'] ?? '500';
        
                return redirect()->route('transaction.detailJubelioSync', $id)->with('fail', 'Gagal adujustment stock' . $code);
            }
        
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('transaction.detailJubelioSync', $id)->with('fail', 'Gagal melakukan proses. Error: ' . $e->getMessage());
        }    
      
    }

    public function getItem($id){

        $item = Item::find($id);

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/inventory/items/to-stock/',[
            'q' => $item->code,
        ]); 

        $data = json_decode($response->body(), true);

        if($data['totalCount'] == 0){

            DB::table('items')->where('code',$item->code)->update([
                'jubelio_item_id' => 0, // Kolom yang diperbarui
            ]);
           
            if($item->type == Item::TYPE_ITEM){
                return redirect()->route('item.jubelio',$id)->with('fail', 'Item ID not found');
    
            }else if($item->type == Item::TYPE_ASSET_LANCAR){
                return redirect()->route('asetLancar.jubelio',$id)->with('fail', 'Item ID not found');
            }else{
                return redirect()->route('dashboard');
            }
        }else{
            
            DB::table('items')->where('code',$item->code)->update([
                'jubelio_item_id' => $data['data'][0]['item_id'], // Kolom yang diperbarui
            ]);

            if($item->type == Item::TYPE_ITEM){
                return redirect()->route('item.jubelio',$id)->with('success', 'Item updated');
    
            }else if($item->type == Item::TYPE_ASSET_LANCAR){
                return redirect()->route('asetLancar.jubelio',$id)->with('success', 'Item updated');
            }else{
                return redirect()->route('dashboard');
            }
           
        }
    }
}
