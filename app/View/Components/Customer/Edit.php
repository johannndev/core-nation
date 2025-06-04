<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Edit extends Component
{
    /**
     * Create a new component instance.
     */
    public $cid;
    public $action;
   
    public function __construct($cid, $action)
    {
        $this->cid = $cid;
        $this->action =$action;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = Customer::findOrFail($this->cid);

        $hideProp = "show";
        $onlineProp = "hidden";

        if($data->type == Customer::TYPE_VWAREHOUSE || $data->type == Customer::TYPE_VACCOUNT){
            $hideProp = "hidden";
        }

        if($data->type == Customer::TYPE_WAREHOUSE || $data->type == Customer::TYPE_CUSTOMER){
            $onlineProp = "show";
        }

        return view('components.customer.edit',compact('data','hideProp','onlineProp'));
    }
}
