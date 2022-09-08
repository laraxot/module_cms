<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Response;
// use Illuminate\Support\Str;
use Modules\Xot\Services\PanelService;

// use Illuminate\Http\Response;

/**
 * Class PanelMiddleware.
 */
class PanelMiddleware {
    /**
     * @return \Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next) {
        $route_params = getRouteParameters();
        try {
            $panel = PanelService::make()
                ->getByParams($route_params);
        } catch (\Exception $e) {
            return response()
                ->view('pub_theme::errors.404', ['message' => $e->getMessage(), 'lang' => 'it'], 404);
        }

        PanelService::make()->setRequestPanel($panel);

        return $next($request);
    }
}