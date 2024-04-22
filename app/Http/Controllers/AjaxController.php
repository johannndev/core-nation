<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\WarehouseItem;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function getCostumer(Request $request)
    {
       
        $customer = Customer::where('name','like','%'.$request->search.'%')->where('type',$request->type)->get();

        // $customer = $customer->get(['id', 'name']);
        return response()->json($customer, 200);
    }

    

    public function getCostumerSingle(Request $request)
    {
       
        $customer = Customer::find($request->idCust);
        return response()->json($customer, 200);
    }

    public function getItem(Request $request)
    {
       
        $item = Item::where('name','like','%'.$request->q.'%')->orderBy('name','asc')->get();
        return response()->json($item, 200);
    }

    public function getItemAjax(Request $request)
	{

		$itemId = $request->item_id;

		$itemGet = Item::where('id',$itemId)->orWhere('code', $itemId)->orWhere('name', $itemId)->first();

		if($request->wh_id){

			$wh= WarehouseItem::where('item_id',$itemGet->id)->where('warehouse_id',$request->wh_id)->first();
            
            if ($wh) {
                
			    $whGet = $wh->quantity;
               
            } else {
                $whGet = 0;
            }
            


		}else{
			$whGet = 0;
		}

		$data = [
			'data' => $itemGet,
			'whQty' => $whGet,
		];

        return response()->json($data);
		
	}
}
