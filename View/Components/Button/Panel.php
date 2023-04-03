<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Modules\Cms\Actions\GetConfigKeyByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Panel.
 */
class Panel extends XotBaseComponent
{
    public PanelContract $panel;
    public array $attrs = [];
    public string $tpl;
    public string $type;
    public string $icon;
    public string $view;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1', string $type = 'create')
    {
        $this->tpl = $tpl;
        $this->type = $type;
        $this->panel = $panel;

        // $this->view = app(GetViewAction::class)->execute($type.'.'.$this->tpl);
        $this->view = app(GetViewAction::class)->execute($this->tpl);
        $this->attrs['class'] = app(GetConfigKeyByViewAction::class)->execute($this->view, $type.'.class');
        // dddx([$this->attrs, $this->view]);
        $this->attrs['href'] = $panel->url($type);
        $this->attrs['title'] = $type;
        $this->attrs['data-toggle'] = 'tooltip';
        // $this->icon = trans($panel->getTradMod().'.'.$type);
        $this->icon = app(GetConfigKeyByViewAction::class)->execute($this->view, $type.'.icon');

        if ('delete' == $type) {
            // tacconamento di emergenza!
            $this->view = 'ui::components.button.delete.v2';
        }
    }

    public function render(): Renderable
    {
        /**
         * @phpstan-var view-string
         */
        $view = $this->view;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

    public function shouldRender(): bool
    {
        if ('detach' == $this->type) {
            if (! isset($this->panel->getRow()->pivot)) {
                return false;
            }
        }

        return Gate::allows($this->type, $this->panel);
    }
}
