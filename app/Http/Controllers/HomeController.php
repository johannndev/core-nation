<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
	public function index()
	{

		
		$onlineStat = Customer::selectRaw("
			SUM(CASE WHEN is_online = 1 AND type = ? THEN 1 ELSE 0 END) as online_customer,
			SUM(CASE WHEN is_online = 1 AND type = ? THEN 1 ELSE 0 END) as online_warehouse,
			SUM(CASE WHEN is_online = 1 AND type IN (?, ?) THEN 1 ELSE 0 END) as online_total,

			SUM(CASE WHEN is_online = 0 AND type = ? THEN 1 ELSE 0 END) as offline_customer,
			SUM(CASE WHEN is_online = 0 AND type = ? THEN 1 ELSE 0 END) as offline_warehouse,
			SUM(CASE WHEN is_online = 0 AND type IN (?, ?) THEN 1 ELSE 0 END) as offline_total,

			SUM(CASE WHEN type = ? THEN 1 ELSE 0 END) as type_customer_total,
			SUM(CASE WHEN type = ? THEN 1 ELSE 0 END) as type_warehouse_total
		", [
			Customer::TYPE_CUSTOMER,  // online_customer
			Customer::TYPE_WAREHOUSE, // online_warehouse
			Customer::TYPE_CUSTOMER,  // online_total IN
			Customer::TYPE_WAREHOUSE, // online_total IN

			Customer::TYPE_CUSTOMER,  // offline_customer
			Customer::TYPE_WAREHOUSE, // offline_warehouse
			Customer::TYPE_CUSTOMER,  // offline_total IN
			Customer::TYPE_WAREHOUSE, // offline_total IN

			Customer::TYPE_CUSTOMER,  // type_customer_total
			Customer::TYPE_WAREHOUSE, // type_warehouse_total
		])->first()->toArray();


		return view('home',compact('onlineStat'));
	}

    public function getIndex()
	{
		// if($this->_access->hide('smart-home',Apps::HIDE))
		// {
		// 	$this->layout->content = View::make('home',$this->_data);
		// 	return;
		// }
		// $this->layout->action = 'Dashboard';

		//check location
		// $lids = false;
		// if($this->_lm->bound())
		// 	$lids = $this->_lm->get_location();

		// //hutang piutang
		// $cs_table = CustomerStat::table();
		// $customer_table = Customer::table();

		// $query = DB::table("$cs_table as cs_table")->select(array(
		// 	DB::raw('SUM(CASE WHEN cs_table.balance < 0 AND customer_table.type = '.Customer::TYPE_CUSTOMER.' THEN cs_table.balance ELSE 0 END) as total_customer_piutang'),
		// 	DB::raw('SUM(CASE WHEN cs_table.balance > 0 AND customer_table.type = '.Customer::TYPE_CUSTOMER.' THEN cs_table.balance ELSE 0 END) as total_customer_hutang'),
		// 	DB::raw('SUM(CASE WHEN cs_table.balance < 0 AND customer_table.type = '.Customer::TYPE_RESELLER.' THEN cs_table.balance ELSE 0 END) as total_reseller_piutang'),
		// 	DB::raw('SUM(CASE WHEN cs_table.balance > 0 AND customer_table.type = '.Customer::TYPE_RESELLER.' THEN cs_table.balance ELSE 0 END) as total_reseller_hutang'),
		// 	DB::raw('SUM(CASE WHEN cs_table.balance < 0 AND customer_table.type = '.Customer::TYPE_SUPPLIER.' THEN cs_table.balance ELSE 0 END) as total_supplier_piutang'),
		// 	DB::raw('SUM(CASE WHEN cs_table.balance > 0 AND customer_table.type = '.Customer::TYPE_SUPPLIER.' THEN cs_table.balance ELSE 0 END) as total_supplier_hutang'),
		// 	DB::raw('SUM(CASE WHEN customer_table.type = '.Customer::TYPE_WAREHOUSE.' THEN cs_table.balance ELSE 0 END) as total_warehouse_asset'),
		// ));
		// if($lids)
		// 	$query = $query->whereIn('cs_table.customer_id', $lids);
		// $query = $query->join("$customer_table as customer_table", 'customer_table.id', '=', 'cs_table.customer_id');
		// $this->_data['hp'] = $query->first();

		// $this->layout->content = View::make('smarthome',$this->_data);

        return view('dashboard.smarthome');
	}

	

	public function cekData()
	{
		$data = WarehouseItem::with('warehouse')->where('item_id','9990')->get();

		dd($data);
	}

}
