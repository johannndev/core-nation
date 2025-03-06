<?php

namespace App\Http\Controllers;

use App\Models\Logjubelio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

    public function viewJson($id){

        $response = Http::withHeaders([ 
                'Content-Type'=> 'application/json', 
                'authorization'=> Cache::get('jubelio_data')['token'], 
            ]) 
            ->get('https://api2.jubelio.com/sales/orders/'.$id); 

            $data = json_decode($response->body(), true);

        dd($data);
    }

    public function detail($id){
        $data = Logjubelio::find($id);

        dd($data->toArray());
    }
    
}
