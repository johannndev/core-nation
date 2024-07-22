<?php

namespace App\Exports;

use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AddrBookTransactionExport implements FromView
{
    public $from,$to,$type,$cid;

    public function __construct($from,$to,$type,$cid)
    {
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
        $this->cid = $cid;
    }

    public function view(): View
    {
        $dataList = Transaction::with('receiver','sender','receiver.stat','sender.stat')->whereAny(['sender_id','receiver_id'],$this->cid)->orderBy('date','desc');

		if($this->from && $this->to){
            $dataList = $dataList->where('date','>=',$this->from)->where('date','<=',$this->to);
        }

        if($this->type){
            $dataList = $dataList->where('type',$this->type);
        }
      
		

		// dd($dataList);

		$dataList = $dataList->paginate(50)->withQueryString();


        return view('export.ab_transaction', [
            'dataList' => $dataList
        ]);
    }
}
