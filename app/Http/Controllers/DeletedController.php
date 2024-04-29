<?php

namespace App\Http\Controllers;

use App\Helpers\StockManagerHelpers;
use App\Models\Deleted;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DeletedController extends Controller
{
    public function index(Request $request)
	{
		$allType = Transaction::$typesJSON;

		$dataList = Deleted::with('receiver','sender')->orderBy('date','desc')->orderBy('id','desc');

		if($request->from && $request->to){
			$dataList = $dataList->whereDate('date','>=',$request->from)->whereDate('date','<=',$request->to);
		}

		if($request->invoice){
			$dataList = $dataList->where('invoice',$request->invoice);
		}

		if($request->total){
			$dataList = $dataList->where('total',$request->total);
		}

		if($request->type){
			$dataList = $dataList->where('type',$request->type);
		}

		$dataList = $dataList->paginate(20)->withQueryString();

		return view('transactions.delete',compact('dataList','allType'));
	}

    public function getDetailDelete($id)
    {

		$data = Deleted::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		$nameWh = StockManagerHelpers::$names;

		

		return view('transactions.delete-detail',compact('data','nameWh'));

    }
}
