<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Panel;

use Modules\Cms\Contracts\PanelContract;
use Spatie\QueueableAction\QueueableAction;

class UpdateAction
{
    use QueueableAction;

    public function __construct()
    {
    }

    public function execute(PanelContract $panel, array $data): PanelContract
    {
        // dddx($panel);
        $row = $panel->getRow();

        $rules = $panel->rules('edit');
        $act = str_replace('\Panel\\', '\Model\\', __CLASS__);
        $act = str_replace('\Cms\\', '\Xot\\', $act);

        $parent = $panel->getParent();
        if (null != $parent) {
            $rows = $panel->rows;
            if (method_exists($rows, 'getForeignKeyName') && method_exists($rows, 'getParentKey')) {
                $foreign_key_name = $rows->getForeignKeyName();
                $parent_key = $rows->getParentKey();
                $data[$foreign_key_name] = $parent_key;
            } else {
            }
        }

        app('\\'.$act)->execute($row, $data, $rules);

        return $panel;
    }
}
