<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class VWarehouseController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_VWAREHOUSE;
      
        $nameType = "vwarehouse";


        return view('vwarehouse.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_VWAREHOUSE;
        $action = 'vwarehouse.detail';
      

        return view('vwarehouse.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'vwarehouse.detail';
        return view('vwarehouse.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {
       

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('vwarehouse.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = "vwarehouse";

        $customerType = Customer::TYPE_VWAREHOUSE;

        return view('vwarehouse.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('vwarehouse.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('vwarehouse.stat',compact('cid','start','nameCustomer'));
    }

    public function itemsale($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('vwarehouse.itemsale',compact('cid','start','nameCustomer'));
    }
}
