<?php

namespace App\View\Components\Partial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ItemForm extends Component
{
    /**
     * Create a new component instance.
     */
    public $lineId;

    public function __construct($lineId)
    {
        $this->lineId = $lineId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.item-form');
    }
}
