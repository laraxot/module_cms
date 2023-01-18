<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Std.
 */
class Crud extends Component
{
    public PanelContract $panel;
    public string $tpl;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1')
    {
        $this->tpl = $tpl;
        $this->panel = $panel;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = 'cms::components.panel.crud.'.$this->tpl;
        $fields = $this->panel->getFields('index');
        // dddx(rowsToSql($this->panel->rows));
        $view_params = [
            'view' => $view,
            'fields' => $fields,
            'rows' => $this->panel->rows->paginate(20),
        ];

        return view()->make($view, $view_params);
    }
}
