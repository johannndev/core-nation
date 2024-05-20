<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListIndex extends Component
{
    /**
     * Create a new component instance.
     */
    public $type;
    public $nameType;
  
    public function __construct($type, $nameType)
    {
        $this->type = $type;
        $this->nameType = $nameType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        
        $dataList = Customer::with('stat')->where('type',$this->type);

        if(Request('name')) {
			$name = str_replace(' ', '%', Request('name'));
			$dataList = $dataList->where('name','LIKE',"%$name%");
		}
		if($id = Request('id')) {
			$dataList = $dataList->where('memberId','=', $id);
		}

		if($deleted = Request('deleted'))
			$dataList = $dataList->onlyTrashed();
        
        $dataList = $dataList->orderBy('name','asc')->paginate(50)->withQueryString();
        
        return view('components.customer.list-index',compact('dataList'));
    }
}
