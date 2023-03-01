<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Panel\Actions;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Buttons extends Component {
    public string $tpl;
    public Collection $acts;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Collection $acts, string $tpl = 'v1') {
        $this->tpl = $tpl;
        $this->acts = $acts;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): Renderable {
        /**
         * @phpstan-var view-string
         */
        $view = 'cms::components.panel.actions.buttons.'.$this->tpl;

        $view_params = [
            'view' => $view,
        ];

        return view($view, $view_params);
    }

   // public static function resolve(array $acts, string $tpl = 'v1') {
        // dddx(['a' => $a]);
   // }
}
