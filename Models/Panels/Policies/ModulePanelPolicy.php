<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Modules\Xot\Contracts\UserContract;
use Modules\Cms\Contracts\PanelContract;

class ModulePanelPolicy extends XotBasePanelPolicy {

    public function db(UserContract $user, PanelContract $panel):bool{
        return true;
    }

    public function downloadDbModule(UserContract $user, PanelContract $panel):bool{
        return true;
    }
}