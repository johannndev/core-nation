<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;

class SellItemExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $from,$to,$whId,$type,$invoice;

    public function __construct($from,$to,$whId,$type,$invoice)
    {
        $this->from = $from;
        $this->to = $to;
        $this->whId = $whId;
        $this->type = $type;
        $this->invoice = $invoice;
       
    }

    public function view(): View
    {
        $dataList = TransactionDetail::where('transaction_type',$this->type)->with('transaction','item','receiver','sender')->orderBy('date','desc')->orderBy('transaction_id','desc');
        
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

        $invoice =  $this->invoice;

        if ($invoice) {
            $dataList = $dataList->whereHas('transaction', function (Builder $query) use($invoice) {
                $query->where('invoice', $invoice);
            });

        }
        
        $dataList = $dataList->get();


        return view('export.sell_item', [
            'dataList' => $dataList
        ]);
    }
}
