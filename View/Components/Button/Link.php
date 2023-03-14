<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Modules\Cms\Actions\GetStyleClassByViewAction;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Actions\GetViewThemeByViewAction;
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

        $view = app(GetViewAction::class)->execute($this->tpl);
        $this->view = app(GetViewThemeByViewAction::class)->execute($view);
        $this->attrs['class'] = app(GetStyleClassByViewAction::class)->execute($this->view);
        // dddx([$this->view, $this->attrs]);
        $this->attrs['data-toggle'] = 'tooltip';
        $this->attrs['title'] = $link->title;
        $this->attrs['href'] = $link->url;

        $this->attrs['class'] .= $link->active ? ' active' : '';

        if (Str::startsWith($link->icon, 'svg::')) {
            $name = Str::after($link->icon, 'svg::');
            $this->icon = '<img src="'.ThemeService::asset('ui::svg/'.$name.'.svg').'" style="height:20px"/>';
        }

        if (Str::contains($link->icon, '<i ')) {
            $this->icon = $link->icon;
        }

        if (null == $this->icon) {
            $this->icon = '<i class="'.$link->icon.'"></i>';
            // $this->icon = $link->icon;
        }

        if (null != $link->onclick) {
            $this->attrs['onclick'] = $link->onclick;
        }
        // dddx($link);
        // dddx($this->attrs);
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

        return view($view, $view_params);
    }

    public function shouldRender(): bool {
        return $this->link->render;
    }
}
