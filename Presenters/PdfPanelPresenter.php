<?php

declare(strict_types=1);

namespace Modules\Cms\Presenters;

use Illuminate\Support\Collection;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Contracts\PanelPresenterContract;
use Modules\UI\Services\ThemeService;
use Modules\Xot\Services\HtmlService;

/**
 * Class JsonPanelPresenter.
 */
class PdfPanelPresenter implements PanelPresenterContract
{
    protected PanelContract $panel;

    public array $view_params = [];

    public function setPanel(PanelContract &$panel): self
    {
        $this->panel = $panel;

        return $this;
    }

    public function setViewParams(array $view_params): self
    {
        $this->view_params = $view_params;

        return $this;
    }

    /**
     * @return mixed|void
     */
    public function index(?Collection $items)
    {
    }

    public function out(array $params = null): string
    {
        if (! isset($params['view_params'])) {
            $params['view_params'] = [];
        }
        // $view = ThemeService::g1etView(); // progressioni::admin.schede.show
        $view = $this->panel->getView();
        $view .= '.pdf';
        $view = str_replace('.store.', '.show.', $view);
        extract($params);
        $row = $this->panel->getRow();
        try {
            $rows = $this->panel->rows();
        } catch (\Exception $e) {
            $rows = collect([]);
        }
        if (null === $row->getKey()) { // utile per le cose a containers
            // if (null == $row) { //utile per le cose a containers
            // $row = tap($this->panel->rows())->first();
            // $row = $this->panel->rows()->first();
            // dddx($row);
            $tmp = $this->panel->rows()->get()->first();
            if (null !== $tmp) {
                $row = $tmp;
            }
        }

        $view_params = [
            'view' => $view,
            'row' => $row,
            'rows' => $rows,
        ];

        $view_params = array_merge($view_params, $this->view_params);

        $html = view($view, $view_params);
        $html = $html->render();
        // dddx($this->rows->get());
        if (request()->input('debug')) {
            return $html;
        }
        $params['html'] = (string) $html;

        return HtmlService::toPdf($params);
    }
}
