<?php

declare(strict_types=1);

namespace Modules\Cms\Contracts;

use Illuminate\Support\Collection;

/**
 * Interface PanelPresenterContract.
 */
interface PanelPresenterContract
{
    /**
     * @return mixed
     */
    public function index(?Collection $items);

    /**
     * @return mixed
     */
    public function setPanel(PanelContract &$panel);

    /**
     * @return mixed
     */
    public function out(?array $params = null);
}
