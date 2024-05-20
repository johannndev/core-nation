<?php

namespace App\View\Components\Customer;

use App\Models\Transaction as ModelsTransaction;
use App\Models\TransactionDetail;
use Closure;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Transaction extends Component
{
    /**
     * Create a new component instance.
     */
    public $cid;
    public function __construct( $cid)
    {
        $this->cid = $cid;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        // $dataList = TransactionDetail::with('transaction','transaction.receiver','transaction.sender')->orderBy('date','desc');

        $dataList = ModelsTransaction::with('receiver','sender')->whereAny(['sender_id','receiver_id'],$this->cid)->orderBy('date','desc');

		if(Request('from') && Request('to')){
            $dataList = $dataList->where('date','>=',Request('from'))->where('date','<=',Request('to'));
        }

        if(Request('type')){
            $dataList = $dataList->where('type',Request('type'));
        }
      
		

		// dd($dataList);

		$dataList = $dataList->paginate(20)->withQueryString();

        // dd($dataList);
        return view('components.customer.transaction',compact('dataList'));
    }
}
