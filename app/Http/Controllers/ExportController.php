<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function sellItem(Request $request)
	{

        $allWh = Customer::whereIn('type',[Customer::TYPE_WAREHOUSE,Customer::TYPE_VWAREHOUSE])->orderBy('name','asc')->get();

        $tanggalAwal = $request->from; // format: Y-m-d
        $tanggalAkhir = $request->to; // format: Y-m-d
        $whId = $request->whId;

        $dataList = TransactionDetail::where('transaction_type',Transaction::TYPE_SELL)->with('transaction','item','receiver','sender')->orderBy('date','desc');
       
        if ($tanggalAwal && $tanggalAkhir) {
            $dataList = $dataList->whereDate('date','>=',$tanggalAwal)->whereDate('date','<=',$tanggalAkhir);
        }

        if ($whId) {
            $dataList = $dataList->where(function ($subQ) use ($whId) {
                $subQ->where('sender_id', $whId)
                     ->orWhere('receiver_id', $whId);
            });
        }
        
        $dataList = $dataList->paginate(20)->withQueryString();

		// dd($dataList);

		return view('transactions.export.sellItem',compact('dataList','allWh'));
	}
}
