<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Modules\Cms\Contracts\PanelContract;
use Modules\Xot\Contracts\UserContract;

class ModulePanelPolicy extends XotBasePanelPolicy
{
    public function db(UserContract $user, PanelContract $panel): bool
    {
        return true;
    }

    public function downloadDbModule(UserContract $user, PanelContract $panel): bool
    {
        return true;
    }

    public function fixJigSaw(UserContract $user, PanelContract $panel): bool
    {
        return true;
    }
}
