<?php

namespace App\Http\Controllers;

use App\Exports\SellItemExport;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Builder;

class ExportController extends Controller
{
    public function sellItem(Request $request)
	{

        $allWh = Customer::whereIn('type',[Customer::TYPE_WAREHOUSE,Customer::TYPE_VWAREHOUSE])->orderBy('name','asc')->get();

        $tanggalAwal = $request->from; // format: Y-m-d
        $tanggalAkhir = $request->to; // format: Y-m-d
        $whId = $request->whId;
        $type = request('type', Transaction::TYPE_SELL);
        $invoice = $request->invoice; 

        $typeList = Transaction::$types;

        $dataList = TransactionDetail::where('transaction_type',$type)->with('transaction','item','receiver','sender')->orderBy('date','desc');
       
        if ($tanggalAwal && $tanggalAkhir) {
            $dataList = $dataList->whereDate('date','>=',$tanggalAwal)->whereDate('date','<=',$tanggalAkhir);
        }

         if ($invoice) {
            $dataList = $dataList->whereHas('transaction', function (Builder $query) use($invoice) {
                $query->where('invoice', $invoice);
            });

        }

        if ($whId) {
            $dataList = $dataList->where(function ($subQ) use ($whId) {
                $subQ->where('sender_id', $whId)
                     ->orWhere('receiver_id', $whId);
            });
        }
        
        $dataList = $dataList->paginate(100)->withQueryString();

		// dd($dataList);

		return view('transactions.export.sellItem',compact('dataList','allWh','typeList'));
	}

    public function exportSellItem(Request $request) 
	{
        // dd($request);\

        $type = request('type', $request->type);

		return Excel::download(new SellItemExport($request->from,$request->to,$request->whId, $type, $request->invoice), 'sell_item.csv');
	}

}
