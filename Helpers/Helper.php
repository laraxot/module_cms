<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Modules\Cms\Services\PanelService;

if (! function_exists('getModuleModelsMenu')) {
    function getModuleModelsMenu(string $module): Collection
    {
        $models = getModuleModels($module);
        $menu = collect($models)->map(
            function ($item, $key) {
                // $obj = new $item();
                $obj = app($item);
                $panel = PanelService::make()->get($obj);

                if ('media' === $key) {// media e' singolare ma anche plurale di medium
                    $panel->setName('medias');
                }

                $url = $panel->url('index');

                return (object) [
                    'title' => $key,
                    'url' => $url,
                    'active' => false,
                ];
            }
        );

        return $menu;
    }
}
