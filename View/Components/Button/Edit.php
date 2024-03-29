<?php

declare(strict_types=1);

namespace Modules\Cms\View\Components\Button;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;
use Modules\Cms\Actions\GetViewAction;
use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\View\Components\XotBaseComponent;

/**
 * Class Edit.
 */
class Edit extends XotBaseComponent
{
    public PanelContract $panel;
    public string $method = 'edit';
    public array $attrs = [];
    public string $tpl;
    public string $type;

    /**
     * Undocumented function.
     */
    public function __construct(PanelContract $panel, string $tpl = 'v1', string $type = 'button', array $attrs = [])
    {
        $this->tpl = $tpl;
        $this->type = $type;
        $this->panel = $panel;
        $this->attrs = $attrs;

        $class_key = inAdmin() ? 'adm_theme::styles.edit.button.class' : 'pub_theme::styles.edit.button.class';
        $this->attrs['button']['class'] = config($class_key, 'btn btn-primary mb-2');

        $class_key = inAdmin() ? 'adm_theme::styles.edit.icon.class' : 'pub_theme::styles.edit.icon.class';
        $this->attrs['icon']['class'] = config($class_key, 'far fa-edit');

        // $this->attrs['class'] = [];

        // if (inAdmin()) {
        //     $this->attrs['button']['class'] = config('adm_theme::styles.edit.button.class', 'btn btn-primary mb-2');
        //     $this->attrs['icon']['class'] = config('adm_theme::styles.edit.icon.class', 'far fa-edit');
        // } else {
        //     $this->attrs['button']['class'] = config('pub_theme::styles.edit.button.class', 'btn btn-primary mb-2');
        //     $this->attrs['icon']['class'] = config('pub_theme::styles.edit.icon.class', 'far fa-edit');
        // }
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
