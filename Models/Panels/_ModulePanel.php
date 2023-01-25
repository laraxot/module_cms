<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels;

/**
 * Class _ModulePanel.
 */
class _ModulePanel extends XotBasePanel
{
    public function actions(): array
    {
        return [
            new Actions\TestAction(),
        ];
    }
}
