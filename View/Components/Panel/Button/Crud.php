<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Button;

use Illuminate\Contracts\View\View;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Crud.
 */
class Crud extends XotBaseComponent
{
    public PanelContract $panel;
    public string $tpl;
    // public bool $has_pivot;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1')
    {
        $this->panel = $panel;
        $this->tpl = $tpl;
        // $this->has_pivot = isset($panel->getRow()->pivot);
    }

    /**
     * Undocumented function.
     */
    public function render(): View
    {
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
