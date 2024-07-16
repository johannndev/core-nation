<?php

namespace App\View\Components\Partial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectItem extends Component
{
    /**
     * Create a new component instance.
     */
    public $ids;
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.select-item');
    }
}
