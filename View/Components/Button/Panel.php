<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Panel.
 */
class Panel extends XotBaseComponent {
    public PanelContract $panel;
    public array $attrs = [];
    public string $tpl;
    public string $type;
    public string $icon;
    public string $view;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1', string $type = 'create') {
        $this->tpl = $tpl;
        $this->type = $type;
        $this->panel = $panel;

        $this->view = app(GetViewAction::class)->execute($type.'.'.$this->tpl);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);
        // dddx([$this->attrs, $this->view]);
        $this->attrs['href'] = $panel->url($type);
        $this->attrs['title'] = $type;
        $this->attrs['data-toggle'] = 'tooltip';
        $this->icon = trans($panel->getTradMod().'.'.$type);
    }

    public function render(): Renderable {
        /**
         * @phpstan-var view-string
         */
        $view = $this->view;
        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function shouldRender(): bool {
        return Gate::allows($this->type, $this->panel);
    }
}
