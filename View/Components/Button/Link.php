<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Datas\LinkData;
use Modules\UI\Services\ThemeService;

/**
 * Class Link.
 */
class Link extends Component {
    public LinkData $link;
    // public string $method = 'show';
    public array $attrs = [];
    public string $tpl;
    // public string $policy_name;
    public string $view;
    public ?string $icon = null;

    /**
     * Undocumented function.
     */
    public function __construct(LinkData $link, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->link = $link;
        // $this->policy_name = $action->getPolicyName();

        $this->view = app(GetViewAction::class)->execute($this->tpl);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);

        $this->attrs['data-toggle'] = 'tooltip';
        $this->attrs['title'] = $link->title;

        if (Str::startsWith($link->icon, 'svg::')) {
            $name = Str::after($link->icon, 'svg::');
            $this->icon = '<img src="'.ThemeService::asset('ui::svg/'.$name.'.svg').'" style="height:20px"/>';
        }

        if (null == $this->icon) {
            $this->icon = '<i class="'.$link->icon.'"></i>';
        }
    }

    /**
     * Undocumented function.
     */
    public function render(): ?View {
        /**
         * @phpstan-var view-string
         */
        $view = $this->view;

        $view_params = [
            'view' => $view,
        ];

        return view()->make($view, $view_params);
    }

    // public function shouldRender(): bool {
    //     return Gate::allows($this->policy_name, $this->action->panel);
    // }
}
