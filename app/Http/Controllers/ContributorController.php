<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ContributorController extends Controller
{
    public function index(Request $request)
    {
        $date = Carbon::now();
		$from = $date->startOfMonth()->toDateString();
		$to = $date->endOfMonth()->toDateString();
        
        $typeList = Item::$brandsJSON;

        // dd($typeList);
        // dd(Tag::loadSizes());

        if($request->customerId){
			$default = $request->customerId;
			
		}else{
			$default = null;
		}

        $dataListPropCustomer = [
			"label" => "Addr Book",
			"id" => "customerId",
			"idList" => "datalistSender",
			"idOption" => "datalistOptionsSender",
			"type" => Customer::TYPE_CUSTOMER.",".Customer::TYPE_RESELLER.",".Customer::TYPE_WAREHOUSE,
			"default" => $default,
			
			
		];

        return view('contributor.index',compact('from','to','typeList','dataListPropCustomer'));
    }
}
