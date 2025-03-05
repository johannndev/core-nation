<?php

namespace App\Http\Controllers;

use App\Models\Logjubelio;
use Illuminate\Http\Request;

class LogJubelioController extends Controller
{
    public function index(Request $request){
        $dataList = Logjubelio::orderBy('created_at','desc');
        
        if($request->from && $request->to){
			$dataList = $dataList->whereDate('created_at','>=',$request->from)->whereDate('created_at','<=',$request->to);
		}

		if($request->invoice){
			$dataList = $dataList->where('invoice',$request->invoice);
		}
        
        $dataList = $dataList->paginate(50)->withQueryString();

        // dd($dataList);

        return view('log.index',compact('dataList'));
    }

    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}
