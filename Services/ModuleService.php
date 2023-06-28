<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Illuminate\Support\Collection;

/**
 * Class ModuleService.
 */
class ModuleService
{
    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function make(): self
    {
        return static::getInstance();
    }

    public function getModuleModelsMenu(string $module): Collection
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
