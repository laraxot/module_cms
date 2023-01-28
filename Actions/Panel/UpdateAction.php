<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction {
    use QueueableAction;

    public function __construct() {
    }

    public function execute(PanelContract $panel, array $data): PanelContract {
        dddx($panel);
        $row = $panel->getRow();

        $rules = $panel->rules(['act' => 'edit']);
        $act = str_replace('\Panel\\', '\Model\\', __CLASS__);
        $act = str_replace('\Cms\\', '\Xot\\', $act);
        app('\\'.$act)->execute($row, $data, $rules);

        return $panel;
    }
}
