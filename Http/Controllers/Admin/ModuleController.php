<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Modules\Cms\Http\Controllers\BaseController;
use Modules\Cms\Services\PanelService;

class ModuleController extends BaseController
{
    /**
     * ---.
     *
     * @return mixed|void
     */
    public function index(Request $request)
    {
        $panel = PanelService::make()->getRequestPanel();
        if (null === $panel) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }
        /**
         * @var string
         */
        $act = $request->_act;
        if ('' !== $act && null !== $panel) {
            // return $panel->callItemActionWithGate($request->_act);
            // return $panel->callContainerAction($request->_act);
            return $panel->callAction($act);
        }

        return $panel->out();
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function store(Request $request)
    {
        return $this->index($request);
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function home(Request $request)
    {
        $panel = PanelService::make()->getRequestPanel();
        if (null === $panel) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }
        $act = $request->input('_act', '');
        if ('' !== $act) {
            if (! \is_string($act)) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }

            return $panel->callItemActionWithGate($act);
            // return $panel->callContainerAction($request->_act);
            // return $panel->callAction($request->_act);
        }

        return $panel->out();
    }

    /**
     * ---.
     *
     * @return mixed|void
     */
    public function dashboard(Request $request)
    {
        $panel = PanelService::make()->getRequestPanel();
        if (null === $panel) {
            throw new \Exception('['.__LINE__.']['.__FILE__.']');
        }
        $act = $request->input('_act', '');
        if ('' !== $act) {
            if (! \is_string($act)) {
                throw new \Exception('['.__LINE__.']['.class_basename(__CLASS__).']');
            }

            return $panel->callItemActionWithGate($act);
            // return $panel->callContainerAction($request->_act);
            // return $panel->callAction($request->_act);
        }

        return $panel->out();
    }
}
// */
