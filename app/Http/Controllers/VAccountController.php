<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;

class VAccountController extends Controller
{
    private function customer($id)
    {
        $customer = Customer::withTrashed()->findOrFail($id);

        return $customer->name;
    }


    public function index()
    {
        $customerType = Customer::TYPE_VACCOUNT;
      
        $nameType = "vaccount";


        return view('vaccount.index',compact('customerType','nameType'));
    }

    public function create()
    {
        $customerType = Customer::TYPE_VACCOUNT;
        $action = 'vaccount.detail';
      

        return view('vaccount.create',compact('customerType','action'));
    }

   

    public function Edit($id)
    {
        $cid = $id;
        $action = 'vaccount.detail';
        return view('vaccount.edit',compact('action','cid'));
    }

    


    public function transaction($id)
    {
       

        $cid = $id;

        $typeList = Transaction::$typesJSON;
        
        $nameCustomer = $this->customer($id);
        // dd($nameCustomer);

       
        return view('vaccount.transaction',compact('cid','typeList','nameCustomer'));
    }

    public function detail($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        $nameType = "vaccount";

        $customerType = Customer::TYPE_VACCOUNT;

        return view('vaccount.detail',compact('cid','nameCustomer','nameType','customerType'));
    }

    public function items($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);
        

        return view('vaccount.item',compact('cid','nameCustomer'));
    }

    public function stat($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('vaccount.stat',compact('cid','start','nameCustomer'));
    }

    public function itemsale($id)
    {
        $cid = $id;
        $nameCustomer = $this->customer($id);

        $start = date('Y');

        return view('vaccount.itemsale',compact('cid','start','nameCustomer'));
    }
}
