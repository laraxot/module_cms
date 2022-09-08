<?php

declare(strict_types=1);

namespace Modules\Cms\Providers;

<<<<<<< HEAD
use Modules\Xot\Providers\XotBaseServiceProvider;
use Modules\Xot\Services\BladeService;
=======
use Exception;
use Modules\Xot\Providers\XotBaseServiceProvider;
use Modules\Xot\Services\BladeService;
use Modules\Xot\Services\FileService;
>>>>>>> cf4013a149aeaf8eca328d0ee285681ff6b35043

/**
 * Undocumented class.
 */
class CmsServiceProvider extends XotBaseServiceProvider {
    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public string $module_name = 'cms';

<<<<<<< HEAD
    public function bootCallback(): void {
        BladeService::registerComponents($this->module_dir.'/../View/Components', 'Modules\\Cms');
=======
    public array $xot = [];

    public function bootCallback(): void {
        // BladeService::registerComponents($this->module_dir.'/../View/Components', 'Modules\\Cms');

        $xot = config('xra');

        $this->xot = is_array($xot) ? $xot : [];
        $this->registerNamespaces('pub_theme');
>>>>>>> cf4013a149aeaf8eca328d0ee285681ff6b35043
    }

    public function registerCallback(): void {
    }
<<<<<<< HEAD
=======

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function registerNamespaces(string $theme_type) {
        /**
         * @var array
         */
        $xot = $this->xot;
        if (! isset($xot[$theme_type])) {
            throw new Exception('['.print_r($xot, true).']['.$theme_type.']['.__LINE__.']['.__FILE__.']');
        }
        $theme = $xot[$theme_type];

        $resource_path = 'Themes/'.$theme.'/Resources';
        $lang_dir = FileService::fixPath(base_path($resource_path.'/lang'));

        $theme_dir = FileService::fixPath(base_path($resource_path.'/views'));

        app('view')->addNamespace($theme_type, $theme_dir);
        $this->loadTranslationsFrom($lang_dir, $theme_type);
    }
>>>>>>> cf4013a149aeaf8eca328d0ee285681ff6b35043
}
