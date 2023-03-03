<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelActionContract;

/**
 * Class Action.
 */
class Action extends Component {
    public PanelActionContract $action;
    // public string $method = 'show';
    public array $attrs = [];
    public string $tpl;
    public string $policy_name;
    public string $view;

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function __construct(PanelActionContract $action, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->action = $action;
        $this->policy_name = $action->getPolicyName();

        $this->view = app(GetViewAction::class)->execute($this->tpl);
        // dddx($this->view);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);

        $this->attrs['href'] = $this->action->url();
    }

    /**
     * Undocumented function.
     */
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
        return Gate::allows($this->policy_name, $this->action->panel);
    }
}
