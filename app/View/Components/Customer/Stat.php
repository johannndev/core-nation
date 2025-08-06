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

        
        $dataCashInCustomer = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_CASH_IN)
            ->where("sender_id",'=',$customer->id)
            ->where("receiver_type",'=',Customer::TYPE_CUSTOMER)
            ->sum('total');

        $dataCashInReselle = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_CASH_IN)
            ->where("sender_id",'=',$customer->id)
            ->where("receiver_type",'=',Customer::TYPE_RESELLER)
            ->sum('total');


        $dataCashOutCustomer = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_CASH_IN)
            ->where("receiver_id",'=',$customer->id)
            ->where("sender_type",'=',Customer::TYPE_CUSTOMER)
            ->sum('total');

        $dataCashOutReselle = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_CASH_IN)
            ->where("receiver_id",'=',$customer->id)
            ->where("sender_type",'=',Customer::TYPE_CUSTOMER)
            ->sum('total');

        $dataSell = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_SELL)
            ->where("sender_id",'=',$customer->id)
            ->sum('total');

        $dataReturn = DB::table('transactions')
            ->where('date','>=',$startDate)->where('date','<=',$endDate)
            ->where("type",'=',Transaction::TYPE_RETURN)
            ->where("receiver_id",'=',$customer->id)
            ->sum('total');

        $data = [
            'cash_in' => [
                'customer' => $dataCashInCustomer,
                'reseller' => $dataCashInReselle,
                'total' => $dataCashInCustomer + $dataCashInReselle
            ],
            'cash_out' => [
                'customer' => $dataCashOutCustomer,
                'reseller' => $dataCashOutReselle,
                'total' => $dataCashOutCustomer + $dataCashOutReselle
            ],
            'sell' => [
                'customer' =>'-',
                'reseller' => '-',
                'total' => $dataSell
            ],
            'return' => [
                'customer' =>'-',
                'reseller' => '-',
                'total' => $dataReturn
            ]

        ];

        dd($data);
            


        // dd($startDate, $endDate);

        $data = DB::table('transactions')
            ->selectRaw("
                -- Cash In
                SUM(CASE 
                    WHEN type = ? AND sender_id = ? THEN total ELSE 0 END) as cash_in_customer,
                SUM(CASE 
                    WHEN type = ? AND sender_id = ? THEN total ELSE 0 END) as cash_in_reseller,
                
                -- Cash Out
                SUM(CASE 
                    WHEN type = ? AND receiver_id = ? THEN total ELSE 0 END) as cash_out_customer,
                SUM(CASE 
                    WHEN type = ? AND receiver_id = ? THEN total ELSE 0 END) as cash_out_reseller,

                -- Sell Total
                SUM(CASE 
                    WHEN type = ? THEN total ELSE 0 END) as sell_total,

                -- Return Total
                SUM(CASE 
                    WHEN type = ? THEN total ELSE 0 END) as return_total
            ", [
                // cash_in_customer
                Transaction::TYPE_CASH_IN, $customer->id,
                // cash_in_reseller
                Transaction::TYPE_CASH_IN, $customer->id,
                // cash_out_customer
                Transaction::TYPE_CASH_OUT, $customer->id,
                // cash_out_reseller
                Transaction::TYPE_CASH_OUT, $customer->id,
                // sell_total
                Transaction::TYPE_SELL,
                // return_total
                Transaction::TYPE_RETURN,
            ])
            ->whereBetween('date', [$startDate, $endDate])
            ->first();

            // Bentuk array hasil
            $result = [
                'cash_in' => [
                    'customer' => (float) $data->cash_in_customer,
                    'reseller' => (float) $data->cash_in_reseller,
                    'total' => (float) $data->cash_in_customer + (float) $data->cash_in_reseller,
                ],
                'cash_out' => [
                    'customer' => (float) $data->cash_out_customer,
                    'reseller' => (float) $data->cash_out_reseller,
                    'total' => (float) $data->cash_out_customer + (float) $data->cash_out_reseller,
                ],
                'sell' => [
                    'customer' => '-',
                    'reseller' => '-',
                    'total' => (float) $data->sell_total,
                ],
                'return' => [
                    'customer' => '-',
                    'reseller' => '-',
                    'total' => (float) $data->return_total,
                ],
            ];

            dd($data);


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
			$dataSell = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_sell'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_SELL)->where("$transactionTable.receiver_id",'=',$customer->id)->first();
			$dataReturn = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_return'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_RETURN)->where("$transactionTable.sender_id",'=',$customer->id)->first();
        } else {
			$dataCashIn = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_in'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_IN)->where("$transactionTable.receiver_id",'=',$customer->id)->first();
			$dataCashOut = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_cash_out'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_CASH_OUT)->where("$transactionTable.sender_id",'=',$customer->id)->first();
			$dataSell = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_sell'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_SELL)->where("$transactionTable.sender_id",'=',$customer->id)->first();
			$dataReturn = DB::table($transactionTable)->select(array(
				DB::raw('SUM(total) as total_return'),
			))->where('date','>=',$startDate)->where('date','<=',$endDate)->where("$transactionTable.type",'=',Transaction::TYPE_RETURN)->where("$transactionTable.receiver_id",'=',$customer->id)->first();
        }

        // dd($dataCashIn->total_cash_in,$dataCashOut);

        return view('components.customer.stat',compact('dataSell','dataReturn','dataCashIn','dataCashOut'));
    }
}
