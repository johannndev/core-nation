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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogJubelioController extends Controller
{
    protected function toggleSign($value) {
        return -$value;
    }

    public function gotoTransaction($id){

        $data = Transaction::where('invoice',$id)->first();

        if($data){
            return redirect()->route('transaction.getDetail',$id);
        }else{
            return redirect()->route('jubelio.log.index')->with('errorMessage', 'Transaksi tidak ada');
        }

    }
    
    public function index(Request $request){

        
        $dataList = Logjubelio::with('user')->orderBy('updated_at','desc');

        if($request->status == "SOLVED"){
            $dataList = $dataList->whereIn('status',[1,2]);
        }else{
            $dataList = $dataList->where('status',0);
        }
       
        
        if($request->from && $request->to){
			$dataList = $dataList->whereDate('updated_at','>=',$request->from)->whereDate('updated_at','<=',$request->to);
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

    public function createSolved($id){

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

        return view('log.solved',compact('jubelioSync','data','adjust','sid'));
    }

    public function storeSolved($id){
        $logjubelio = Logjubelio::findOrFail($id);
        $logjubelio ->status = 1;
        $logjubelio->user_solved_by = Auth::user()->id;
        $logjubelio->cron_run = 10;

        $logjubelio->save();

        return redirect()->route('jubelio.log.index')->with('success','Jubelio Log Solved');
    }



    // public function postManualgpt($id)
    // {
    //     $maxRetries = 3;
    //     $retryCount = 0;

    //     while ($retryCount < $maxRetries) {
    //         try {
    //             DB::beginTransaction(); // Mulai transaksi

    //             $logjubelio = Logjubelio::findOrFail($id);

    //             $response = Http::withHeaders([
    //                 'Content-Type' => 'application/json',
    //                 'authorization' => Cache::get('jubelio_data')['token'],
    //             ])->get("https://api2.jubelio.com/sales/orders/{$logjubelio->order_id}");

    //             $dataApi = json_decode($response->body(), true);

    //             $jubelioSync = Jubeliosync::where('jubelio_store_id', $dataApi['store_id'])
    //                 ->where('jubelio_location_id', $dataApi['location_id'])
    //                 ->firstOrFail();

    //             $sender = Customer::findOrFail($jubelioSync->warehouse_id);
    //             $receiver = Customer::findOrFail($jubelioSync->customer_id);

    //             $itemCodes = collect($dataApi['items'])->pluck('item_code')->unique();

    //             $existingProducts = Item::whereIn('code', $itemCodes)
    //                 ->get(['id', 'code', 'name'])
    //                 ->keyBy('code');

    //             $groupedData = collect($dataApi['items'])->partition(fn($item) => isset($existingProducts[$item['item_code']]));

    //             $matched = $groupedData[0]->map(fn($item) => [
    //                 'item_id' => $existingProducts[$item['item_code']]->id,
    //                 'quantity' => $item['qty'],
    //                 'price' => $item['price'],
    //                 'total' => $item['qty'] * $item['price'],
    //                 'date' => now()->toDateString(),
    //                 'transaction_type' => Transaction::TYPE_SELL,
    //                 'sender_id' => $sender->id,
    //                 'receiver_id' => $receiver->id,
    //             ])->values();

    //             if ($groupedData[1]->isNotEmpty()) {
    //                 throw new Exception('Produk tidak ditemukan: ' . implode(", ", $groupedData[1]->pluck('item_code')->toArray()));
    //             }

    //             if (Transaction::where('invoice', $dataApi['salesorder_no'])->exists()) {
    //                 throw new Exception('Invoice transaksi sudah ada');
    //             }

    //             $adjust = $dataApi['sub_total'] - $dataApi['grand_total'];

    //             $transactionId = DB::table('transactions')->insertGetId([
    //                 'date' => now()->toDateString(),
    //                 'type' => Transaction::TYPE_SELL,
    //                 'sender_id' => $sender->id,
    //                 'receiver_id' => $receiver->id,
    //                 'invoice' => $dataApi['salesorder_no'],
    //                 'adjustment' => $adjust,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             $warehouseStock = WarehouseItem::whereIn('item_id', $matched->pluck('item_id'))
    //                 ->whereIn('warehouse_id', $matched->pluck('sender_id'))
    //                 ->lockForUpdate()
    //                 ->pluck('quantity', 'item_id');

    //             $insufficientStock = [];
    //             $updateCases = [];
    //             $idsToUpdate = [];
    //             $sumTotal = 0;
    //             $sumQty = 0;

    //             foreach ($matched as $item) {
    //                 $warehouseQty = $warehouseStock[$item['item_id']] ?? 0;
    //                 if ($item['quantity'] > $warehouseQty) {
    //                     $insufficientStock[] = "Stok tidak cukup untuk {$item['item_id']}. Butuh {$item['quantity']}, tersedia {$warehouseQty}.";
    //                 } else {
    //                     $updateCases[] = "WHEN item_id = {$item['item_id']} AND warehouse_id = {$item['sender_id']} THEN quantity - {$item['quantity']}";
    //                     $idsToUpdate[] = "{$item['item_id']}_{$item['sender_id']}";
    //                     $sumTotal += $item['total'];
    //                     $sumQty += $item['quantity'];
    //                 }
    //             }

    //             if (!empty($insufficientStock)) {
    //                 throw new Exception(implode("\n", $insufficientStock));
    //             }

    //             if (!empty($updateCases)) {
    //                 DB::statement("
    //                     UPDATE warehouse_item
    //                     SET quantity = CASE " . implode(" ", $updateCases) . " END
    //                     WHERE CONCAT(item_id, '_', warehouse_id) IN ('" . implode("', '", $idsToUpdate) . "')
    //                 ");
    //             }

    //             foreach ($matched as &$item) {
    //                 $item['transaction_id'] = $transactionId;
    //                 $item['created_at'] = now();
    //                 $item['updated_at'] = now();
    //             }
    //             DB::table('transaction_details')->insert($matched->toArray());

    //              // Update balance dengan lock
               
    //              $grandTotal = $sumTotal - $adjust;
    //              $ppnTotal = 0;
 
    //              if($receiver->ppn == 1){
    //                  $ppnTotal = abs(round(bcdiv(bcmul($grandTotal,0.11,5),1.11,5),2));
    //              }
 
                

    //             $hitung = $sumTotal -$grandTotal + $ppnTotal;
    //             $totalTransaction = ($logjubelio->type === 'SALE') ? -$hitung : $hitung;

    //             $senderBalance = CustomerStat::where('customer_id', $jubelioSync->warehouse_id)->lockForUpdate()->first();
    //             $receiverBalance = CustomerStat::where('customer_id', $jubelioSync->customer_id)->lockForUpdate()->first();

    //             $senderBalance->update(['balance' => $senderBalance->balance + $hitung]);
    //             $receiverBalance->update(['balance' => $receiverBalance->balance - $hitung]);

    //             DB::table('transactions')->where('id', $transactionId)->update([
    //                 'sender_balance' => $senderBalance->balance,
    //                 'receiver_balance' => $receiverBalance->balance,
    //                 'total' => $totalTransaction,
    //                 'total_items' => $sumQty,
    //                 'real_total' => $sumTotal,
    //                 'updated_at' => now(),
    //             ]);

    //             Transaction::where('receiver_id', $receiver->id)
    //                 ->where('date', '>', now()->toDateString())
    //                 ->lockForUpdate()
    //                 ->increment('receiver_balance', $totalTransaction);

    //             DB::commit();

    //             return redirect()->route('transaction.getDetail',$transactionId);

    //         } catch (QueryException $e) {
                
    //             DB::rollBack();
    //             Log::error('Database Error: ' . $e->getMessage());

    //             dd($e->getMessage());

    //             if (strpos($e->getMessage(), 'Deadlock') !== false && $retryCount < $maxRetries) {
    //                 $retryCount++;
    //                 usleep(100000); // Delay 100ms sebelum mencoba ulang
    //                 continue;
    //             }

    //             return redirect()->back()->with('errorMessage', 'Database error: ' . $e->getMessage());
    //         } catch (Exception $e) {
    //             DB::rollBack();
    //             Log::error('Error: ' . $e->getMessage());
    //             dd($e->getMessage());
    //             return redirect()->back()->with('errorMessage', $e->getMessage());
    //         }
    //     }
    // }


    public function postManualSeek($id)
    {

            DB::beginTransaction();

            try {

                $logjubelio = Logjubelio::lockForUpdate()->findOrFail($id);

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

                $cekTransaksi = Transaction::where('type',Transaction::TYPE_SELL)->where('invoice',$dataApi['salesorder_no'])->first();

                if ($cekTransaksi) {
                    throw new Exception('Invoice sudah ada.');
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

                $newSenderBalance = $senderBalance->balance - $grandTotalConvert;
                $newRecaiverBalance = $receiverBalance->balance + $grandTotalConvert;

                $senderBalance->update(['balance' => $newSenderBalance]);
                $receiverBalance->update(['balance' => $newRecaiverBalance]);

                $user_id = Auth::user() ? Auth::user()->id : -100;

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
                    'sender_balance' => $newSenderBalance,
                    'receiver_balance' => $newRecaiverBalance,
                    'real_total' => $sumTotal,
                    'submit_type' => 2,
                    'user_id' => $user_id,
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


                // Update transaksi terkait dengan lock
                // Transaction::where('receiver_id', $receiver->id)
                //     ->where('date', '>', $transactionData['date'])
                //     ->lockForUpdate()
                //     ->increment('receiver_balance', $grandTotalConvert);


                $result = DB::table('transaction_details')
                ->where('transaction_details.transaction_id',$transactionId)
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

                foreach ($insertData as $entry) {
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

                $logjubelio->status = 1;
                $logjubelio->user_solved_by = Auth::user()->id;
                $logjubelio->cron_run = 10;
                $logjubelio->save();

                DB::commit();

                return redirect()->route('transaction.getDetail',$transactionId);

            } catch (QueryException $e) {
                DB::rollBack();

                dd($e);
                
                Log::error('Database Error: ' . $e->getMessage());

                return redirect()->back()->with('errorMessage', 'Terjadi kesalahan database');
            } catch (Exception $e) {
                DB::rollBack();
                Log::error('Error: ' . $e->getMessage());
                return redirect()->back()->with('errorMessage', $e->getMessage());
            }
        

        return redirect()->back()->with('errorMessage', 'Gagal memproses setelah 3 kali percobaan');
    }



    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}
