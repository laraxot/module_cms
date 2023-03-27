<?php

declare(strict_types=1);

namespace Modules\Cms\View\Composers;

use Modules\Cms\Actions\Module\GetModelsMenuByModuleNameAction;
use Modules\Cms\Datas\NavbarMenuData;
use Modules\Cms\Services\RouteService;
use Modules\LU\Services\ProfileService;
use Modules\UI\Models\Menu;
use Spatie\LaravelData\DataCollection;

class ThemeComposer {
    /**
     * ---.
     */
    public function getArea(): ?string {
        $params = getRouteParameters();
        if (isset($params['module'])) {
            return $params['module'];
        }

        return null;
    }

    public function getModelsMenuByModuleName(?string $module_name = null): DataCollection {
        if (null == $module_name) {
            $module_name = $this->getArea();
        }
        $res = app(GetModelsMenuByModuleNameAction::class)->execute($module_name);

        return $res;
    }

    public function getModuleMenuByModuleName(?string $module_name = null): DataCollection {
        $profile = ProfileService::make();

        $menu_name = $module_name;

        if (null == $module_name) {
            $module_name = $this->getArea();
            $menu_name = 'module_'.$module_name;
        }

        $menu = Menu::firstOrNew(
            ['name' => $menu_name]
        );

        // dddx($menu->items);

        $items = $menu->items->filter(function ($item) use ($profile) {
            $roles = array_map('trim', explode(',', $item->roles));
            $roles[] = 'superadmin';
            if ($profile->hasAnyRole($roles)) {
                return true;
            }

            return false;
        })->map(function ($item) {
            return [
                'title' => $item->label,
                'url' => $item->link,
                'active' => (bool) $item->active,
                'icon' => $item->icon,
            ];
        });

        return NavbarMenuData::collection($items);
    }

    public function getDashboardMenu(): DataCollection {
        $profile = ProfileService::make();
        $menu = $profile->areas()->map(function ($item) {
            return [
                'title' => $item->area_define_name,
                'url' => $item->url,
                'active' => (bool) $item->active,
            ];
        });
        // $menu = []; // se non è superadmin dovrebbe essere vuoto
        // if (! $profile->isSuperAdmin()) {
        //     $menu = [];
        // }

        return NavbarMenuData::collection($menu);
    }

    public function getRouteAct(): string {
        return RouteService::getAct();
    }
}
