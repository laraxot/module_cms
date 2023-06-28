<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

class CreateAction
{
    use QueueableAction;

    public function __construct()
    {
    }

    public function execute(PanelContract $panel, array $data): PanelContract
    {
        return $panel;
    }
}
