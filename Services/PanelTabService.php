<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Models\Panels\XotBasePanel;

/**
 * Class PanelTabService.
 */
class PanelTabService {
    protected XotBasePanel $panel;

    /**
     * PanelTabService constructor.
     */
    public function __construct(XotBasePanel &$panel) {
        $this->panel = $panel;
    }

    public function getItemTabs(): array {
        /*
        $item = $this->panel->getRow();
        $tabs = $this->panel->tabs();
        $routename = (string) \Route::currentRouteName();
        $act = last(explode('.', $routename));
        $row = [];
        foreach ($tabs as $tab) {
            $tmp = new \stdClass();
            $tmp->title = $tab;

            if (in_array($act, ['index_edit', 'edit', 'update'])) {
                $tab_act = 'index_edit';
            } else {
                $tab_act = 'index';
            }
            $tmp->url = $this->panel->relatedUrl($tab, $tab_act);
            $tmp->active = false; //in_array($tab,$containers);
            $row[] = $tmp;
        }

        return [$row];
        */
        return $this->getBreadTabs($this->panel);
    }

    public function getRowTabs(): array {
        return $this->getBreadTabs($this->panel);
        /*
        $data = [];
        if (null == $this->panel->getRow()) {
            return $data;
        }

        foreach ($this->panel->tabs() as $tab) {
            $tmp = (object) [];
            $tmp->title = trans($this->panel->getModuleNameLow().'::'.class_basename($this->panel->getRow()).'.tab.'.$tab);
            $tmp->url = $this->panel->relatedUrl('$tab', 'index');

            //if ('#' != $tmp->url[0]) {
            //    dddx(['tmp' => $tmp, 'panel' => $this->panel]);
            //}

            $tmp->index_edit_url = $this->panel->relatedUrl('$tab', 'index_edit');
            $tmp->create_url = $this->panel->relatedUrl('$tab', 'create');
            $tmp->active = false;
            $data[] = $tmp;
        }

        return $data;
        */
    }

    public function getBreadTabs(PanelContract $bread): array {
        [$containers, $items] = params2ContainerItem();
        // dddx( [$bread,$containers, $items]);
        $tabs = $bread->tabs();
        $row = [];
        if ('' !== $bread->guid()) {
            foreach ($tabs as $tab) {
                $tab_panel = $bread->relatedName($tab);
                if (Gate::allows('index', $tab_panel)) {
                    $trans_key = $bread->getTradMod().'.tab.'.Str::snake($tab);
                    /*
                    dddx([
                        $trans_key.'.label',
                        trans($trans_key.'.label'),
                        trans('quaeris::show_answer_action.submitdate'),
                        __('quaeris::show_answer_action.submitdate'),
                        __('pub_theme::txt.day_names.sun'),
                        Lang::get('quaeris::show_answer_action.submitdate'),
                        app()->getLocale(),
                    ]);
                    */
                    $tmp = (object) [
                        'title' => trans($trans_key.'.label'),
                        'icon' => trans($trans_key.'.icon'),
                        'url' => $tab_panel->url('index'),
                        'active' => \in_array($tab, $containers, true),
                    ];
                    $row[] = $tmp;
                }
            }
        }

        return $row;
    }

    public function getTabs(): array {
        $breads = $this->panel->getBreads();

        $data = [];
        foreach ($breads as $bread) {
            $data[] = $this->getBreadTabs($bread);
        }

        return $data;
    }

    public function getTabsOld(): array {
        $request = \Request::capture();
        $routename = (string) \Route::currentRouteName();
        $act = last(explode('.', $routename));
        // $routename = \Route::current()->getName();
        $route_current = \Route::current();
        $route_params = [];
        if (null !== $route_current) {
            $route_params = $route_current->parameters();
        }
        [$containers, $items] = params2ContainerItem($route_params);
        $data = [];
        // $items[]=$this->row;
        if (! \is_array($items)) {
            return [];
        }
        // array_unique($items);
        $parents = $this->panel->getParents();
        if ('' !== $this->panel->guid()) {
            $parents->push($this->panel);
        }
        // dddx($parents);

        foreach ($parents as $k => $panel) {
            // $item = $panel->getRow();
            $tabs = [];
            if (! \is_object($panel)) {
                return $tabs;
            }
            $tabs = $panel->tabs();
            $row = [];
            // *
            if (0 === $k) {
                if (Gate::allows('index', $panel)) {
                    $tmp = new \stdClass();
                    // $tmp->title = '<< Back '; //.'['.get_class($item).']';
                    $tmp->title = 'Back'; // .'['.get_class($item).']';
                    $tmp->url = $panel->url('index');
                    $tmp->active = false;
                    $row[] = $tmp;
                }
                // -----------------------
                $tmp = new \stdClass();
                if (\in_array($act, ['index_edit', 'edit', 'update'], true)) {
                    $url = $panel->url('edit');
                } else {
                    $url = $panel->url('show');
                }
                $tmp->url = $url;
                $tmp->title = 'Content'; // .'['.request()->url().']['.$url.']';
                /*
                if ($url_test = 1) {
                    $tmp->active = request()->url() == $url;
                } else {
                    $tmp->active = request()->routeIs('admin.containers.'.$act);
                }
                */
                $tmp->active = request()->url() === $url;
                if (null !== $panel->guid()) {
                    $row[] = $tmp;
                }
                // ----------------------
            }
            // */

            foreach ($tabs as $tab) {
                // dddx($tabs);
                $tmp = new \stdClass();

                if (! \is_array($tab)) {
                    // $tmp = new \stdClass();
                    $tmp->title = $tab;
                    $tmp->panel = $panel;

                    if (\in_array($act, ['index_edit', 'edit', 'update'], true)) {
                        $tab_act = 'index_edit';
                    } else {
                        $tab_act = 'index';
                    }
                    $tmp->url = $panel->relatedUrl($tab, $tab_act);
                    $tmp->active = \in_array($tab, $containers, true);
                } else {
                    //  dddx($tmp);
                    // $tmp = new \stdClass();
                    $tmp->title = $tab['title'];
                    $panel1 = $panel;
                    if (isset($tab['related'])) {
                        $panel1 = $panel1->related($tab['related']);
                    }
                    if (isset($tab['container_action'])) {
                        $tmp->url = $panel1->urlContainerAction($tab['container_action']);
                    }
                    // $tmp->url = $tab['page'];
                    $tmp->active = false;
                }
                $row[] = $tmp;
            }

            $data[] = $row;
        }
        // dddx([$data, $tabs]);

        return $data;
    }
}
