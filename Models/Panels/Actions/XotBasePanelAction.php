<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Actions;

use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;

// ----------  SERVICES --------------------------
// ------------ jobs ----------------------------
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Services\PanelService;
use Modules\Theme\Services\FormXService;
use Modules\Xot\Contracts\RowsContract;

/**
 * Class XotBasePanelAction.
 */
abstract class XotBasePanelAction {
    public bool $onContainer = false;

    public bool $onItem = false;

    public Model $row;

    /**
     * Undocumented variable.
     *
     * @var RowsContract
     */
    public $rows;

    // public Builder $rows;

    public PanelContract $panel;

    public ?string $name = null;

    public string $icon = '<i class="far fa-question-circle"></i>';

    public string $class = 'btn btn-secondary mb-2';

    protected array $data = [];

    public ?string $related = null; // post_type per filtrare le azioni nei vari index_edit

    /**
     * handle.
     *
     * @return mixed
     */
    abstract public function handle();

    /*
    public function __construct() {
        parent::__construct();
    }
    */
    /*
    public function __construct() {
        $data = request()->all();
        foreach ($data as $k => $v) {
            $this->$k = $v;
        }
    }
    */

    /**
     * @return mixed
     */
    public function postHandle() {
        return 'Add postHandle Method to Action !';
    }

    public function setPanel(PanelContract &$panel): self {
        $this->panel = $panel;

        return $this;
    }

    public function getName(): string {
        if (Str::contains((string) $this->name, '::')) {
            $this->name = null;
        }
        if (null !== $this->name) {
            return $this->name;
        }
        $this->name = Str::snake(class_basename($this));
        $str = '_action';
        if (Str::endsWith($this->name, $str)) {
            $this->name = Str::before($this->name, $str);
        }

        return $this->name;
    }

    public function getTitle(): string {
        $name = $this->getName();

        $row = $this->panel->getRow();

        $module_name_low = strtolower(getModuleNameFromModel($row));
        $trans_path = $module_name_low.'::'.strtolower(class_basename($row)).'.act.'.$name;
        $trans = trans($trans_path);

        if ($trans_path === $trans && ! config('xra.show_trans_key')) {
            $title = str_replace('_', ' ', $name);
        } else {
            $title = $trans;
        }

        if (! \is_string($title)) {
            return '---';
        }

        return $title;
    }

    public function getUrl(string $act = 'show'): string {
        if (isset($this->onItem) && $this->onItem) {
            return $this->urlItem();
        }

        return $this->urlContainer();
    }

