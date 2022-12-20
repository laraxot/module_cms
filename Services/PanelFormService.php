<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Theme\Contracts\FieldContract;
use Modules\Theme\Services\FieldService;
use Modules\Theme\Services\FormXService;
use Modules\Theme\Services\ThemeService;

/**
 * Class PanelFormService.
 */
class PanelFormService {
    protected PanelContract $panel;

    public function __construct(PanelContract &$panel) {
        $this->panel = $panel;
    }

    /**
     * setPanel.
     *
     * @return $this
     */
    public function setPanel(PanelContract &$panel) {
        $this->panel = $panel;

        return $this;
    }

    /**
     * @return string
     */
    public function formCreate(array $params = []) {
        $fields = $this->getFields(['act' => 'create']);
        $row = $this->panel->getRow();
        $res = '';
        // $res.='<h3>'.$this->url('store').'</h3>'; //4 debug
        $res .= Form::bsOpenPanel($this, 'store');
        $res .= '<div class="clearfix">';
        foreach ($fields as $field) {
            $res .= ThemeService::inputHtml(['row' => $row, 'field' => $field]);
        }
        $res .= '</div>';
        // $res.=Form::bsSubmit('save');
        $res .= '<p class="form-submit">
            <input name="submit" type="submit" id="submit" value="Post your answer" class="button small color">
        </p>';
        $res .= Form::close();

        return $res;
    }

    /**
     * @return string
     */
    public function formEdit(array $params = []) {
        $submit_btn = '<p class="form-submit">
            <input name="submit" type="submit" id="submit" value="Post your answer" class="button small color">
        </p>';
        extract($params);
        /**
         * @var array<object>
         */
        $fields = $this->getFields(['act' => 'edit']);
        $row = $this->panel->getRow();
        $res = '';
        // $res.='<h3>'.$this->url('store').'</h3>'; //4 debug
        $res .= Form::bsOpenPanel($this->panel, 'update');

        $col_size = 0;

        $res .= '<div class="row clearfix">';
        foreach ($fields as $field) {
            if ($col_size >= 12) {
                echo '</div><div class="row">';
                $col_size = 0;
            }
            $col_size += $field->col_size ?? 12;
            $res .= ThemeService::inputHtml(['row' => $row, 'field' => $field]);
        }
        $res .= '</div>';
        // $res.=Form::bsSubmit('save');
        $res .= $submit_btn;
        $res .= Form::close();

        return $res;
    }

    public function formLivewireEdit(array $params = []): string {
        $fields = $this->editObjFields();

        $col_size = 0;
        $html = '<div class="row clearfix">';
        foreach ($fields as $field) {
            if ($col_size >= 12) {
                echo '</div><div class="row">';
                $col_size = 0;
            }
            $col_size += $field->col_size ?? 12;
            // $res .= ThemeService::inputHtml(['row' => $row, 'field' => $field]);
            $html .= $field->toHtml();
        }
        $html .= '</div>';
        // $res.=Form::bsSubmit('save');
        // $html .= $submit_btn;
        // $html .= Form::close();
        return $html;
    }

    public function getFormData(array $params = []): array {
        $form_data = [];

        $fields = $this->getFields($params);
        $row = isset($params['row']) ? $params['row'] : $this->panel->getRow();
        foreach ($fields as $field) {
            $value = Arr::get($row, $field->name);
            if (\is_object($value)) {
                switch (\get_class($value)) {
                    case 'Illuminate\Support\Carbon':
                        $value = $value->format('Y-m-d\TH:i');
                        break;
                    default:
                        dddx(\get_class($value));
                        break;
                }
            }
            Arr::set($form_data, $field->name, $value);
        }

        return $form_data;
    }

