<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Restock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    public function index()
    {
        $restocks = Restock::with('item')->orderBy('date', 'desc')->paginate(10);
        return view('restock.index', compact('restocks'));
    }

    public function create()
    {
        $userId   = auth()->id();
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
            'qty'  => 'required|integer|min:1',

        ];

        $attributes = [

            'code'  => 'Code',
            'qty' => 'Quantity',

        ];

        $this->validate($request, $rules, [], $attributes);


        $userId   = auth()->id();
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
        if (!$found) {
            $items[] = [
                'code' => $request->code,
                'name' => $itemData ? $itemData->name : 'Unknown Item',
                'qty'  => $request->qty,
            ];
        }

        // simpan ulang cache (expired 1 jam)
        Cache::put($cacheKey, $items, now()->addHour());

        return redirect()->route('restock.create')->with('success', 'Item added to restock list.');
    }

    public function listItem()
    {
        $userId   = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $items = Cache::get($cacheKey, []);

        return response()->json($items);
    }

    public function removeItem($code)
    {
        $userId   = auth()->id();
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

        $userId   = auth()->id();
        $cacheKey = "cart_items_user_{$userId}";

        $items = Cache::get($cacheKey, []);

        if (empty($items)) {
            return back()->withErrors([
                'item' => 'Tidak ada data untuk disimpan'
            ]);
        }

        $now  = now();
        $date = $request->date; // ✅ pakai tanggal dari form

        $data = collect($items)->map(function ($item) use ($now, $date) {
            return [
                'item_id'                => $item['code'],
                'date'                   => $date,
                'status'                 => 1,
                'restocked_quantity'     => $item['qty'],
                'in_production_quantity' => 0,
                'shipped_quantity'       => 0,
                'missing_quantity'       => 0,
                'created_at'             => $now,
                'updated_at'             => $now,
            ];
        })->toArray();

        DB::transaction(function () use ($data, $cacheKey) {
            Restock::insert($data);
            Cache::forget($cacheKey);
        });

        return redirect()
            ->route('restock.index')
            ->with('success', 'Data restock berhasil disimpan');
    }
}
