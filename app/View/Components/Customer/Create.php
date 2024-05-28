<?php

namespace App\View\Components\Customer;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Create extends Component
{
    /**
     * Create a new component instance.
     */
    public $type;
    public $action;
    public function __construct($type, $action)
    {
        $this->type = $type;
        $this->action =$action;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $hideProp = "show";

        $hidePropInitial = "show";

        if($this->type == Customer::TYPE_VWAREHOUSE ||$this->type == Customer::TYPE_VACCOUNT){
            $hideProp = "hidden";
        }

        if($this->type == Customer::TYPE_WAREHOUSE || $this->type == Customer::TYPE_VWAREHOUSE || $this->type == Customer::TYPE_VACCOUNT){
            $hidePropInitial = "hidden";
        }

        // dd($this->type);

        return view('components.customer.create',compact('hideProp','hidePropInitial'));
    }
}
