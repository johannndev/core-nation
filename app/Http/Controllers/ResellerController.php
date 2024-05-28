<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ResellerController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_RESELLER;
      
        $nameType = "reseller";


        return view('reseller.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_RESELLER;
        $action = 'reseller.detail';
      

        return view('reseller.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'reseller.detail';
        return view('reseller.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {
       

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('reseller.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = "reseller";

        $customerType = Customer::TYPE_RESELLER;

        return view('reseller.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('reseller.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('reseller.stat',compact('cid','start','nameCustomer'));
    }
}
