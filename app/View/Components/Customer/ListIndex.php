<?php

namespace App\View\Components\Customer;

use App\Helpers\DateHelper;
use App\Helpers\KeysHelper;
use App\Models\Customer;
use App\Models\Depreciation;
use App\Models\Item;
use App\Models\WarehouseItem;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        
        $hidePropBalance = "show";

        if($this->type == Customer::TYPE_VWAREHOUSE || $this->type == Customer::TYPE_WAREHOUSE){
            $hidePropBalance = "hidden";
        }

        $dataList = Customer::with('stat','locations')->where('type',$this->type);

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

     

        // dd($dataList);
        
        return view('components.customer.list-index',compact('dataList','hidePropBalance'));
    }
}
