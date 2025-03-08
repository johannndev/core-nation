<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerStat;
use App\Models\Item;
use App\Models\Jubeliosync;
use App\Models\Logjubelio;
use App\Models\Transaction;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Log;

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

        $logjubelio = Logjubelio::findOrFail($id);

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/sales/orders/'.$logjubelio->order_id); 

        $data = json_decode($response->body(), true);

        $adjust = $data['sub_total'] - $data['grand_total'];

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();
        
        $sid = $id;

        return view('log.manual',compact('jubelioSync','data','adjust','sid'));
    }

    public function postManual($id){
        try {
            DB::beginTransaction();
            
            // Ambil data Jubelio Sync dengan Lock agar tidak ada perubahan selama transaksi
            $jubelioSync = JubelioSync::where('status', 0)->lockForUpdate()->firstOrFail();
            
            // Ambil data item yang sesuai
            $matched = WarehouseItem::where('warehouse_id', $jubelioSync->warehouse_id)
                ->whereIn('item_id', $jubelioSync->items->pluck('item_id'))
                ->lockForUpdate()
                ->get();
            
            if ($matched->isEmpty()) {
                throw new Exception("Item tidak ditemukan dalam warehouse.");
            }
            
            // Ambil data pelanggan (sender & receiver) sekaligus
            $customers = Customer::whereIn('id', [$jubelioSync->warehouse_id, $jubelioSync->customer_id])
                ->get()
                ->keyBy('id');
            
            $sender = $customers[$jubelioSync->warehouse_id] ?? null;
            $receiver = $customers[$jubelioSync->customer_id] ?? null;
            
            if (!$sender || !$receiver) {
                throw new Exception("Sender atau Receiver tidak ditemukan.");
            }
            
            // Persiapkan update stok dengan metode batch
            $updateCases = [];
            $ids = [];
            
            foreach ($matched as $item) {
                $updateCases[] = "WHEN id = {$item->id} THEN quantity - {$item->quantity}";
                $ids[] = $item->id;
            }
            
            $updateQuery = "UPDATE warehouse_items SET quantity = CASE " . implode(" ", $updateCases) . " END WHERE id IN (" . implode(",", $ids) . ")";
            DB::statement($updateQuery);
            
            // Ambil saldo pelanggan dengan lock
            $balances = CustomerStat::whereIn('customer_id', [$jubelioSync->warehouse_id, $jubelioSync->customer_id])
                ->lockForUpdate()
                ->get()
                ->keyBy('customer_id');
            
            $senderBalance = $balances[$jubelioSync->warehouse_id] ?? null;
            $receiverBalance = $balances[$jubelioSync->customer_id] ?? null;
            
            if (!$senderBalance || !$receiverBalance) {
                throw new Exception("Saldo pelanggan tidak ditemukan.");
            }
            
            // Insert transaction
            $transaction = Transaction::create([
                'sender_id' => $jubelioSync->warehouse_id,
                'receiver_id' => $jubelioSync->customer_id,
                'total' => $jubelioSync->total,
                'transaction_date' => Carbon::now(),
            ]);
            
            // Insert transaction details secara batch
            $detailArray = array_map(function ($item) use ($transaction) {
                return [
                    'transaction_id' => $transaction->id,
                    'item_id' => $item['item_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'],
                    'total' => $item['total'],
                    'date' => Carbon::now(),
                    'transaction_type' => $item['transaction_type'],
                    'sender_id' => $item['sender_id'],
                    'receiver_id' => $item['receiver_id'],
                    'transaction_disc' => $item['transaction_disc'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }, $matched->toArray());
            
            DB::table('transaction_details')->insert($detailArray);
            
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            
            // Jika terjadi deadlock, coba ulangi dengan delay acak
            if (str_contains($e->getMessage(), 'Deadlock')) {
                usleep(random_int(100000, 500000));
                // Ulangi transaksi jika perlu
            }
            
            throw $e;
        }
        
    }

    public function postManualgpt($id)
    {
        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                DB::beginTransaction(); // Mulai transaksi

                $logjubelio = Logjubelio::findOrFail($id);

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'authorization' => Cache::get('jubelio_data')['token'],
                ])->get("https://api2.jubelio.com/sales/orders/{$logjubelio->order_id}");

                $dataApi = json_decode($response->body(), true);

                $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])
                    ->where('jubelio_location_id', $dataApi['location_id'])
                    ->firstOrFail();

                $sender = Customer::findOrFail($jubelioSync->warehouse_id);
                $receiver = Customer::findOrFail($jubelioSync->customer_id);

                $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();

                $existingProducts = Item::whereIn('code', $itemCodes)
                    ->get(['id', 'code', 'name'])
                    ->keyBy('code');

                $groupedData = collect($dataApi['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));

                $matched = $groupedData[0]->map(fn($item) => [
                    'item_id' => $existingProducts[$item['item_code']]->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['qty'] * $item['price'],
                    'date' => now()->toDateString(),
                    'transaction_type' => Transaction::TYPE_SELL,
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                ])->values();

                if ($groupedData[1]->isNotEmpty()) {
                    throw new Exception('Produk tidak ditemukan: ' . implode(", ", $groupedData[1]->pluck('item_code')->toArray()));
                }

                if (Transaction::where('invoice', $dataApi['salesorder_no'])->exists()) {
                    throw new Exception('Invoice transaksi sudah ada');
                }

                $adjust = $dataApi['sub_total'] - $dataApi['grand_total'];

                $transactionId = DB::table('transactions')->insertGetId([
                    'date' => now()->toDateString(),
                    'type' => Transaction::TYPE_SELL,
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'invoice' => $dataApi['salesorder_no'],
                    'adjustment' => $adjust,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $warehouseStock = WarehouseItem::whereIn('item_id', $matched->pluck('item_id'))
                    ->whereIn('warehouse_id', $matched->pluck('sender_id'))
                    ->lockForUpdate()
                    ->pluck('quantity', 'item_id');

                $insufficientStock = [];
                $updateCases = [];
                $idsToUpdate = [];
                $sumTotal = 0;
                $sumQty = 0;

                foreach ($matched as $item) {
                    $warehouseQty = $warehouseStock[$item['item_id']] ?? 0;
                    if ($item['quantity'] > $warehouseQty) {
                        $insufficientStock[] = "Stok tidak cukup untuk {$item['item_id']}. Butuh {$item['quantity']}, tersedia {$warehouseQty}.";
                    } else {
                        $updateCases[] = "WHEN item_id = {$item['item_id']} AND warehouse_id = {$item['sender_id']} THEN quantity - {$item['quantity']}";
                        $idsToUpdate[] = "{$item['item_id']}_{$item['sender_id']}";
                        $sumTotal += $item['total'];
                        $sumQty += $item['quantity'];
                    }
                }

                if (!empty($insufficientStock)) {
                    throw new Exception(implode("\n", $insufficientStock));
                }

                if (!empty($updateCases)) {
                    DB::statement("
                        UPDATE warehouse_item
                        SET quantity = CASE " . implode(" ", $updateCases) . " END
                        WHERE CONCAT(item_id, '_', warehouse_id) IN ('" . implode("', '", $idsToUpdate) . "')
                    ");
                }

                foreach ($matched as &$item) {
                    $item['transaction_id'] = $transactionId;
                    $item['created_at'] = now();
                    $item['updated_at'] = now();
                }
                DB::table('transaction_details')->insert($matched->toArray());

                 // Update balance dengan lock
               
                 $grandTotal = $sumTotal - $adjust;
                 $ppnTotal = 0;
 
                 if($receiver->ppn == 1){
                     $ppnTotal = abs(round(bcdiv(bcmul($grandTotal,0.11,5),1.11,5),2));
                 }
 
                

                $hitung = $sumTotal -$grandTotal + $ppnTotal;
                $totalTransaction = ($logjubelio->type === 'SALE') ? -$hitung : $hitung;

                $senderBalance = CustomerStat::where('customer_id', $jubelioSync->warehouse_id)->lockForUpdate()->first();
                $receiverBalance = CustomerStat::where('customer_id', $jubelioSync->customer_id)->lockForUpdate()->first();

                $senderBalance->update(['balance' => $senderBalance->balance + $hitung]);
                $receiverBalance->update(['balance' => $receiverBalance->balance - $hitung]);

                DB::table('transactions')->where('id', $transactionId)->update([
                    'sender_balance' => $senderBalance->balance,
                    'receiver_balance' => $receiverBalance->balance,
                    'total' => $totalTransaction,
                    'total_items' => $sumQty,
                    'real_total' => $sumTotal,
                    'updated_at' => now(),
                ]);

                Transaction::where('receiver_id', $receiver->id)
                    ->where('date', '>', now()->toDateString())
                    ->lockForUpdate()
                    ->increment('receiver_balance', $totalTransaction);

                DB::commit();

                return redirect()->route('transaction.getDetail',$transactionId);

            } catch (QueryException $e) {
                
                DB::rollBack();
                Log::error('Database Error: ' . $e->getMessage());

                dd($e->getMessage());

                if (strpos($e->getMessage(), 'Deadlock') !== false && $retryCount < $maxRetries) {
                    $retryCount++;
                    usleep(100000); // Delay 100ms sebelum mencoba ulang
                    continue;
                }

                return redirect()->back()->with('errorMessage', 'Database error: ' . $e->getMessage());
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage());
                dd($e->getMessage());
                return redirect()->back()->with('errorMessage', $e->getMessage());
            }
        }
    }


    public function postManualSeek($id)
    {
        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            DB::beginTransaction();

            try {

                $logjubelio = Logjubelio::findOrFail($id);

                // Ambil data dari API
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

                if (!$jubelioSync) {
                    throw new Exception('Data sync dengan aria tidak ditemukan.');
                }

                $sender = Customer::findOrFail($jubelioSync->warehouse_id);
                $receiver = Customer::findOrFail($jubelioSync->customer_id);

                // Proses item
                $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();
                $existingProducts = Item::whereIn('code', $itemCodes)
                    ->get(['id', 'code', 'name'])
                    ->keyBy('code');

                [$matched, $notMatched] = collect($dataApi['items'])->partition(
                    fn($item) => isset($existingProducts[$item['item_code']])
                );

                if ($notMatched->isNotEmpty()) {
                    throw new Exception(implode(', ', $notMatched->pluck('item_code')->toArray()) . ' tidak ditemukan');
                }

                // Format data transaksi
                $transactionDetails = $matched->map(function ($item) use ($existingProducts, $sender, $receiver) {
                    return [
                        'item_id' => $existingProducts[$item['item_code']]->id,
                        'quantity' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['qty'] * $item['price'],
                        'date' => now()->toDateString(),
                        'transaction_type' => Transaction::TYPE_SELL,
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        
                    ];
                });

                // Validasi stok gudang dengan lock
                $itemIds = $transactionDetails->pluck('item_id')->unique();
                $warehouseItems = WarehouseItem::where('warehouse_id', $sender->id)
                    ->whereIn('item_id', $itemIds)
                    ->lockForUpdate()
                    ->get(['item_id', 'quantity']);

                $stockIssues = [];
                $updateCases = [];
                $sumTotal = 0;
                $sumQty = 0;

                foreach ($transactionDetails as $detail) {
                    $stock = $warehouseItems->firstWhere('item_id', $detail['item_id']);
                    if (!$stock || $stock->quantity < $detail['quantity']) {
                        $stockIssues[] = "Stok {$detail['item_id']} tidak mencukupi";
                    }

                    $sumTotal += $detail['total'];
                    $sumQty += $detail['quantity'];
                    $updateCases[] = "WHEN item_id = {$detail['item_id']} THEN quantity - {$detail['quantity']}";
                }

                if (!empty($stockIssues)) {
                    throw new Exception(implode(', ', $stockIssues));
                }

                // Update stok gudang
                WarehouseItem::where('warehouse_id', $sender->id)
                    ->whereIn('item_id', $itemIds)
                    ->update([
                        'quantity' => DB::raw("CASE " . implode(' ', $updateCases) . " END")
                    ]);

                     // Update balance dengan lock
                $adjustmentFee = $dataApi['sub_total'] - $dataApi['grand_total'];

                $grandTotal = $dataApi['grand_total'];
                $ppnTotal = 0;

                // if($receiver->ppn == 1){
                //     $ppnTotal = abs(round(bcdiv(bcmul($grandTotal,0.11,5),1.11,5),2));
                // }

                // $hitung = $grandTotal + $ppnTotal;

                $grandTotalConvert = ($logjubelio->type === 'SALE') ? -$grandTotal : $grandTotal;

                $senderBalance = CustomerStat::where('customer_id', $jubelioSync->warehouse_id)->lockForUpdate()->first();
                $receiverBalance = CustomerStat::where('customer_id', $jubelioSync->customer_id)->lockForUpdate()->first();

                $newSenderBalance = $senderBalance->balance + $grandTotalConvert;
                $newRecaiverBalance = $receiverBalance->balance - $grandTotalConvert;

                $senderBalance->update(['balance' => $newSenderBalance]);
                $receiverBalance->update(['balance' => $newRecaiverBalance]);


                // Buat transaksi
                $transactionData = [
                    'date' => now()->toDateString(),
                    'type' => Transaction::TYPE_SELL,
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiver->id,
                    'sender_type' => $sender->type,
                    'receiver_type' => $receiver->type,
                    'adjustment' => $adjustmentFee,
                    'invoice' => $dataApi['salesorder_no'],
                    'total' => $grandTotalConvert,
                    'total_items' => $sumQty,

                    'sender_balance' => $senderBalance,
                    'receiver_balance' => $receiverBalance,
                    'real_total' => $sumTotal,

                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $transactionId = DB::table('transactions')->insertGetId($transactionData);

                // Insert detail transaksi
                $transactionDetails = $transactionDetails->map(function ($detail) use ($transactionId) {
                    $detail['transaction_id'] = $transactionId;
                    return $detail;
                });

                DB::table('transaction_details')->insert($transactionDetails->toArray());

               

                CustomerStat::where('customer_id', $sender->id)
                    ->lockForUpdate()
                    ->increment('balance', $totalTransaction);

                CustomerStat::where('customer_id', $receiver->id)
                    ->lockForUpdate()
                    ->decrement('balance', $totalTransaction);

                // Update transaksi terkait dengan lock
                Transaction::where('receiver_id', $receiver->id)
                    ->where('date', '>', $transactionData['date'])
                    ->lockForUpdate()
                    ->increment('receiver_balance', $totalTransaction);

                DB::commit();

                return redirect()->route('transaction.getDetail',$transactionId);

            } catch (QueryException $e) {
                DB::rollBack();

                dd($e);
                
                Log::error('Database Error: ' . $e->getMessage());

                if ($e->errorInfo[1] == 1213 && $retryCount < $maxRetries) {
                    $retryCount++;
                    usleep(100000);
                    continue;
                }

                return redirect()->back()->with('errorMessage', 'Terjadi kesalahan database');
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage());
                return redirect()->back()->with('errorMessage', $e->getMessage());
            }
        }

        return redirect()->back()->with('errorMessage', 'Gagal memproses setelah 3 kali percobaan');
    }

    public function postManualalt($id){


        $maxRetries = 3;
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                $response = Http::withHeaders([ 
                    'Content-Type'=> 'application/json', 
                    'authorization'=> Cache::get('jubelio_data')['token'], 
                ]) 
                ->get('https://api2.jubelio.com/sales/orders/'.$id); 
        
                $dataApi = json_decode($response->body(), true);
        
                $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])->where('jubelio_location_id',$dataApi['location_id'])->first();
        
                if($jubelioSync){

                    $sender = Customer::find($jubelioSync->warehouse_id);
                    $receiver = Customer::find($jubelioSync->customer_id);
        
                    // $produkIds = collect($dataApi['items'])->pluck('item_code')->unique(); // Hilangkan duplikasi ID
                    $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();
        
                    // Ambil hanya kolom yang diperlukan
                    $existingProducts = Item::whereIn('code', $itemCodes)
                        ->get(['id', 'code', 'name'])
                        ->keyBy('code'); // Index berdasarkan 'code' agar pencarian lebih cepat
                    
                    // Proses matching dengan map agar lebih efisien
                    $groupedData = collect($dataApi['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));
                    
                    $matched = $groupedData[0]->map(fn($item) => [
                        'item_id'   => $existingProducts[$item['item_code']]->id,
                        'quantity' => $item['qty'],
                        'price'    => $item['price'],
                        'discount' => 0,
                        'total' => $item['qty']*$item['price'],
                        'date' => Carbon::now()->toDateString(),
                        'transaction_type' => Transaction::TYPE_SELL,
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'transaction_disc' => 0,
                        'code'     => $existingProducts[$item['item_code']]->code,
                        'name'     => $existingProducts[$item['item_code']]->name,
                        
                    ])->values(); // Reset indeks array


                    
                    $notMatched = $groupedData[1]->values(); // Reset indeks array
        
                    $createData = [];

                    

                    if($notMatched->count() > 0){

                        // Ambil item_code dari notMatched
                        $item_codes = array_column($notMatched->toArray(), 'item_code');

                        // Ubah menjadi string dengan koma sebagai pemisah
                        $notMatchedString = implode(", ", $item_codes);

                        throw new Exception($notMatchedString.' tidak di temukan');

                    }
                                                
                    if($matched->count() > 0){

                      
                        $cekTransaksi = Transaction::where('invoice',$dataApi['salesorder_no'])->first();
        
                        if($cekTransaksi){
                            throw new Exception('Invoice transaksi sudah ada');
                        
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
                                "adjustment" =>  $adjust,
                                "ongkir" => "0"
                            ];
        
                            // create transaksi   
        
                            $transaction = DB::table('transactions')->insertGetId([
                                'date' => $dataJubelio['date'],
                                'type' => Transaction::TYPE_SELL,
                                'sender_id' => $sender->id,
                                'receiver_id' => $receiver->id,
                                'sender_type' => $sender->type,
                                'receiver_type' => $receiver->type,
                                'adjustment' => $dataJubelio['adjustment'],
                                'invoice' => $dataJubelio['invoice'],
                                'due' => $dataJubelio['due'] ?? '',
                                'detail_ids' => ' ',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);


                            // cek quantity update quantity

                            $warehouseStock = WarehouseItem::whereIn('item_id', array_column($matched->toArray(), 'item_id'))
                            ->whereIn('warehouse_id', array_column($matched->toArray(), 'sender_id'))
                            ->lockForUpdate() // Kunci row agar tidak diakses transaksi lain
                            ->pluck('quantity', 'item_id');
                
                            $insufficientStock = [];
                            $allStockSufficient = true;
                            $updateCases = [];
                            $idsToUpdate = [];
                            $sumTotal = 0; 
                            $sumQty = 0;
                    
                            // Cek stok cukup atau tidak
                            foreach ($matched->toArray() as $item) {
                                $warehouseQty = $warehouseStock[$item['item_id']] ?? 0;
                                $requestedQty = (float) $item["quantity"];
                                $totalItemPrice = $item["total"];
                    
                                if ($requestedQty > $warehouseQty) {
                                    $insufficientStock[] = "Quantity {$item['code']} membutuhkan {$requestedQty}, di gudang hanya {$warehouseQty}.";
                                    $allStockSufficient = false;
                                } else {
                                    // Siapkan query batch update
                                    $updateCases[] = "WHEN item_id = {$item['item_id']} AND warehouse_id = {$item['sender_id']} THEN quantity - {$requestedQty}";
                                    $idsToUpdate[] = "{$item['item_id']}_{$item['sender_id']}";

                                     // Tambahkan total transaksi ke sumTotal
                                    $sumTotal += $totalItemPrice;
                                    $sumQty += $requestedQty;
                                }
                            }
                    
                            // Jika stok tidak cukup, rollback dan tampilkan pesan
                            if (!$allStockSufficient) {
                            
                                throw new Exception(implode("\n", $insufficientStock));  
                            }
                    
                            // Jika semua stok cukup, lakukan batch update
                            if (!empty($updateCases)) {
                                $updateQuery = "
                                    UPDATE warehouse_item
                                    SET quantity = CASE " . implode(" ", $updateCases) . " END
                                    WHERE CONCAT(item_id, '_', warehouse_id) IN ('" . implode("', '", $idsToUpdate) . "')
                                ";
                                DB::statement($updateQuery); // Jalankan query batch update
                            }

                            $detailArray = $matched->toArray();

                            foreach ($detailArray as &$item) {
                                $item['transaction_id'] = $transaction;
                                $item['created_at'] = Carbon::now();
                                $item['update_at'] = Carbon::now();
                                unset($item['code'], $item['name']); // Hapus code dan name
                            }
                            unset($item);

                            DB::table('transaction_details')->insert($detailArray);

                    
                            $hitung =  $sumTotal - $dataJubelio['adjutment'] + ($sumTotal * 0.12);

                            if ($dataApi['status'] == "SHIPPED") {
                                $totalTransaction = -$hitung; 
                            } else {
                                $totalTransaction = $hitung; 
                            }

                            // Update stok produk
                            $sender = Customer::find($jubelioSync->warehouse_id);
                            $receiver = Customer::find($jubelioSync->customer_id);
                    
                            $sender_balance = CustomerStat::where('customer_id',$jubelioSync->warehouse_id)->lockForUpdate()->first();
                            $recaiver_balance = CustomerStat::where('customer_id',$jubelioSync->customer_id)->lockForUpdate()->first();

                            $senderNewBalance = $sender_balance->balance+$hitung;
                            $recaiverNewBalance = $recaiver_balance->balance-$hitung;

                            $sender_balance->update(['balance' => $senderNewBalance]);
                            $recaiver_balance->update(['balance'=> $recaiverNewBalance]);

                            $itemId = array_column($matched->toArray(), 'item_id');

                            $updatedData = DB::table('transactions')
                            ->where('id', $transaction)
                            ->update([
                                'sender_balance' => $senderNewBalance,
                                'receiver_balance' => $recaiverNewBalance,
                                'total' => $totalTransaction,
                                'total_items' => $sumQty,
                                'detail_ids' => implode(", ", $itemId),
                                'real_total' => $sumTotal,
                                'updated_at' => now()
                            ]);

                        if (!$updatedData) {
                            throw new Exception('Gagal memperbarui order.');
                        }

                          // Ambil semua transaksi dengan tanggal lebih besar, lalu kunci data dengan FOR UPDATE
                            $transaksirecaiverAtas = Transaction::where('recaiver_id',$receiver->id)->where('date', '>', $dataJubelio['date'])
                            ->lockForUpdate()
                            ->increment(['recaiver_balance',$totalTransaction]);

                        }
        
                    }

                    
                
                }else{

                    throw new Exception('Data sync dengan aria tidak ditemukan.');
                }

                DB::commit();

                return redirect()->route('jubelio.log.index');

            } catch (QueryException $e) {
                DB::rollBack();

                dd($e->getMessage());
                Log::error('Database Error: ' . $e->getMessage());
    
                if (strpos($e->getMessage(), 'Deadlock') !== false && $retryCount < $maxRetries) {
                    $retryCount++;
                    usleep(100000); // Delay 100ms sebelum mencoba ulang
                    continue;
                }
    
                return redirect()->route('orders.create')->with('error', 'Database error: ' . $e->getMessage());
            } catch (Exception $e) {
                DB::rollBack();

                dd($e->getMessage());
                Log::error('Error: ' . $e->getMessage());
                return redirect()->route('orders.create')->with('error', $e->getMessage());
            }
        }

       
    }

    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}
