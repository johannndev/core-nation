<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SellItemExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $from,$to,$whId;

    public function __construct($from,$to,$whId)
    {
        $this->from = $from;
        $this->to = $to;
        $this->whId = $whId;
       
    }

    public function view(): View
    {
        
        $dataList = TransactionDetail::where('transaction_type',Transaction::TYPE_SELL)->with('transaction','item','receiver','sender')->orderBy('date','desc');
        
        $tanggalAwal = $this->from;
        $tanggalAkhir =$this->to;
        $whId = $this->whId;
       
        if ($tanggalAwal && $tanggalAkhir) {
            $dataList = $dataList->whereDate('date','>=',$tanggalAwal)->whereDate('date','<=',$tanggalAkhir);
        }

        if ($whId) {
            $dataList = $dataList->where(function ($subQ) use ($whId) {
                $subQ->where('sender_id', $whId)
                     ->orWhere('receiver_id', $whId);
            });
        }
        
        $dataList = $dataList->get();


        return view('export.sell_item', [
            'dataList' => $dataList
        ]);
    }
}
