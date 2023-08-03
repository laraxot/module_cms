<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Http\Requests\XotRequest;
use Modules\Cms\Services\PanelService;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\PolicyService;

// ---- services ---

/**
 * Undocumented class.
 *
 * @method Renderable home(Request $request)
 * @method Renderable show(Request $request)
 */
class ContainersController extends BaseController
{
    public PanelContract $panel;

    /**
     * Undocumented function.
     *
     * @return mixed|null
     */
    public function index(Request $request)
    {
        $route_params = getRouteParameters(); // "module" => "lu"
        [$containers,$items] = params2ContainerItem();
        // dddx(['contianers' => $containers, 'items' => $items]);
        if (0 === \count($containers)) {
            $act = isset($route_params['module']) ? 'home' : 'dashboard';

            $res = $this->{$act}($request);

            return $res;
        }

        if (\count($containers) === \count($items)) {
            return $this->show($request);
        }

        $res = $this->__call('index', $route_params);

        return $res;
    }

    /**
     * Undocumented function.
     *
     * @param string $method
     * @param array  $args
     */
    public function __call($method, $args)
    {
        // dddx([$method, $args]);
        $route_current = \Route::current();
        if (null !== $route_current) {
            /**
             * @var array
             */
            $action = $route_current->getAction();
            $action['controller'] = __CLASS__.'@'.$method;
            $action = $route_current->setAction($action);
        }
        $panel = PanelService::make()->getRequestPanel();

        if (null === $panel) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }
        $this->panel = $panel;
        if ('' !== request()->input('_act', '')) {
            return $this->__callPanelAct($method, $args);
        }

        return $this->callRouteAct($method, $args);
    }

    public function callRouteAct(string $method, array $args)
    {
        $panel = $this->panel;

        $authorized = Gate::allows($method, $panel);

        if (! $authorized) {
            return $this->notAuthorized($method, $panel);
        }
        $request = XotRequest::capture();
        $controller = $this->getController();
        $panel = app($controller)->$method($request, $panel);

        return $panel;
    }

    public function __callPanelAct(string $method, array $args)
    {
        $request = request();
        /**
         * @var string
         */
        $act = $request->_act;
        $method_act = Str::camel($act);

        $panel = $this->panel;

        $authorized = Gate::allows($method_act, $panel);
        if (! $authorized) {
            return $this->notAuthorized($method_act, $panel);
        }

        return $panel->callAction($act);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function notAuthorized(string $method, PanelContract $panel)
    {
        $lang = app()->getLocale();

        if (! Auth::check()) {
            $referer = \Request::path();

            return redirect()->route('login', ['lang' => $lang, 'referer' => $referer])
                ->withErrors(['active' => 'login before']);
        }
        $policy_class = PolicyService::get($panel)->createIfNotExists()->getClass();
        $msg = 'Auth Id ['.Auth::id().'] not can ['.$method.'] on ['.$policy_class.']';
        FileService::viewCopy('ui::errors.403', 'pub_theme::errors.403');

        return response()->view('pub_theme::errors.403', ['msg' => $msg], 403);
    }

    /**
     * Undocumented function.
     */
    public function getController(): string
    {
        list($containers, $items) = params2ContainerItem();
        $mod_name = $this->panel->getModuleName(); // forse da mettere container0

        $tmp = collect($containers)->map(
            function ($item) {
                return Str::studly($item);
            }
        )->implode('\\');
        if ('' === $tmp) {
            $tmp = 'Module';
        }
        $controller = '\Modules\\'.$mod_name.'\Http\Controllers\Admin\\'.$tmp.'Controller';

        if (class_exists($controller)) {
            return $controller;
        }
        if ('Module' === $tmp) {
            // return '\Modules\Cms\Http\Controllers\Admin\ModuleController';
            return '\Modules\Cms\Http\Controllers\Admin\ModuleController';
        }

        // return '\Modules\Cms\Http\Controllers\Admin\XotPanelController';
        return '\Modules\Cms\Http\Controllers\Admin\XotPanelController';
    }
}
