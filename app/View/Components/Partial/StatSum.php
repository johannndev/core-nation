<?php

namespace App\View\Components\Partial;

use App\Models\TransactionDetail;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Builder;

class StatSum extends Component
{
    /**
     * Create a new component instance.
     */
    public $bulan;
    public $tahun;
    public $tid;
    public $type;
    public $total;
    public $group;


    public function __construct($tid,$type,$tahun,$bulan, $group = null)
    {

;

        $data = TransactionDetail::where('transaction_type',$type)->whereMonth('date', $bulan)->whereYear('date', $tahun);

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

        $this->type = $type;
      

        // dd( $this->total );

        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.stat-sum');
    }
}
