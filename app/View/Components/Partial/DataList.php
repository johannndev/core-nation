<?php

namespace App\View\Components\Partial;

use App\Models\Customer;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class DataList extends Component
{
    /**
     * Create a new component instance.
     */

    public $dataProp;
    public $defaultWH;
     
    public function __construct($dataProp)
    {
        $this->dataProp = $dataProp;

        // dd($this->dataProp['defaultRecaiver']);


        if(isset($this->dataProp['default'])){
            $this->defaultWH = Customer::where('id', $this->dataProp['default'])->first();
        }

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.data-list',compact('user'));
    }
}
