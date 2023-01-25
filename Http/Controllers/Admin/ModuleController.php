<?php

declare(strict_types=1);

namespace Modules\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Cms\Services\PanelService;

class ModuleController extends Controller
{
    /**
     * @return mixed
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
     * Undocumented function.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Undocumented function.
     *
     * @return mixed
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
     * Undocumented function.
     *
     * @return mixed
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
