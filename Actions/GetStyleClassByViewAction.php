<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Illuminate\Support\Str;
use Spatie\QueueableAction\QueueableAction;

class GetStyleClassByViewAction {
    use QueueableAction;

    public function execute(string $view = ''): string {
        $config_key = inAdmin() ? 'adm_theme' : 'pub_theme';
        $config_key .= '::'.Str::after($view, '::components.').'.class';

        $class = config($config_key);
        if (! is_string($class)) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $class;
    }
}
