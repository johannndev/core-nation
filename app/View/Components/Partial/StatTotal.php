<?php

namespace App\View\Components\Partial;

use App\Models\TransactionDetail;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Builder;

class StatTotal extends Component
{
    /**
     * Create a new component instance.
     */

    
    public $type;
    public $tid;
    public $total;
    public $group;


    public function __construct($tid,$type,$group = null)
    {

        $this->type = $type;
        
        if(Request("from")){
			$from = Request("from");
		}else{
			$from = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
		}
	

		if(Request("to")){
			$to = Request("to");
		}else{
			$to = Carbon::now()->endOfMonth()->toDateString();
		}

        $data = TransactionDetail::where('transaction_type',$type)->whereDate('date','>=',$from)->whereDate('date','<=',$to);

        if($group){

            $data = $data->whereHas('item', function (Builder $query) use($tid) {
                $query->where('group_id',$tid);
            });

        }else{
            $data = $data->where('item_id',$tid);
        }

        if(Request('addr')){
			$data = $data->whereAny(['sender_id','receiver_id'],Request('addr'));
        }

        $data = $data->sum('quantity');

        $this->total = $data;
		
        // dd( $this->total );

        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.stat-total');
    }
}
