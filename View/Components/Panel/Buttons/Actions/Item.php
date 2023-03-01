<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Buttons\Actions;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Cms\Contracts\PanelContract;

class Item extends Component {
    public PanelContract $panel;
    public string $tpl;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->panel = $panel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable {
        /**
         * @phpstan-var view-string
         */
        $view = 'cms::components.panel.buttons.actions.item.'.$this->tpl;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }
}
