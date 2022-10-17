<?php
namespace Modules\Cms\Models\Panels\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\LU\Models\User as User;
use Modules\Cms\Models\Panels\Policies\ConfPanelPolicy as Panel;

use Modules\Xot\Models\Panels\Policies\XotBasePanelPolicy;

class ConfPanelPolicy extends XotBasePanelPolicy {
}
