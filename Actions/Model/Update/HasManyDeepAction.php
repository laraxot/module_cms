<?php

declare(strict_types=1);

namespace Modules\Cms\Actions\Model\Update;

use Illuminate\Database\Eloquent\Model;
use Spatie\QueueableAction\QueueableAction;

class HasManyDeepAction {
    use QueueableAction;

    public function __construct() {
    }

    /**
     * Undocumented function.
     *
     * @return void
     */
    public function execute(Model $row, object $relation) {
        dddx('wip');
    }
}