    /**
     * @param RowsContract $rows
     */
    public function setRows($rows): self {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param Model $row
     */
    public function setRow($row): self {
        $this->row = $row;

        return $this;
    }

    public function btn(array $params = []): string {
        extract($params);
        if (isset($row)) {
            $this->setRow($row);
        }
        if (isset($panel)) {
            $this->setPanel($panel);
        }
        if (isset($this->onItem) && $this->onItem) {
            return $this->btnItem($params);
        }

        return $this->btnContainer($params);
    }

    public function url(string $act = 'show'): string {
        if (isset($this->onItem) && $this->onItem) {
            return $this->urlItem();
        }

        return $this->urlContainer();
    }

    public function urlContainer(/* string $act = 'show' */): string {
        $panel = $this->panel;
        // $request = \Request::capture();
        $name = $this->getName();
        // $url = $request->fullUrlWithQuery(['_act' => $name]);
        // if (isset($panel)) {
        // dddx([request()->all(),$this->data]);
        // dddx(request()->all());
        // dddx(get_class_methods(request()));
        $request_query = request()->query();
        if (! \is_array($request_query)) {
            $request_query = [];
        }

        $this->data = array_merge($request_query, $this->data);
        // $this->data = collect($this->data)->except(['fingerprint', 'serverMemo', 'updates'])->all();

        // $url = $panel->url('index');
        $url = $panel->url('index');
        $url = url_queries(['_act' => $name], $url);
        // $this->data['page'] = 1;
        $this->data['_act'] = $name;
        $url = url_queries($this->data, $url);
        // }

        return $url;
    }

    /**
     * @param array|string $key
     * @param mixed        $value
     */
    public function with($key, $value = null): self {
        if (\is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function btnHtml(array $params = []): string {
        $params['panel'] = $this->panel;

        if (isset($params['debug']) && true === $params['debug']) {
            dddx($params);
        }

        $params['method'] = Str::camel($this->getName());
        if (! isset($params['act'])) {
            if ($this->onContainer) {
                $params['act'] = 'index';
            } else {
                $params['act'] = 'show';
            }
        }

        $params['url'] = $this->getUrl($params['act']);

        if (! isset($params['title'])) {
            $params['title'] = ''; // $this->getTitle();
        }
        if (true === $params['title']) {
            $params['title'] = $this->getTitle();
        }

        if (! isset($params['tooltip'])) {
            $params['tooltip'] = $this->getTitle();
        }

        if (! isset($params['data_title'])) {
            $params['data_title'] = $this->getTitle();
        }
        if (! isset($params['icon'])) {
            $params['icon'] = $this->icon;
        }
        if (! isset($params['class'])) {
            $params['class'] = $this->class;
        }

        return FormXService::btnHtml($params);
    }

    public function btnContainer(array $params = []): string {
        $act = isset($params['act']) ? $params['act'] : 'show';
        $url = $this->urlContainer();
        $title = $this->getTitle();
        $params['url'] = $url;
        $params['title'] = $title;

        return $this->btnHtml($params);
        /*'<a href="'.$url.'" class="btn btn-secondary" data-toggle="tooltip" title="'.$title.'">
            '.$this->icon.'&nbsp;'.$title.'
            </a>';*/
    }

    // end btnContainer

    public function urlItem(/* string $act = 'show' */): string {
        $url = '';
        $query_params = [];

        if (! isset($this->panel)) {
            $this->panel = PanelService::make()->get($this->row);
        }
        $name = $this->getName();
        $url = $this->panel->url('show');
        $query_params['_act'] = $name;
        /*
        if (isset($modal)) {
            $this->data['modal'] = $modal;
        }
        $this->data = array_merge(request()->all(), $this->data);
        */

        $url = url_queries($query_params, $url);
        $url = url_queries($this->data, $url);

        return $url;
    }

    public function btnItem(array $params = []): string {
        $url = $this->urlItem();
        $title = $this->getTitle();
        $method = Str::camel($this->getName());

        extract($params);
        if (Gate::allows($method, $this->panel)) {
            if (isset($modal)) {
                switch ($modal) {
                    case 'iframe':
                        return
                            '<button type="button" data-title="'.$title.'"
						data-href="'.$url.'" data-toggle="modal" class="btn btn-secondary mb-2" data-target="#myModalIframe">
                        '.$this->icon.'
                        </button>';
                        // break;
                    case 'ajax':
                        break;
                }
            }

            return '<a href="'.$url.'" class="btn btn-success" data-toggle="tooltip" title="'.$title.'">
            '.$this->icon.'</i>&nbsp;'.$title.'
            </a>';
        } else {
            return '<button type="button" class="btn btn-secondary btn-lg" data-toggle="tooltip" title="not can" disabled>'.$this->icon.' '.$method.'</button>';
        }
    }

    // end btnItem
    // * --
    public function updateRow(array $params = []): PanelContract {
        $row = $this->row;
        extract($params);
        $this->panel->setRow($row);
        $data = request()->all();
        $this->panel->update($data);

        return $this->panel;
        /*
        $item = $row;
        $up = UpdateJob::dispatchNow($container, $item);
        return $up;
        */
    }

    // */

    /**
     * Undocumented function.
     *
     * @return mixed
     */
    public function pdf(array $params = []) {
        /*
        if (null == $this->row) {
            $this->row = clone($this->rows)->get()[0];
            if (! is_object($this->row)) {
                die('<h3>Nulla da creare nel PDF</h3>');
                //$this->row=$this->rows->getModel();
            }
        }
        $panel = PanelService::make()->get($this->row);
        $panel->setRowzs($this->rows);
        */
        return $this->panel->pdf($params);
    }
}
