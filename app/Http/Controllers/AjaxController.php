<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getCostumer(Request $request)
    {
       
        $customer = Customer::where('name','like','%'.$request->search.'%')->where('type',$request->type)->paginate();
        return response()->json($customer, 200);
    }

    public function getCostumerSingle(Request $request)
    {
       
        $customer = Customer::find($request->idCust);
        return response()->json($customer, 200);
    }
}
