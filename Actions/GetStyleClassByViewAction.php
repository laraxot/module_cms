<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Illuminate\Support\Str;
use Modules\Xot\Services\FileService;
use Spatie\QueueableAction\QueueableAction;

class GetStyleClassByViewAction {
    use QueueableAction;

    public function execute(string $view = ''): string {
        $config_path = inAdmin() ? 'adm_theme' : 'pub_theme';
        $config_key = '::'.Str::after($view, '::components.').'.class';
        $key = $config_path.$config_key;

        $class = FileService::config($key);
        if (is_string($class)) {
            return $class;
        }
        $key1 = 'cms'.$config_key;

        $class = FileService::config($key1);

        if (is_string($class)) {
            FileService::configCopy($key1, $key);

            return $class;
        }

        // *
        dddx([
            'key' => $key,
            'value' => FileService::config($key),
            'key1' => $key1,
            'value1' => FileService::config($key1),
        ]);
        // */
        // dddx(['kye' => config($key), 'k1' => config($key1)]);
        throw new \Exception('create config ['.$key.'] or ['.$key1.']['.__LINE__.']['.__FILE__.']');

        return $class;
    }
}
