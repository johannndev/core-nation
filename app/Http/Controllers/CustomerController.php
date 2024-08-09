<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use App\Models\Transaction;


class CustomerController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

      
        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_CUSTOMER;
      
        $nameType = "customer";


        return view('customer.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_CUSTOMER;
        $action = 'customer.detail';
      

        return view('customer.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'customer.detail';
        return view('customer.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {

      

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('customer.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = 'customer';

        $customerType = Customer::TYPE_CUSTOMER;

        return view('customer.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('customer.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('customer.stat',compact('cid','start','nameCustomer'));
    }

   

}
