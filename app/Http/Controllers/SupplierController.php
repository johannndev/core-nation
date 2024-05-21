<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }

    public function index()
    {
        $customerType = Customer::TYPE_SUPPLIER;
      
        $nameType = "supplier";


        return view('supplier.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_SUPPLIER;
        $action = 'supplier.detail';
      

        return view('supplier.create',compact('customerType','action'));
    }
    


    public function Edit($id)
    {
        $cid = $id;
        $action = 'supplier.detail';
        return view('customer.edit',compact('action','cid'));
    }

    public function transaction($id)
    {
       
        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('supplier.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = 'supplier';

        $customerType = Customer::TYPE_CUSTOMER;

        return view('supplier.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('supplier.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('supplier.stat',compact('cid','start','nameCustomer'));
    }

}
