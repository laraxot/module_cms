<?php

namespace Modules\Cms\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $view='pub_theme';
        if(inAdmin()) {
            $view='adm_theme';
        }
        $view=$view.'::components.app-layout';
        $view_params=[];
        return view($view, $view_params);
    }
}
