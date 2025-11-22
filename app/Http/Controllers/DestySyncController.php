<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\DestySync;
use App\Models\DestyWarehouse;
use Illuminate\Http\Request;

class DestySyncController extends Controller
{

    public function index(){
        $dataList = DestySync::with(['destyWarehouse','warehouse','customer']);

       

        // if(Request('name')) {
		// 	$name = str_replace(' ', '%', Request('name'));
		// 	$dataList = $dataList->where('jubelio_location_name','LIKE',"%$name%");
		// }
		// if($id = Request('id')) {
		// 	$dataList = $dataList->where('memberId','=', $id);
		// }

      

		
        
        $dataList = $dataList->orderBy('created_at','desc')->paginate(50)->withQueryString();

        return view('desty.sync.index',compact('dataList'));
    }

    public function create()
    {

        // $dataApi = JubelioHelper::checkOrUpdateData('jub', 'new_value');



        $destyWarehouses = DestyWarehouse::orderBy('platform_warehouse_name', 'asc')->get();

        // dd($dataList);


        $dataListPropWarehouse = [
            "label" => "Warehouse",
            "id" => "warehouse",
            "idList" => "datalistWh",
            "idOption" => "datalistOptionsWh",
            "type" => Customer::TYPE_WAREHOUSE,

        ];

        $dataListPropCustomer = [
            "label" => "Customer",
            "id" => "customer",
            "idList" => "datalistCs",
            "idOption" => "datalistOptionsCs",
            "type" => Customer::TYPE_CUSTOMER,

        ];



        return view('desty.sync.create', compact('dataListPropWarehouse', 'dataListPropCustomer','destyWarehouses'));
    }

    //function store(){} ke model desty sync
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'desty_id' => 'required|integer',
            'warehouse' => 'required|integer',
            'customer' => 'required|integer',
        ]);

        // Simpan data ke database
        DestySync::create([
            'desty_warehouse_id' => $validatedData['desty_id'],
            'warehouse_id' => $validatedData['warehouse'],
            'customer_id' => $validatedData['customer'],
        ]);

        return redirect()->route('desty.sync.index')->with('success', 'Desty Sync created successfully.');
    }

    public function delete($id)
    {
        $destySync = DestySync::findOrFail($id);
        $destySync->delete();

        return redirect()->route('desty.sync.index')->with('success', 'Desty Sync deleted successfully.');
    }
}
