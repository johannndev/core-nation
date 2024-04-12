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
    public $idItem;
    public $type;
    public $urlImage;
    public function __construct($idItem,$type)
    {
        $this->$idItem = $idItem;

        $folder = str_pad(substr($this->$idItem, -2), 2, '0', STR_PAD_LEFT);

        $this->urlImage = 'https://cdn.corenationactive.com/img/'.$type.'/'.$folder.'/'.$this->$idItem.'.jpg';
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.partial.image');
    }
}
