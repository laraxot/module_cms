<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\Xot\Services\StubService;

/**
 * Class JsonPanelPresenter.
 */
class JsonPanelPresenter implements PanelPresenterContract
{
    protected PanelContract $panel;

    public function setPanel(PanelContract &$panel): self
    {
        $this->panel = $panel;

        return $this;
    }

    /**
     * @return mixed|void
     */
    public function index(?Collection $items)
    {
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     */
    public function outContainer(array $params = null)
    {
        $model = $this->panel->getRow();
        $transformer = StubService::make()->setModelAndName($model, 'transformer_collection')->get();
        $rows = $this->panel->rows()->paginate(20);
        $out = new $transformer($rows);

        return $out;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \ReflectionException
     */
    public function outItem(array $params = null)
    {
        $model = $this->panel->getRow();
        $transformer = StubService::make()->setModelAndName($model, 'transformer_resource')->get();
        $out = new $transformer($model);

        return $out;
    }

    public function out(array $params = null)
    {
        if (isContainer()) {
            return $this->outContainer($params);
        }

        return $this->outItem($params);
    }
}
