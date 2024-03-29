<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\UI\Services\ThemeService;
use Modules\Xot\Services\ArrayService;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Class JsonPanelPresenter.
 */
class XlsPanelPresenter implements PanelPresenterContract
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
     * @return Renderable|BinaryFileResponse
     */
    public function out(array $params = null)
    {
        if (! isset($params['view_params'])) {
            $params['view_params'] = [];
        }
        // $view = ThemeService::g1etView(); // progressioni::admin.schede.show
        $view = $this->panel->getView();
        /**
         * @var string
         */
        $name = last(explode('.', $view));
        // dddx($name);
        // $view .= '.pdf';
        // $view = str_replace('.store.', '.show.', $view);
        extract($params);
        // $row = $this->panel->getRow();
        $rows = $this->panel->getRows()->get()->toArray();

        // if (null == $row->getKey()) { //utile per le cose a containers
        //    $row = $this->panel->getRows()->first();
        // }
        /*
        $html = view($view)
            ->with('view', $view)
            ->with('row', $row)
            ->with('rows', $rows)
            ->with($params['view_params']);
        */
        // dddx($this->rows->get());
        // if (request()->input('debug')) {
        //    return $html;
        // }
        // $params['html'] = (string) $html;

        return ArrayService::make()
            ->setArray($rows)
            ->setFileName($name)
            ->toXls();
    }
}
