<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

class SortAction extends XotBasePanelAction
{
    public bool $onContainer = true; // onlyContainer

    public string $icon = '<i class="fas fa-sort"></i>';

    public function handle()
    {
        /**
         * @phpstan-var view-string
         */
        $view = 'cms::admin.index.acts.sort';
        $view_params = [
            'view' => $view,
        ];

        return view()->make($view, $view_params);
    }
}
