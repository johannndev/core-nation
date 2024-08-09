<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use App\Models\Transaction;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Stat extends Component
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
        $customer = Customer::withTrashed()->findOrFail($this->cid);


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

        $start = date('Y');

        if(Request('month')){
            $month = Request('month'); 
        }else{
            $month = 1;
        }

        if(Request('year')){
            $year = Request('year'); 
        }else{
            $year = $start;
        }

        $startDate = Carbon::createFromDate($year,$month,'1')->firstOfMonth()->toDateString();
        $endDate =Carbon::createFromDate($year,$month,'1')->endOfMonth()->toDateString();

        // dd($startDate, $endDate);

        //2. get sales data
		$transactionTable = Transaction::table();
		//2.1 get cash in and cash out
		if($customer->type == CUSTOMER::TYPE_CUSTOMER || $customer->type == CUSTOMER::TYPE_RESELLER) {
			$dataCashIn = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_in'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_IN)->where("$transactionTable.sender_id",'=',$customer->id)->first();
			$dataCashOut = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_out'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_OUT)->where("$transactionTable.receiver_id",'=',$customer->id)->first();
		} else {
			$dataCashIn = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_in'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_IN)->where("$transactionTable.receiver_id",'=',$customer->id)->first();
			$dataCashOut = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_out'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_OUT)->where("$transactionTable.sender_id",'=',$customer->id)->first();
		}

        // dd($dataCashIn->total_cash_in,$dataCashOut);

        return view('components.customer.stat',compact('dataCashIn','dataCashOut'));
    }
}
