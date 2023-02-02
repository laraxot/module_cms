<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Show.
 */
class Show extends Component
{
    public PanelContract $panel;
    public string $method = 'show';

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel)
    {
        $this->panel = $panel;
    }

    /**
     * Undocumented function.
     */
    public function render(): ?View
    {
        /**
         * @phpstan-var view-string
         */
        $view = 'ui::components.button.show';
        $view_params = [
            'view' => $view,
        ];

        return view()->make($view, $view_params);
    }

    public function shouldRender(): bool
    {
        return Gate::allows($this->method, $this->panel);
    }
}
