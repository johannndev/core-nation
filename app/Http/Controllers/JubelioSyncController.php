<?php

namespace App\Http\Controllers;

use App\Helpers\JubelioHelper;
use App\Models\Customer;
use App\Models\Jubeliosync;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JubelioSyncController extends Controller
{

    public function index(){
        $dataList = Jubeliosync::with(['warehouse','customer']);

       

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('jubelio_location_name','LIKE',"%$name%");
		}
		if($id = Request('id')) {
			$dataList = $dataList->where('memberId','=', $id);
		}

      

		
        
        $dataList = $dataList->orderBy('created_at','desc')->paginate(50)->withQueryString();

        return view('jubelio.sync.index',compact('dataList'));
    }

    public function create()
    {

        $dataApi = JubelioHelper::checkOrUpdateData('jub', 'new_value');

        

        $response = Http::withHeaders([
            'Authorization' => $dataApi->sk
        ])->get('https://api2.jubelio.com/locations/', [
            'page' => 1,
            'pageSize' => 200
        ]);

        $dataList = $response->json();

        dd($dataList);
        

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
       
 

        return view('jubelio.sync.create',compact('dataList','dataListPropWarehouse','dataListPropCustomer'));
    }

    public function store(Request $request){

        $rules = [
            'location_id'  => 'required',
            'warehouse'  => 'required',
            'customer' => 'required',
           
            
		];

        $attributes = [
            'location_id'  => 'Jubelio location',
            'warehouse'  => 'Warehouse',
            'customer' => 'Customer',
            
        ];

        $this->validate($request, $rules, [], $attributes);

     
        $data = new Jubeliosync();

        $data->jubelio_location_id = $request->location_id;
        $data->jubelio_location_name = $request->locationName;
        $data->warehouse_id = $request->warehouse;
        $data->customer_id = $request->customer;

        $data->save();

        return redirect()->route('jubelio.sync.index')->with('success', 'Jubelio sync created.');
      

    }

    public function delete($id){
        $data = Jubeliosync::find($id);

        $data->delete();

        return redirect()->route('jubelio.sync.index')->with('success', 'Jubelio sync deleted.');
    }
}
