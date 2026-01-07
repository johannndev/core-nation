<?php

namespace App\Exports;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class DestyTransactionExport implements FromView
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
       
    }

    public function view(): View
    {
        $dataList = TransactionDetail::where('transaction_id',$this->id)->with('transaction','item','receiver','sender','destyReceiver', 'destySender')->orderBy('date','desc')->orderBy('transaction_id','desc');
        

        
        $dataList = $dataList->get();


        return view('export.desty_transaction', [
            'dataList' => $dataList
        ]);
    }
}
