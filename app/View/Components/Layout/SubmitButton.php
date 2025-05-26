<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SubmitButton extends Component
{
    /**
     * Create a new component instance.
     */

    public string $label;
    public string $color;
    public ?string $icon;
    public string $type;


    public function __construct(string $label = 'Submit', string $color = 'blue', ?string $icon = null, string $type = 'default' )
    {
        $this->label = $label;
        $this->color = $color;
        $this->icon = $icon;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layout.submit-button');
    }
}
