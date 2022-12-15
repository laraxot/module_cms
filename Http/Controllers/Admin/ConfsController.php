<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Modules\Cms\Models\Conf;
use Modules\Tenant\Services\TenantService;
use Modules\Xot\Services\PanelService;

/**
 * Class ConfController.
 */
class ConfsController extends Controller {
    /**
     * Undocumented function.
     *
     * @return mixed
     */
    public function index(Request $request) {
        // $rows = TenantService::getConfigNames();
        $panel = PanelService::make()->getRequestPanel();

        return $panel->out();
    }

    /**
     * Undocumented function.
     *
     * @return Renderable|string
     */
    public function edit(Request $request) {
        $data = $request->all();
        $route_params = getRouteParameters();
        [$containers,$items] = params2ContainerItem($route_params);
        $conf_id = last($items); // google
        $rows = app(Conf::class)->getRows();
        $row = collect($rows)->firstWhere('id', $conf_id);
        $conf_name = $row['name'];

        $name = TenantService::getName();
        $config_key = Str::replace('/', '.', $name.'/'.$conf_name);
        $filename = TenantService::filePath($conf_name.'.php');

        /*
        dddx([
            'conf_name' => $conf_name,
            'name' => $name,
            'config_key' => $config_key,
            'test1' => config($config_key),
            'filename' => $filename,
        ]);

        return 'preso';
        // */
        $view = 'theme::admin.standalone.manage.php-array';
        $view_params = [
            'view' => $view,
            'filename' => $filename,
        ];

        return view()->make($view, $view_params);
    }
}