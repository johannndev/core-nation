<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Detail extends Component
{
    /**
     * Create a new component instance.
     */
    public $cid;
    public $nameType;
    public function __construct( $cid,$nameType)
    {
        $this->cid = $cid;
        $this->nameType =$nameType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = Customer::withTrashed()->findOrFail($this->cid);

        $hideProp = "show";

        if($data->type == Customer::TYPE_WAREHOUSE){
            $hideProp = "hidden";
        }

        if($data->type == Customer::TYPE_VWAREHOUSE){
            $hideProp = "hidden";
        }

       
        return view('components.customer.detail',compact('data','hideProp'));
    }
}
