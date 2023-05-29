<?php

declare(strict_types=1);

namespace Modules\Cms\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface PanelPresenterContract.
 */
interface PanelPresenterContract
{
    public function index(?Collection $items);

    public function setPanel(PanelContract &$panel);

    public function out(array $params = null);
}
