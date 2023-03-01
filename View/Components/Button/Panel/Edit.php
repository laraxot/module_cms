<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button\Panel;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Actions\GetConfigKeyByViewAction;
use Modules\Xot\View\Components\XotBaseComponent;
use Modules\Cms\Actions\GetStyleClassByViewAction;

/**
 * Class Edit.
 */
class Edit extends XotBaseComponent {
    public PanelContract $panel;
    public string $method = 'edit';
    public array $attrs = [];
    public string $tpl;
    public string $type;
    public string $view;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1', string $type = 'button') {
        $this->tpl = $tpl;
        $this->type = $type;
        $this->panel = $panel;
        $this->view = app(GetViewAction::class)->execute($this->tpl);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);
        $this->attrs['href']= $panel->url($this->method);
        
        $icon= app(GetConfigKeyByViewAction::class)->execute($this->view,'icon');
    }

    public function render(): View {
        /**
         * @phpstan-var view-string
         */
        $view=$this->view;

        $view_params = [
            'view' => $view,
        ];
        // if (! Gate::allows($this->method, $this->panel)) {
        //    return null;
        // }

        return view($view, $view_params);
    }

    public function shouldRender(): bool {
        return Gate::allows($this->method, $this->panel);
    }
}
