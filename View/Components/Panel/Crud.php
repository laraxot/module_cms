<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel;

use Illuminate\Contracts\Support\Renderable;
<<<<<<< HEAD
use Illuminate\View\Component;
=======
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Std.
 */
class Crud extends Component {
    public PanelContract $panel;
    public string $tpl;
<<<<<<< HEAD
=======
    public LengthAwarePaginator $rows;
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275

    /**
     * Create a new component instance.
     *
     * @return void
     */
<<<<<<< HEAD
    public function __construct(PanelContract $panel, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->panel = $panel;
=======
    public function __construct(PanelContract $panel, LengthAwarePaginator $rows, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->panel = $panel;
        $this->rows = $rows;
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable {
        /**
         * @phpstan-var view-string
         */
<<<<<<< HEAD
        $view = 'cms::components.panel.crud.'.$this->tpl;
=======
        // $view = 'cms::components.panel.crud.'.$this->tpl;
        $view = app(GetViewAction::class)->execute($this->tpl);
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275
        $fields = $this->panel->getFields('index');

        $view_params = [
            'view' => $view,
            'fields' => $fields,
<<<<<<< HEAD
            'rows' => $this->panel->rows()->paginate(20),
=======
            // 'rows' => $this->panel->rows()->paginate(20),
>>>>>>> 83789965fd9572aa1df56c480bdf14891b374275
        ];

        return view()->make($view, $view_params);
    }
}
