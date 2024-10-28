<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\WarehouseCompare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CompareController extends Controller
{
    public function index(Request $request)
    {
        // Ambil warehouse IDs dari filter form


        
        $warehouseCompare = WarehouseCompare::where('user_id',Auth::user()->id)->orderBy('created_at','asc')->pluck('werehouse_id')->toArray();

        // dd($warehouseCompare);

        // $warehouseIds = $request->input('warehouse_ids', $warehouseCompare); // Default adalah array kosong jika tidak ada yang dipilih

        
		$dataListPropWarehouse = [
			"label" => "Warehouse",
			"id" => "warehouse",
			"idList" => "datalistWarehouse",
			"idOption" => "datalistOptionsWarehouse",
			"type" => Customer::TYPE_WAREHOUSE,
		];

        // Dapatkan daftar warehouse untuk form
        $allWarehouses = Customer::where('type',2)->select('id', 'name')->get();

        $wherehouseHead = WarehouseCompare::with('warehouse')->where('user_id',Auth::user()->id)->orderBy('created_at','asc')->get();

        // dd($wherehouseHead);

        

        // Jika ada warehouse yang dipilih, buat query dinamis
        if (!empty($warehouseCompare)) {
            $products = DB::table('items')
                ->select('items.id as item_id', 'items.name as produk', 'warehouse_item.warehouse_id', DB::raw('SUM(warehouse_item.quantity) as total_quantity'))
                ->join('warehouse_item', 'items.id', '=', 'warehouse_item.item_id')
                ->whereIn('warehouse_item.warehouse_id', $warehouseCompare)
                ->groupBy('items.id', 'items.name', 'warehouse_item.warehouse_id');

            if($request->produk){

                if($request->produk != "none"){
                    $products = $products->orderBy('produk',$request->produk);
                }

            }else{
                $products = $products->orderBy('produk','asc');
            }

            if($request->wh){

                $products = $products->orderByRaw("SUM(CASE WHEN warehouse_item.warehouse_id = ".$request->wh." THEN warehouse_item.quantity ELSE 0 END) ".$request->sort);  // Sorting by total_quantity in Warehouse A

            }

            $products = $products->paginate(50);

        } else {
            // Jika tidak ada warehouse yang dipilih, kosongkan hasil
            $products = collect();
        }

        // dd($products->toArray());

        return view('compare.index', compact('products', 'allWarehouses', 'warehouseCompare','wherehouseHead','dataListPropWarehouse'));
    }

    public function store(Request $request){

        $request->validate([
            // Validasi unique combination
            'warehouse' => [
                'required',
                Rule::unique('warehouse_compares','werehouse_id')->where(function ($query) {
                    return $query->where('user_id', Auth::user()->id);
                }),
            ],
        ]);

        if($request->warehouse){


            $data = new WarehouseCompare();

            $data->user_id = Auth::user()->id;
            $data->werehouse_id = $request->warehouse;

            $data->save();

        }

        return redirect()->route('compare.index')->with('success', 'Warehouse added.');

    }

    public function delete($id){


        // dd($id);
        $data = WarehouseCompare::where('user_id',Auth::user()->id)->where('werehouse_id',$id)->first();

        $data->delete();

        return redirect()->route('compare.index')->with('success', 'Warehouse deleted.');

    }

}
