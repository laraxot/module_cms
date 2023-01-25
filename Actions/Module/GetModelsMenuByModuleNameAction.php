<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Module;

use Modules\Cms\Datas\NavbarMenuData;
use Modules\Cms\Services\PanelService;
use Spatie\LaravelData\DataCollection;
use Spatie\QueueableAction\QueueableAction;

class GetModelsMenuByModuleNameAction {
    use QueueableAction;

    public function __construct() {
    }

    /**
     * Undocumented function.
     *
     * @return DataCollection<NavbarMenuData>
     */
    public function execute(string $module_name): DataCollection {
        $models = app(GetModelsByModuleNameAction::class)->execute($module_name);
        $menu = collect($models)->map(
            function ($item, $key) {
                // $obj = new $item();
                $obj = app($item);
                $panel = PanelService::make()->get($obj);

                if ('media' === $key) {// media e' singolare ma anche plurale di medium
                    $panel->setName('medias');
                }

                $url = $panel->url('index');

                return [
                    'title' => $key,
                    'url' => $url,
                    'active' => false,
                ];
            }
        );

        return NavbarMenuData::collection($menu);
    }
}
