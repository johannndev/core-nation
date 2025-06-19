<?php

namespace App\View\Components\Partial;

use App\Models\Tag;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectTag extends Component
{
    /**
     * Create a new component instance.
     */
    public $dataProp;
    public $default;
    public function __construct($dataProp)
    {
        $this->dataProp = $dataProp;

        if(isset($this->dataProp['default'])){
            $this->default = Tag::where('id', $this->dataProp['default'])->first();
        
        }
        
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.select-tag');
    }
}
