<?php

namespace App\View\Components\Layouts;

use App\Helpers\AppSettingsHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    /**
     * Create a new component instance.
     */

    public $loadAppSettingCache;
    
    public function __construct()
    {
        $loadAppSettingCache = new AppSettingsHelper;

        $loadAppSettingCache->load();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.layouts.layout');
    }
}
