<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_BANK;
      
        $nameType = "account";


        return view('account.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_BANK;
        $action = 'account.detail';
      

        return view('account.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'account.detail';
        return view('account.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {
       

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('account.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = "account";

        $customerType = Customer::TYPE_BANK;

        return view('account.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('account.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('account.stat',compact('cid','start','nameCustomer'));
    }

    public function itemsale($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('account.itemsale',compact('cid','start','nameCustomer'));
    }
}
