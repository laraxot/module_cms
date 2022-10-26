<?php
/**
 * --.
 */
declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

// -------- services --------

use Modules\Xot\Models\Panels\Actions\XotBasePanelAction;

// -------- bases -----------

/**
 * Class TestAction.
 */
class TestAction extends XotBasePanelAction {
    public bool $onItem = true;
    public string $icon = '<i class="fas fa-vial"></i>';

    /**
     * @return mixed
     */
    public function handle() {
        dddx('qui');
    }
}
