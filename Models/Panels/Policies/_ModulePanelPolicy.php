<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Contracts\PanelContract;
use Modules\Xot\Models\Panels\Policies\XotBasePanelPolicy;

class _ModulePanelPolicy extends XotBasePanelPolicy {

    public function showModelsModuleMenu(UserContract $user,PanelContract $panel):bool{
        
        return false;
    }
}
