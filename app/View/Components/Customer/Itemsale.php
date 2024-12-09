<?php

namespace App\View\Components\Customer;

use App\Models\StatSell;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Itemsale extends Component
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
        $dataList = StatSell::with('sender','group')->where('sender_id',$this->cid)->orderBy('bulan','desc')->orderBy('tahun','desc');

        if(Request('bulan')){
			$dataList = $dataList->where('bulan',Request('bulan'));
		}

		if(Request('tahun')){
			$dataList = $dataList->where('tahun',Request('tahun'));
		}

        if(Request('group')){
			$dataList = $dataList->where('group',Request('group'));
		}

		if(Request('type')){
			$dataList = $dataList->where('type',Request('type'));
		}

		$dataList = $dataList->paginate(100)->withQueryString();

        return view('components.customer.itemsale',compact('dataList'));
    }
}
