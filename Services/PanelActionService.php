<?php

declare(strict_types=1);

namespace Modules\Cms\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\Cms\Contracts\PanelContract;
use Modules\Cms\Models\Panels\Actions\XotBasePanelAction;

/**
 * Class PanelActionService.
 */
class PanelActionService
{
    protected PanelContract $panel;

    /**
     * PanelActionService constructor.
     */
    public function __construct(PanelContract &$panel)
    {
        $this->panel = $panel;
    }

    /**
     * @return Collection|PanelContract[]
     */
    public function getActions(string $name = null)
    {
        $panel = $this->panel;
        $filters = [];
        $name1 = 'on'.Str::studly((string) $name);
        $filters[$name1] = true;
        if (null == $name) {
            return collect($panel->actions())->map(
                function ($item) use ($panel) {
                    $item->getName();
                    $item->setPanel($panel);

                    return $item;
                }
            );
        }

        $actions = collect($panel->actions())->filter(
            function ($item) use ($filters) {
                $item->getName();
                $res = true;
                foreach ($filters as $k => $v) {
                    if (! isset($item->$k)) {
                        $item->$k = false;
                    }
                    if ($item->$k !== $v) {
                        return false;
                    }
                }

                return $res;
            }
        )->map(
            function ($item) use ($panel) {
                $item->setPanel($panel);

                return $item;
            }
        );

        return $actions;
    }

    /* DEPRECATED
     * @return Collection&iterable<PanelContract>

    public function containerActions(array $params = []) {
        $params['filters']['onContainer'] = true;

        return $this->getActions($params);
    }
    */
    /* DEPRECATED
     * @return Collection&iterable<PanelContract>

    public function itemActions(array $params = []) {
        $params['filters']['onItem'] = true;

        return $this->getActions($params);
    }
    */

    /* DEPRECATED
     * @return Collection&iterable<PanelContract>

    public function checkActions(array $params = []) {
        $params['filters']['onCheck'] = true;

        return $this->getActions($params);
    }
    */

    public function getAction(string $name): XotBasePanelAction
    {
        $action = $this->getActions()
            ->firstWhere('name', $name);
        if (null == $action) {
            // dddx(debug_backtrace());

            throw new \Exception('no Action with name ['.$name.'] on
            ['.get_class($this).']
            ');
        }
        if (! $action instanceof XotBasePanelAction) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']['.\gettype($action).']');
        }

        return $action;
    }

    public function itemAction(string $act): ?XotBasePanelAction
    {
        $itemActions = $this->getActions('item');
        $itemAction = $itemActions->firstWhere('name', $act);
        /*
        if (! is_object($itemAction)) {
            dddx([
                'error' => 'nessuna azione con questo nome',
                'act' => $act,
                'this' => $this,
                'itemActions' => $itemActions,
            ]);
        }
        //$itemAction->setPanel($this); //incerto dovrebbe farlo getActions
        */
        if (null === $itemAction) {
            throw new \Exception('['.$act.'] is not an ItemAction of ['.class_basename($this->panel).']');
        }
        if (! $itemAction instanceof XotBasePanelAction) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $itemAction;
    }

    public function containerAction(string $act): ?XotBasePanelAction
    {
        $actions = $this->getActions('container');
        $action = $actions->firstWhere('name', $act);
        if (! \is_object($action)) {
            dddx(
                [
                    'error' => 'nessuna azione con questo nome',
                    'act' => $act,
                    'this' => $this,
                    'Container Actions' => $actions,
                    'panel' => $this->panel,
                    'All Actions' => $this->panel->actions(),
                ]
            );
        }
        // $action->setPanel($this);
        if (! $action instanceof XotBasePanelAction) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }

        return $action;
    }

    public function urlContainerAction(string $act, array $params = []): string
    {
        // $containerActions = $this->getActions('container');
        // $containerAction = $containerActions->firstWhere('name', $act);
        $containerAction = $this->containerAction($act);
        // 123    Call to an undefined method object::urlContainer().
        if (\is_object($containerAction) /* && $containerAction instanceof XotBasePanelAction */) {
            return $containerAction->urlContainer();
        }

        return '#';
    }

    public function urlItemAction(string $act, array $params = []): string
    {
        $itemAction = $this->itemAction($act);
        if (\is_object($itemAction)) {
            return $itemAction->urlItem();
        }

        return '#';
    }

    /* -- deprecated
     * @return mixed

    public function btnItemAction(string $act, array $params = [])
    {
        $itemAction = $this->itemAction($act);
        if (\is_object($itemAction)) {
            // return $itemAction->btn(['row' => $this->panel->getRow(), 'panel' => $this->panel]);
            return $itemAction->btn($params);
        }
    }
    */
}
