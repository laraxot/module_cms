<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Model\Store;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class MorphToOneAction {
    use QueueableAction;

    public function __construct() {
    }

    public function execute(Model $row, object $relation) {
        // dddx(['row' => $row, 'relation' => $relation, 'relation_data' => $relation->data]);

        $rows = $relation->rows;

        if (is_array($relation->data)) {
            if (! isset($relation->data['lang'])) {
                $relation->data['lang'] = \App::getLocale();
            }
            $rows->create($relation->data);
        } else {
            $rows->sync($relation->data);
        }

        return;
        /*
        dddx([
            'message' => 'wip',
            'row' => $row,
            'relation' => $relation,
            'relation_rows' => $relation->rows->exists(),
            't' => $row->{$relation->name},
        ]);

        dddx('wip');
        */
    }
}