    /*
    public function btnDelete(array $params = []) {
        $class = 'btn-primary mb-2';
        extract($params);
        //dddx($params);
        $act = 'destroy';
        $parz = [
            'id' => $this->panel->getRow()->getKey(),
            'btn_class' => 'btn '.$class,
            'route' => $this->panel->url('destroy'),
            'act' => $act,
            'title' => $title,
        ];

        return view()->make('theme::includes.components.btn.'.$act)->with($parz);
    }

    public function btnDetach(array $params = []) {
        $class = 'btn-primary mb-2';
        extract($params);
        $act = 'detach';
        $parz = [
            'id' => $this->panel->getRow()->getKey(),
            'btn_class' => 'btn '.$class,
            'route' => $this->url('detach'),
            'act' => $act,
        ];

        return view()->make('theme::includes.components.btn.'.$act)->with($parz);
    }
    */

    public function btnCrud(array $params = []): string {
        extract($params);
        $acts = ['edit', 'destroy', 'show'];
        if (\is_object($this->panel->getRow()->getRelationValue('pivot'))) {
            $acts = ['edit', 'destroy', 'detach', 'show'];
        }

        $html = '';
        if (! \in_array('title', array_keys($params), true)) {
            $params['title'] = '';
        }

        foreach ($acts as $act) {
            $params['act'] = $act;
            $html .= $this->btnHtml($params);
        }
        if (\in_array('group', array_keys($params), true) && false === $params['group']) {
        } else {
            $html = '<div role="group" aria-label="Actions" class="btn-group btn-group-sm">'.
            \chr(13).$html.\chr(13).'</div>';
        }

        return $html;
    }

    public function btnHtml(array $params): ?string {
        $params['url'] = $this->panel->url($params['act']);
        // dddx([$this->panel->route, $params['panel'], $params['url']]);
        $params['method'] = Str::camel($params['act']);
        if ('index_order' === $params['act']) {
            //  dddx($params);
        }

        if (! isset($params['tooltip'])) {
            $row = $this->panel->getRow();
            $module_name_low = (string) strtolower((string) getModuleNameFromModel($row));
            $params['tooltip'] = trans($module_name_low.'::'.strtolower(class_basename($row)).'.act.'.$params['method']);
        }

        if (! isset($params['title'])) {
            $row = $this->panel->getRow();
            $module_name_low = strtolower((string) getModuleNameFromModel($row));

            $trans_key = $module_name_low.'::'.strtolower(class_basename($row)).'.act.'.$params['method'];
            $trans = trans($trans_key);
            $title = $trans;
            if ($trans === $trans_key && ! config('xra.show_trans_key')) {
                $title = class_basename($row); // .' '.$params['method'];
            }

            $params['title'] = $title;
        }

        if (! isset($params['icon'])) {
            switch ($params['method']) {
                case 'index':
                    $params['icon'] = '<i class="fas fa-bars"></i>';
                    break;
                case 'create':
                    $params['icon'] = '<i class="far fa-plus-square"></i>';
                    break;
                case 'edit':
                    $params['icon'] = '<i class="far fa-edit"></i>';
                    break;
                case 'destroy':
                    $params['icon'] = '<i class="far fa-trash-alt"></i>';
                    break;
                case 'show':
                    $params['icon'] = '<i class="far fa-eye"></i>';
                    break;
                case 'indexOrder':
                    $params['icon'] = '<i class="fas fa-sort"></i>';
                    break;
                default:
                    // $params['icon'] = $params['method']; //per vedere quale
                    break;
            }
        }

        if (true === $params['title']) {
            $row = $this->panel->getRow();
            $module_name_low = strtolower((string) getModuleNameFromModel($row));
            $parent = $this->panel->getParent();
            if (null !== $parent) {
                $tmp = [];
                $tmp[] = class_basename($parent->getRow());
                $tmp[] = class_basename($row);
                $tmp[] = 'act';
                $tmp[] = $params['method'];
                $tmp = collect($tmp)->map(
                    function ($item) {
                        return Str::snake($item);
                    }
                )->implode('.');
                $params['title'] = trans($module_name_low.'::'.$tmp);
            } else {
                $params['title'] = trans($module_name_low.'::'.strtolower(class_basename($row)).'.act.'.$params['method']);
            }
        }
        $params['panel'] = $this->panel;

        return FormXService::btnHtml($params);
    }

