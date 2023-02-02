<?php

declare(strict_types=1);

namespace Modules\Cms\Contracts;

interface PanelActionContract
{
    /**
     * Undocumented function.
     */
    public function url(string $act = 'show'): string;
}
