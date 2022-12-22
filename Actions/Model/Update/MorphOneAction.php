<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class MorphOneAction {
    use QueueableAction;

    public function __construct() {
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function execute(Model $row, object $relation) {
        /* con update or create crea sempre uno nuovo, con update e basta se non esiste non va a crearlo */
        // $rows = $model->$name();
        $rows = $relation->rows;
        if ($rows->exists()) {
            $rows->update($relation->data);
        } else {
            if (! isset($relation->data['lang'])) {
                $relation->data['lang'] = \App::getLocale();
            }
            $rows->create($relation->data);
        }
    }
}
