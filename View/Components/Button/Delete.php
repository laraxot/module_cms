<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;

/**
 * Class Delete.
 */
class Delete extends Component
{
    public PanelContract $panel;
    public string $method = 'delete';
    public array $attrs = [];
    public string $tpl;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1')
    {
        $this->tpl = $tpl;
        $this->panel = $panel;

        $this->attrs['class'] = [];

        if (inAdmin()) {
            $this->attrs['button']['class'] = config('adm_theme::styles.delete.button.class', 'btn btn-primary mb-2 btn-danger btn-confirm-delete');
            $this->attrs['icon']['class'] = config('adm_theme::styles.delete.icon.class', 'far fa-trash-alt');
        } else {
            $this->attrs['button']['class'] = config('pub_theme::styles.delete.button.class', 'btn btn-primary mb-2 btn-danger btn-confirm-delete');
            $this->attrs['icon']['class'] = config('pub_theme::styles.delete.icon.class', 'far fa-trash-alt');
        }
    }

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

    public function shouldRender(): bool
    {
        return Gate::allows($this->method, $this->panel);
    }
}
