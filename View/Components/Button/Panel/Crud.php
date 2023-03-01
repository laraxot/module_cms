<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button\Panel;

use Illuminate\Contracts\View\View;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Crud.
 */
class Crud extends XotBaseComponent {
    public string $tpl;
    public PanelContract $panel;
    // public bool $has_pivot;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1') {
        $this->panel = $panel;
        // $this->has_pivot = isset($panel->getRow()->pivot);
        $this->tpl = $tpl;
    }

    /**
     * Undocumented function.
     */
    public function render(): View {
        /**
         * @phpstan-var view-string
         */
        $view = app(GetViewAction::class)->execute($this->tpl);
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }
}
