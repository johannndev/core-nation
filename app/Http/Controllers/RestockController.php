<?php

namespace App\Http\Controllers;

use App\Exceptions\ModelException;
use App\Helpers\AppSettingsHelper;
use App\Helpers\StatManagerHelper;
use App\Models\Item;
use App\Models\Restock;
use App\Models\RestockHistory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    public function index(Request $request)
    {
        $columnMap = [
            'restock'    => 'restocked_quantity',
            'production' => 'in_production_quantity',
            'shipped'   => 'shipped_quantity',
            'missing'    => 'missing_quantity',
        ];

        $warehouseIds = [2792, 2875, 2851];

        // request
        $searchColumn = $request->get('kolom'); // untuk sorting
        $searchValue  = $request->get('code');  // search id / code
        $sortDir      = $request->get('order');

        $query = Restock::with([
            'item',
            'item.warehouseItem' => function ($q) use ($warehouseIds) {
                $q->whereIn('warehouse_id', $warehouseIds)
                    ->with('warehouse:id,name');
            }
        ]);

        /* =========================
     * SEARCH (relasi item)
     * ========================= */
        if (!empty($searchValue)) {
            $query->whereHas('item', function ($q) use ($searchValue) {
                $q->where('id', 'like', "%{$searchValue}%")
                    ->orWhere('code', 'like', "%{$searchValue}%");
            });
        }
        /* =========================
     * SORT (quantity)
     * ========================= */

        // dd($columnMap[$searchColumn], $sortDir);

        if (isset($columnMap[$searchColumn])) {
            $query->orderBy($columnMap[$searchColumn], $sortDir);
        }

        $restocks = $query->paginate(10)->withQueryString();

        // dd($restocks);

        return view('restock.index', compact('restocks'));
    }

    public function create()
    {
        $userId = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $hasCache = Cache::has($cacheKey);
        if ($hasCache) {
            $items = Cache::get($cacheKey, []);
        } else {
            $items = [];
        }

        return view('restock.create', compact('items'));
    }

    public function addItem(Request $request)
    {
        $rules = [
            'code' => 'required|string',
            'qty' => 'required|integer|min:1',

        ];

        $attributes = [

            'code' => 'Code',
            'qty' => 'Quantity',

        ];

        $this->validate($request, $rules, [], $attributes);

        $userId = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $itemData = Item::find($request->code);

        // ambil cache lama (jika belum ada → array kosong)
        $items = Cache::get($cacheKey, []);

        // cek apakah code sudah ada
        $found = false;
        foreach ($items as &$item) {
            if ($item['code'] === $request->code) {
                $item['qty'] += $request->qty; // akumulasi qty
                $found = true;
                break;
            }
        }

        // kalau belum ada, push item baru
        if (! $found) {
            $items[] = [
                'code' => $request->code,
                'name' => $itemData ? $itemData->name : 'Unknown Item',
                'qty' => $request->qty,
            ];
        }

        // simpan ulang cache (expired 1 jam)
        Cache::put($cacheKey, $items, now()->addHour());

        return redirect()->route('restock.create')->with('success', 'Item added to restock list.');
    }

    public function listItem()
    {
        $userId = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $items = Cache::get($cacheKey, []);

        return response()->json($items);
    }

    public function removeItem($code)
    {
        $userId = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $items = Cache::get($cacheKey, []);

        $items = array_values(array_filter($items, function ($item) use ($code) {
            return $item['code'] !== $code;
        }));

        Cache::put($cacheKey, $items, now()->addHour());

        return redirect()->route('restock.create')->with('success', 'Item removed from restock list.');
    }

    public function store(Request $request)
    {

        $userId = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $items = Cache::get($cacheKey, []);

        if (empty($items)) {
            return back()->withErrors([
                'item' => 'Tidak ada data untuk disimpan',
            ]);
        }

        $now = now();
        $date = $request->date; // ✅ pakai tanggal dari form

        DB::transaction(function () use ($items, $request, $cacheKey, $now, $date) {

            foreach ($items as $item) {

                $restock = Restock::where('item_id', $item['code'])
                    ->where('date', $request->date)
                    ->where('status', 1)
                    ->lockForUpdate() // 🔒 penting untuk race condition
                    ->first();

                if ($restock) {
                    // UPDATE (tambah qty + update date)
                    $before = $restock->restocked_quantity;

                    $restock->increment('restocked_quantity', $item['qty']);
                    $restock->update([
                        'date' => $request->date,
                        'updated_at' => now(),
                    ]);

                    $after = $before + $item['qty'];
                } else {
                    // ✅ JIKA BELUM ADA → INSERT BARU
                    $restock = Restock::create([
                        'item_id' => $item['code'],
                        'date' => $date,
                        'status' => 1,
                        'restocked_quantity' => $item['qty'],
                        'in_production_quantity' => 0,
                        'shipped_quantity' => 0,
                        'missing_quantity' => 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $before = 0;
                    $after = $item['qty'];
                }

                // ✅ HISTORY KHUSUS RESTOCK
                RestockHistory::create([
                    'restock_id' => $restock->id,
                    'item_id' => $item['code'],
                    'step' => 'restocked',
                    'action' => 'created',
                    'qty_before' => $before,
                    'qty_after' => $after,
                    'qty_changed' => $item['qty'],
                    'invoice' => null,
                    'user_id' => auth()->id(),
                    'date' => $request->date,
                ]);
            }

            Cache::forget($cacheKey);
        });

        return redirect()
            ->route('restock.index')
            ->with('success', 'Data restock berhasil disimpan');
    }

    public function update($id)
    {
        $restock = Restock::with('item')->findOrFail($id);

        return view('restock.update', compact('restock'));
    }

    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'type'    => 'required|in:restocked,production,shipped,received,missing',
            'qty'     => 'required|integer|min:1',
            'invoice' => 'nullable|string',
            'date'    => 'required|date',
        ]);

        DB::transaction(function () use ($request, $id) {

            $restock = Restock::lockForUpdate()->findOrFail($id);
            $qty     = (int) $request->qty;
            $type    = $request->type;

            $beforeValue = 0;
            $afterValue  = 0;

            switch ($type) {

                case 'restocked':
                    $beforeValue = $restock->restocked_quantity;
                    $restock->restocked_quantity += $qty;
                    $afterValue  = $restock->restocked_quantity;
                    break;

                case 'production':
                    if ($restock->restocked_quantity < $qty) {
                        throw new \Exception('Restocked quantity tidak cukup');
                    }

                    $beforeValue = $restock->in_production_quantity;

                    $restock->restocked_quantity      -= $qty;
                    $restock->in_production_quantity += $qty;

                    $afterValue  = $restock->in_production_quantity;
                    break;

                case 'shipped':
                    if ($restock->in_production_quantity < $qty) {
                        throw new \Exception('Production quantity tidak cukup');
                    }

                    $beforeValue = $restock->shipped_quantity;

                    $restock->in_production_quantity -= $qty;
                    $restock->shipped_quantity       += $qty;

                    $afterValue  = $restock->shipped_quantity;
                    break;

                case 'missing':
                    $beforeValue = $restock->missing_quantity;
                    $restock->missing_quantity += $qty;
                    $afterValue  = $restock->missing_quantity;
                    break;
            }

            $restock->date = $request->date;
            $restock->save();

            // ✅ HISTORY (BERSIH & BENAR)
            RestockHistory::create([
                'restock_id' => $restock->id,
                'item_id'    => $restock->item_id,
                'step'       => $type,
                'action'     => 'edited',
                'qty_before' => $beforeValue,
                'qty_after'  => $afterValue,
                'qty_changed' => $qty,
                'invoice'    => $request->invoice,
                'user_id'    => auth()->id(),
                'date'       => $request->date,
            ]);
        });

        return redirect()
            ->route('restock.index')
            ->with('success', 'Stock updated successfully');
    }

    public function received($id)
    {
        $restock = Restock::with('item')->findOrFail($id);
        return view('restock.received', compact('restock'));
    }

    public function receiveStore($id, Request $request)
    {
        $request->validate([
            'qty'     => 'required|integer|min:1',
            'date'    => 'required|date',
            'invoice' => 'nullable|string',
        ]);

        $response = DB::transaction(function () use ($id, $request) {

            $restock = Restock::lockForUpdate()->with('item')->findOrFail($id);

            $receiveQty = (int) $request->qty;

            if ($receiveQty > $restock->shipped_quantity) {
                throw new \Exception('Qty diterima melebihi shipped quantity');
            }

            // ✅ PAYLOAD KE GUDANG
            $payload = [
                'date'       => $request->date,
                'due'        => null,
                'customer'   => 373, // shopee core customer
                'warehouse'  => 2875, // sambisari 2024
                'invoice'    => $request->invoice,
                'note'       => null,
                'disc'       => 0,
                'adjustment' => 0,
                'addMoreInputFields' => [
                    [
                        'itemId'   => $restock->item_id,
                        'code'     => $restock->item->item_code,
                        'name'     => $restock->item->name,
                        'quantity' => $receiveQty,
                        'wh'       => 0,
                        'price'    => $restock->item->price,
                        'discount' => 0,
                        'subtotal' => $receiveQty * $restock->item->price,
                    ]
                ],
            ];

            // 🔻 KURANGI SHIPPED QTY
            $before = $restock->shipped_quantity;

            $restock->update([
                'shipped_quantity' => $before - $receiveQty,
            ]);

            // 🧾 HISTORY
            RestockHistory::create([
                'restock_id'  => $restock->id,
                'item_id'     => $restock->item_id,
                'step'        => 'received',
                'action'      => 'receive',
                'qty_before'  => $before,
                'qty_after'   => $restock->shipped_quantity,
                'qty_changed' => $receiveQty,
                'invoice'     => $request->invoice,
                'user_id'     => auth()->id(),
                'date'        => $request->date,
            ]);

            // 🚚 KIRIM KE GUDANG
            return $this->toGudang(Transaction::TYPE_BUY, $payload);
        });


        // ✅ CONVERT JsonResponse → ARRAY
        $result = $response->getData(true);

        if ($result['status'] !== 'ok') {
            return back()->withErrors($result['message']);
        }

        return redirect()
            ->route('transaction.getDetail', $result['trx'])
            ->with('success', 'Transaction #' . $result['trx'] . ' created.');
    }


    protected function toGudang($type = null, $payload)
    {

        try {

            $class = array();


            //start transaction
            DB::beginTransaction();

            $customer = $payload['customer'];
            $warehouse = $payload['warehouse'];


            // dd($customer,$warehouse);

            // $input = $data;
            $transaction = new Transaction();
            $transaction->date = $payload['date'];
            $transaction->type = $type;
            $transaction->due = $payload['due'] ?? '0000-00-00';
            $transaction->description = $payload['note'] ?? ' ';
            $transaction->invoice = $payload['invoice'] ?? ' ';
            $transaction->adjustment = $payload['adjustment'] ?? 0;
            $transaction->discount = $payload['disc'] ?? 0;
            $transaction->submit_type = 1;
            $transaction->detail_ids = ' ';

            $transaction->save();
            switch ($type) {
                case Transaction::TYPE_BUY:
                case Transaction::TYPE_RETURN:
                    $transaction->sender_id = $customer;
                    $transaction->receiver_id = $warehouse;
                    break;
                case Transaction::TYPE_SELL:
                case Transaction::TYPE_RETURN_SUPPLIER:
                    $transaction->sender_id = $warehouse;
                    $transaction->receiver_id = $customer;
                    break;
                default: //don't update stats for move, production
                    break;
            }

            $transaction->init($type);

            // dd($data->addMoreInputFields);
            //gets the transaction id
            if (!$transaction->save())


                throw new ModelException($transaction->getErrors(), __LINE__);

            if (!$details = $transaction->createDetails($payload['addMoreInputFields']))
                throw new ModelException($transaction->getErrors(), __LINE__);


            //check ppn first
            $transaction->checkPPN($transaction->sender, $transaction->receiver);




            //add to customer stat
            // $sm = new StatManager;

            $sm = new StatManagerHelper();
            switch ($type) {
                case Transaction::TYPE_BUY:
                case Transaction::TYPE_RETURN:
                    //add balance to sender(supplier)
                    $sender_balance = $sm->add($transaction->sender_id, $transaction, true); //skip 1 because the transaction is already created?
                    if ($sender_balance === false)
                        throw new ModelException($sm->getErrors());

                    $transaction->sender_balance = $sender_balance;
                    break;
                case Transaction::TYPE_SELL:
                case Transaction::TYPE_RETURN_SUPPLIER:
                    $transaction->setAttribute('total', 0 - $transaction->total); //make negative

                    //deduct balance from receiver(customer)
                    $receiver_balance = $sm->deduct($transaction->receiver_id, $transaction, true);
                    if ($receiver_balance === false)
                        throw new ModelException($sm->getErrors());

                    $transaction->receiver_balance = $receiver_balance;

                    // $transaction->save();

                    // dd($receiver_balance,$transaction, $transaction->receiver_balance);
                    break;
                default: //don't update stats for move, production
                    break;
            }



            if (!$transaction->save())
                throw new $transaction->getErrors();

            $paid = $payload['paid'] ?? false;
            //special case: paid is checked
            if ($type == Transaction::TYPE_SELL && isset($paid) && $paid) {
                //calculate total
                $amount = isset($payload['amount']) ? $payload['amount'] : 0;
                if ($amount <= 0) $amount = abs($transaction->total);

                $payment = $transaction->attachIncome($transaction->date, $transaction->receiver_id, $payload['account'], $amount);
                $class['income'] = $payment->total;

                //another special case, ongkir is filled, create journal
                $settingApp = new AppSettingsHelper;
                $ongkir = isset($payload['ongkir']) ? $payload['ongkir'] : null;
                if (!empty($ongkir))
                    $transaction->attachOngkir($transaction->date, $payment->receiver_id, abs($ongkir), $settingApp->getAppSettings('ongkir'));
            }

            if ($type == Transaction::TYPE_SELL || $type == Transaction::TYPE_RETURN) {



                // Query
                $result = DB::table('transaction_details')
                    ->where('transaction_details.transaction_id', $transaction->id)
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

            // $data->session()->flash('success', 'Transaction # ' . $transaction->id. ' created.');

            // return redirect()->route('transaction.getDetail',$transaction->id)->with('success', 'Transaction # ' . $transaction->id. ' created.');

            return response()->json(['status' => 'ok', 'message' => 'Data berhasil disimpan', 'trx' => $transaction->id]);

            // return response()->json([
            //     'url' => route('transaction.getDetail',$transaction->id,$transaction->date),
            // ]);


        } catch (ModelException $e) {

            DB::rollBack();

            return response()->json(['status' => 'error', 'message' => $e->getErrors()['error'][0]], 200);

            // return redirect()->back()->withInput()->with('errorMessage',$e->getErrors()['error'][0]);
            // return response()->json($e->getErrors(), 500);

        } catch (\Exception $e) {
            DB::rollBack();

            // dd($e);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 200);

            // return redirect()->back()->withInput()->with('errorMessage',$e->getMessage());

            // return response()->json($e->getMessage(), 500);

        }
    }

    public function history($restockId)
    {
        $restock = Restock::with('item')->findOrFail($restockId);

        $histories = RestockHistory::with('user')
            ->where('restock_id', $restockId)
            ->orderBy('id', 'desc')
            ->orderBy('date', 'desc')
            ->paginate(50);



        return view('restock.history', compact('histories', 'restock'));
    }
}