    /*
    public function btn($act, $params = []) {
        dddx('deprecated');
        extract($params);
        $parents = [];
        $parent = $this->panel->parent;
        $route_params = optional(\Route::current())->parameters();
        $cont_i = RouteService::containerN(['model' => get_class($parent->getRow())]);
        $routename = RouteService::routenameN(['n' => $cont_i + 1, 'act' => $act]);

        $route_params['item'.($cont_i + 0)] = $this->parent->row;
        $route_params['container'.($cont_i + 1)] = $this->postType();
        $route_params['item'.($cont_i + 1)] = $this->panel->getRow();
        $route = route($routename, $route_params);
        //http://multi.local:8080/it/profile/profile%20279656/restaurant/pizza%20gino/cuisine/antipasti/recipe/gigi]
        //return '['.$routename.']<br>['.$route.'][['.$cont_i.']';
        $parz = [
            'id' => $this->panel->getRow()->id,
            'btn_class' => 'btn',
            'route' => $route,
            'act' => $act,
        ];
        if (isset($modal) && $modal) {
            return view()->make('theme::includes.components.btn.modal')->with($parz);
        }

        return view()->make('theme::includes.components.btn.'.$act)->with($parz);
    }
    */
    /* deprecated
    public function btnSubmit(array $params = []) {
        return Form::bsSubmit(trans('xot::buttons.save'));
    }
    */

    /**
     * exceptFields.
     */
    public function exceptFields(array $params = []): Collection {
        $act = 'show';
        extract($params);
        $panel = $this->panel;
        extract($params);
        $excepts = collect([]);
        if (isset($panel->rows) && \is_object($panel->getRows())) {
            $methods = [
                'getForeignKeyName', // relation  BelongsTo,HasManyThrought,HasOneOrMany
                'getMorphType',     // relation   MorphOneOrMany,MorphPivot,MorphTo,MorphToMany
                // 'getLocalKeyName',
                'getForeignPivotKeyName', // relation BelongsToMany
                'getRelatedPivotKeyName', // relation  BelongsToMany
                'getRelatedKeyName', // relation  BelongsToMany
            ];
            if ('index' !== $act) { // nella lista voglio visualizzare l'id
                $methods[] = 'getLocalKeyName';
            }

            foreach ($methods as $method) {
                if (method_exists($panel->getRows(), $method)) {
                    $excepts = $excepts->merge($panel->getRows()->$method());
                }
            }
        }
        // dddx($excepts);
        $excepts = $excepts->unique()->all();
        $fields = collect($panel->fields())
            ->filter(
                function ($item) use ($excepts, $act) {
                    if (! isset($item->except)) {
                        $item->except = [];
                    }

                    // !in_array($item->type,['Password']) &&
                    return ! \in_array($act, $item->except, true) &&
                        ! \in_array($item->name, $excepts, true);
                }
            );

        return $fields;
    }

    /**
     * Undocumented function.
     *
     * @return Collection<FieldContract>
     */
    public function getFields(array $params = []): Collection {
        $act = isset($params['act']) ? $params['act'] : 'index';

        $fields = $this->exceptFields(['act' => $act]);

        return $fields;
    }

    public function editObjFields(): array {
        /**
         * @var Collection<FieldContract>
         */
        $fields = $this->getFields(['act' => 'edit']);
        $fields = $fields->map(
            /**
             * @phpstan-param object $field
             *
             * @param mixed $item
             */
            function ($item) {
                /**
                 * @var object
                 */
                $field = $item;
                /**
                 * @var array
                 */
                $vars = get_object_vars($field);

                return FieldService::make()
                    ->setVars($vars)
                // ->type($field->type)
                // ->setColSize($field->col_size ?? 12)
                ;
            }
        )->all();

        return $fields;
    }
}
