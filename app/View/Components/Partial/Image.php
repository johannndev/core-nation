<?php

namespace App\View\Components\Partial;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
    /**
     * Create a new component instance.
     */
    public $url;
    public $type;
    
    public function __construct($url, $type)
    {
        $this->url = $url;
        $this->type = $type;
        
    
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.image');
    }
}
