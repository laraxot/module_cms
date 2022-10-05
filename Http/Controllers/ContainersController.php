<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Xot\Contracts\PanelContract;
use Modules\Xot\Http\Requests\XotRequest;
use Modules\Xot\Services\FileService;
use Modules\Xot\Services\PanelService;
use Modules\Xot\Services\PolicyService;

/**
 * Undocumented class.
 *
 * @method Renderable home(Request $request)
 * @method Renderable show(Request $request)
 */
class ContainersController extends Controller {
    protected PanelContract $panel;

    /**
     * Undocumented function.
     *
     * @return mixed
     */
    public function index(Request $request) {
        $route_params = getRouteParameters();
        [$containers,$items] = params2ContainerItem();
        if (0 === \count($containers)) {
            return $this->home($request);
        }
        if (\count($containers) === \count($items)) {
            return $this->show($request);
        }

        return $this->__call('index', $route_params);
    }

    public function __call($method, $args) {
        // dddx(['method' => $method, 'args' => $args]);
        $route_current = Route::current();

        if (null != $route_current) {
            /**
             * @var array
             */
            $action = $route_current->getAction();
            $action['controller'] = __CLASS__.'@'.$method;
            $action = $route_current->setAction($action);
        }
        $panel = PanelService::make()->getRequestPanel();
        if (null == $panel) {
            throw new Exception('['.__LINE__.']['.__FILE__.']');
        }
        $this->panel = $panel;

        if ('' !== request()->input('_act', '')) {
            return $this->__callPanelAct($method, $args);
        }

        return $this->__callRouteAct($method, $args);
    }

    public function getController(): string {
        /*
        if (null == $this->panel) {
            return '\Modules\Xot\Http\Controllers\XotPanelController';
        }
        */
        list($containers, $items) = params2ContainerItem();

        $mod_name = $this->panel->getModuleName();

        $tmp = collect($containers)->map(
            function ($item) {
                return Str::studly($item);
            }
        )->implode('\\');
        $controller = '\Modules\\'.$mod_name.'\Http\Controllers\\'.$tmp.'Controller';
        if (class_exists($controller) && '' !== $tmp) {
            return $controller;
        }

        return '\Modules\Xot\Http\Controllers\XotPanelController';
    }

    /**
     * @return mixed
     */
    public function __callRouteAct(string $method, array $args) {
        $panel = $this->panel;
        $authorized = Gate::allows($method, $panel);

        if (! $authorized) {
            // dddx($method, $panel);

            return $this->notAuthorized($method, $panel);
        }
        $request = XotRequest::capture();

        $controller = $this->getController();
        // dddx([$controller, $method]);
        // Modules\Xot\Http\Controllers\XotPanelController
        // home

        $panel = app($controller)->$method($request, $panel);

        return $panel;
    }

    /**
     * @return mixed
     */
    public function __callPanelAct(string $method, array $args) {
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
    public function notAuthorized(string $method, PanelContract $panel) {
        $lang = app()->getLocale();
        /*
        if (! \Auth::check()) {
            $referer = \Request::path();

            return redirect()->route('login', ['lang' => $lang, 'referer' => $referer])
            ->withErrors(['active' => 'login before']);
        }
        */
        $policy_class = PolicyService::get($panel)->createIfNotExists()->getClass();
        $msg = 'Auth Id ['.\Auth::id().'] not can ['.$method.'] on ['.$policy_class.']';

        // $msg = 'Auth Id ['.\Auth::id().'] not can ['.$method.'] on ['.get_class($panel).']';
        FileService::viewCopy('theme::errors.403', 'pub_theme::errors.403');

        return response()->view('pub_theme::errors.403', ['msg' => $msg], 403);
    }
}
