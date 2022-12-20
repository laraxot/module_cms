<?php

declare(strict_types=1);

namespace Modules\Cms\Providers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Modules\Tenant\Services\TenantService;
use Modules\Theme\Services\ThemeService;
use Modules\Xot\Providers\XotBaseServiceProvider;
use Modules\Xot\Services\BladeService;
use Modules\Xot\Services\FileService;

/**
 * Undocumented class.
 */
class CmsServiceProvider extends XotBaseServiceProvider {
    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public string $module_name = 'cms';

    public array $xot = [];

    /**
     * Undocumented function.
     */
    public function getXot(): array {
        return $this->xot;
    }

    public function bootCallback(): void {
        // BladeService::registerComponents($this->module_dir.'/../View/Components', 'Modules\\Cms');

        $xot = config('xra');

        $this->xot = \is_array($xot) ? $xot : [];
        // $this->registerNamespaces('pub_theme');
        $this->registerNamespaces('adm_theme');
        $this->registerNamespaces('pub_theme');

        $this->bootThemeProvider('pub_theme');

        $this->registerThemeConfig('adm_theme');
        $this->registerThemeConfig('pub_theme');

        $this->registerViewComposers();

        $timezone = config('app.timezone') ?? 'Europe/Berlin';
        date_default_timezone_set($timezone);
    }

    public function registerCallback(): void {
        $configFileName = 'xra';
        $this->mergeConfigFrom(__DIR__."/../Config/{$configFileName}.php", $configFileName);
    }

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
            throw new \Exception('['.print_r($xot, true).']['.$theme_type.']['.__LINE__.']['.__FILE__.']');
        }
        $theme = $xot[$theme_type];

        $resource_path = 'Themes/'.$theme.'/Resources';
        $lang_dir = FileService::fixPath(base_path($resource_path.'/lang'));

        $theme_dir = FileService::fixPath(base_path($resource_path.'/views'));

        app('view')->addNamespace($theme_type, $theme_dir);
        $this->loadTranslationsFrom($lang_dir, $theme_type);
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function bootThemeProvider(string $theme_type) {
        if ($this->app->runningInConsole()) {
            return;
        }
        $xot = $this->getXot();
        if (! isset($xot[$theme_type])) {
            return;
        }
        $theme = $xot[$theme_type];
        if (! File::exists(base_path('Themes/'.$theme))) {
            $xot[$theme_type] = ThemeService::firstThemeName($theme_type);
            TenantService::saveConfig(['name' => 'xra', 'data' => $xot]);
            throw new \Exception('['.base_path('Themes/'.$theme).' not exists]['.__LINE__.']['.class_basename(__CLASS__).']');
        }
        $provider = 'Themes\\'.$theme.'\Providers\\'.$theme.'ServiceProvider';
        if (! class_exists($provider)) {
            throw new \Exception('class not exists ['.$provider.']['.__LINE__.']['.basename(__FILE__).']');
        }

        $provider = new $provider();

        if (method_exists($provider, 'bootCallback')) {
            $provider->bootCallback();
        }
    }

    public function registerThemeConfig(string $theme_type): void {
        $xot = $this->getXot();

        if (! isset($xot[$theme_type])) {
            $xot[$theme_type] = ThemeService::firstThemeName($theme_type);
            // TenantService::saveConfig(['name' => 'xra', 'data' => $xot]);
        }
        $theme = $xot[$theme_type];

        $config_path = base_path('Themes/'.$theme.'/Config');
        if (! File::exists($config_path)) {
            return;
        }
        $files = File::files($config_path);
        foreach ($files as $file) {
            $name = $file->getFilenameWithoutExtension();
            $real_path = $file->getRealPath();
            if (false === $real_path) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }
            $data = File::getRequire($real_path);
            Config::set($theme_type.'::'.$name, $data);
        }
    }

    /**
     * Undocumented function.
     */
    private function registerViewComposers(): void {
        if ($this->app->runningInConsole()) {
            return;
        }
        $xot = $this->getXot();
        if (! isset($xot['pub_theme'])) {
            $xot['pub_theme'] = ThemeService::getThemeType('pub_theme');
        }
        if (! isset($xot['adm_theme'])) {
            $xot['adm_theme'] = ThemeService::getThemeType('adm_theme');
        }

        $theme = inAdmin() ? $xot['adm_theme'] : $xot['pub_theme'];
        if (null === $theme) {
            throw new \Exception('iuston gavemo un problema ['.__LINE__.']['.class_basename(__CLASS__).']');
        }

        $custom_composer = '\Themes\\'.$theme.'\View\Composers\ThemeComposer';
        if (class_exists($custom_composer)) {
            View::composer('*', $custom_composer);

            return;
        } else {
            dddx('['.$custom_composer.']');
        }

        // View::composer('*', ThemeComposer::class);
    }
}
