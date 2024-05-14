<?php

namespace App\View\Components\Contributor;

use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class Top extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $date = Carbon::now();
		$from = $date->startOfMonth()->toDateString();
		$to = $date->endOfMonth()->toDateString();

        $td = TransactionDetail::table();
		$c = 'customers';
		$i = Item::table();

        $data = DB::table($td)->select(
           "$td.item_id", "$i.code", "$i.name", "$i.size", "$i.brand", "$i.genre", "$i.type",
			DB::raw("SUM( $td.quantity ) as total_quantity"),
			DB::raw("SUM( ($td.total * (100 - $td.transaction_disc)) / 100 ) as total_value"),
        );
		
        if(Request('from') && Request('to')){
            $data =  $data->where('date','>=',Request('from'))->where('date','<=',Request('to'));
        }else{
            $data =  $data->where('date','>=',$from)->where('date','<=',$to);
        }

		$data = $data->where("$td.transaction_type", '=', Transaction::TYPE_SELL)
		->join($i,"$i.id",'=',"$td.item_id");
		//filters
		if(Request('filterBrand'))
			$data = $data->where("$i.brand",'=',Request('filterBrand'));
		if(Request('warehouseId')) {
			$data = $data->join($c, "$c.id",'=', "$td.sender_id")->where("$td.sender_id",'=',Request('warehouseId'));
		} elseif (Request('customerId')) {
			$data = $data->join($c, "$c.id",'=', "$td.receiver_id")->where("$td.receiver_id",'=',Request('customerId'));
		}
		$top50 = $data->groupBy("$td.item_id")->orderBy('total_value','desc')->take(50)->get();


        // dd($top50);

        return view('components.contributor.top',compact('top50'));
    }
}
