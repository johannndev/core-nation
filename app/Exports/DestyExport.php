<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;

class DestyExport implements FromView
{
    public $from,$to,$whId,$type,$invoice,$submit;

    public function __construct($from,$to,$whId,$type,$invoice,$submit)
    {
        $this->from = $from;
        $this->to = $to;
        $this->whId = $whId;
        $this->type = $type;
        $this->invoice = $invoice;
        $this->submit = $submit;
       
    }

    public function view(): View
    {
        $dataList = TransactionDetail::where('transaction_type',$this->type)->with('transaction','item','receiver','sender','destyReceiver', 'destySender')->orderBy('date','desc')->orderBy('transaction_id','desc');
        
        $tanggalAwal = $this->from;
        $tanggalAkhir =$this->to;
        $whId = $this->whId;
        $submit = $this->submit;
        

        if ($submit !== 'all' && $submit !== null) {
            $dataList = $dataList->whereHas('transaction', function (Builder $query) use ($submit) {
                if ($submit === 'aria') {
                    $query->where('submit_type', 1);
                } elseif ($submit === 'desty') {
                    $query->where('submit_type', 4);
                }
            });
        }
       
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


        return view('export.desty', [
            'dataList' => $dataList,
            'type' => $this->type
        ]);
    }

    
}
