<?php

namespace App\Http\Controllers;

use App\Models\Jubelioreturn;
use App\Models\Jubeliosync;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

        $returnData = Jubelioreturn::find($id);

		$data = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$returnData->transaction_id	)->first();

        $rid = $id;

		return view('jubelio.return.detail',compact('data','rid'));
	}

    public function jubelioReturnPost($id, Request $request){

        $returnData = Jubelioreturn::find($id);

		$transactionData = Transaction::with(['receiver','sender','user','transactionDetail','transactionDetail.item','transactionDetail.item.group'])->where('id',$returnData->transaction_id	)->first();

		
		
		$item = TransactionDetail::with('item')->where('transaction_id',$transactionData->id)->whereIn('item_id', $request->return_item)->get();

		dd($returnData,$request->return_item,$item);
		


		$moreItem = [];
		

		foreach ($item as $data) {
			$moreItem[] = [
				'itemId'   => $data->item_id,
				'code'     => $data->item->code,
				'name'     => $data->item->name,
				'quantity' => $data->quantity,
				'price'    => $data->price,
				'discount' => 0,
				'subtotal' => $data->quantity*$data->price,
			];
		}

		$dataJubelio = [
			"date" => Carbon::now()->toDateString(),
			"due" => null,
			"warehouse" => $transactionData->sender_id,
			"customer" => $transactionData->receiver_id,
			"invoice" => $transactionData->invoice,
			"note" => " ",
			"account" => "7204",
			"amount" => null,
			"paid" => null,
			"addMoreInputFields" => $moreItem,
			"disc" => "0",
			"adjustment" =>  $request->adjustment,
			"ongkir" => "0"
		];

		$dataCollect =  (object) $dataJubelio;

		

		$transactionData->jubelio_return = 2;

		$transactionData->save();
		
		$this->createTransaction(Transaction::TYPE_RETURN, $dataCollect);

        $returnData->status = 1;
        $returnData->confirmed_by = Auth::user()->id;

        $returnData->save();

		return redirect()->route('transaction.index')->with('success', 'Return created.');

	}

    public function createSolved($id){

        $logjubelio = Jubelioreturn::findOrFail($id);

        $response = Http::withHeaders([ 
            'Content-Type'=> 'application/json', 
            'authorization'=> Cache::get('jubelio_data')['token'], 
        ]) 
        ->get('https://api2.jubelio.com/sales/orders/'.$logjubelio->order_id); 

        $data = json_decode($response->body(), true);

        $adjust = $data['sub_total'] - $data['grand_total'];

        $jubelioSync = Jubeliosync::where('jubelio_store_id', $data['store_id'])->where('jubelio_location_id',$data['location_id'])->first();
        
        $sid = $id;

        return view('jubelio.return.solved',compact('jubelioSync','data','adjust','sid'));
    }

    public function storeSolved($id){
        $logjubelio = Jubelioreturn::findOrFail($id);
        $logjubelio ->status = 1;
        $logjubelio->confirmed_by = Auth::user()->id;
    
        $logjubelio->save();

        return redirect()->route('transaction.index')->with('success', 'Return finished.');
    }

}
