<?php

namespace App\Http\Controllers;

use App\Models\Jubelioreturn;
use App\Models\Transaction;
use Illuminate\Http\Request;

class JubelioReturnController extends Controller
{
    public function index(Request $request){

        
        $dataList = Jubelioreturn::with('user')->orderBy('updated_at','asc');

        if($request->status == "SOLVED"){
            $dataList = $dataList->whereIn('status',[1,2]);
        }else{
            $dataList = $dataList->where('status',0);
        }
       
        
        if($request->from && $request->to){
			$dataList = $dataList->whereDate('updated_at','>=',$request->from)->whereDate('updated_at','<=',$request->to);
		}

		if($request->invoice){
			$dataList = $dataList->where('invoice',$request->invoice);
		}
        
        $dataList = $dataList->paginate(50)->withQueryString();

        // dd($dataList);

        return view('jubelio.return.index',compact('dataList'));
    }

    
	public function jubelioReturn($id){

		$data = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$id)->first();

		return view('jubelio.return.detail',compact('data'));
	}
}
