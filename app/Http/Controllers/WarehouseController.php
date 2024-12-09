<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_WAREHOUSE;
      
        $nameType = "warehouse";


        return view('warehouse.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_WAREHOUSE;
        $action = 'warehouse.detail';
      

        return view('warehouse.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'warehouse.detail';
        return view('warehouse.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {
       

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('warehouse.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = 'warehouse';

        $customerType = Customer::TYPE_WAREHOUSE;

        return view('warehouse.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('warehouse.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('warehouse.stat',compact('cid','start','nameCustomer'));
    }

    public function itemsale($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('warehouse.itemsale',compact('cid','start','nameCustomer'));
    }
}
