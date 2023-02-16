<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetStyleClassByViewAction {
    use QueueableAction;

    public function execute(string $view = ''): string {
        $config_path = inAdmin() ? 'adm_theme' : 'pub_theme';
        $config_key = '::'.Str::after($view, '::components.').'.class';
        $key=$config_path.$config_key;
        $key1='ui'.$config_key;
        $class = config($key) ?? config($key1);
        if (! is_string($class)) {
            dddx(['kye'=>config($key),'k1'=>config($key1)]);
            throw new \Exception('create config ['.$key.']['.$key1.']'.__LINE__.']['.__FILE__.']');
        }

        return $class;
    }
}
