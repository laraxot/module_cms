<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Support\Collection;
use Modules\Xot\Contracts\PanelContract;
use Modules\Xot\Contracts\PanelPresenterContract;

abstract class XotBasePanelPresenter implements PanelPresenterContract {
    protected PanelContract $panel;

    public function setPanel(PanelContract &$panel): self {
        $this->panel = $panel;

        return $this;
    }

    /**
     * @return mixed|void
     */
    public function index(?Collection $items) {
    }

    /**
     * @return mixed
     */
    public function out(?array $params = null) {
    }
}
