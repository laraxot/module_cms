<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

class DetachAction
{
    use QueueableAction;

    public function __construct()
    {
    }

    public function execute(PanelContract $panel, array $data): PanelContract
    {
        $row = $panel->getRow();
        $rules = [];
        $act = str_replace('\Panel\\', '\Model\\', __CLASS__);
        $act = str_replace('\Cms\\', '\Xot\\', $act);
        app('\\'.$act)->execute($row, $data, $rules);
        /*
        if (method_exists($panel, 'detachCallback')) {
            $panel->detachCallback(['row' => $row]);
        }
        */

        return $panel;
    }
}
