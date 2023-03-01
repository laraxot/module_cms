<?php

declare(strict_types=1);

namespace Modules\Cms\Actions;

use Illuminate\Support\Str;
use Modules\Xot\Services\FileService;
use Spatie\QueueableAction\QueueableAction;

class GetStyleClassByViewAction {
    use QueueableAction;

    public function execute(string $view = ''): string {
        return app(GetConfigKeyByViewAction::class)->execute($view,'class');
    }
}
