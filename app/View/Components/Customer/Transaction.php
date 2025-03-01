<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use App\Models\Transaction as ModelsTransaction;
use App\Models\TransactionDetail;
use Closure;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
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

        $customer = Customer::with('locations')->withTrashed()->findOrFail($this->cid);

        $custLokal = $customer->locations->pluck('name','id')->toArray();
        $userLokal = Auth::user()->location_id;

       
        if($userLokal > 0){
            if($customer->locations){
                if(array_key_exists($userLokal,$custLokal)){
                    
                }else{
                    abort(404);
                }
            }
        }

        // dd($customer);

        $dataList = ModelsTransaction::with('receiver','sender','receiver.stat','sender.stat')->whereAny(['sender_id','receiver_id'],$this->cid)->orderBy('date','desc')->orderBy('id','desc');

		if(Request('from') && Request('to')){
            $dataList = $dataList->where('date','>=',Request('from'))->where('date','<=',Request('to'));
        }

        if(Request('type')){
            $dataList = $dataList->where('type',Request('type'));
        }

        if(Auth::user()->location_id > 0){

			$customers = Customer::whereHas('locations', function ($query) {
				$query->where('location_id', Auth::user()->location_id);
			})->pluck('id');

			$dataList = $dataList->whereIn('sender_id', $customers)->whereIn('receiver_id', $customers);

		
			
		
			

		}

        if(Request('order_date')){
			$dataList = $dataList->orderBy(Request('order_date'),'desc');
		}else{
			$dataList = $dataList->orderBy('date','desc');
		}


      
		

		// dd($dataList);

		$dataList = $dataList->paginate(50)->withQueryString();

        // dd($dataList);
        return view('components.customer.transaction',compact('dataList'));
    }
}
