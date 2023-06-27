<?php

declare(strict_types=1);

namespace Modules\Cms\Models\Panels\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
// use Illuminate\Contracts\Auth\UserProvider as User;
use Modules\Cms\Contracts\PanelContract;  // da usare Facades per separazione dei moduli
use Modules\LU\Services\ProfileService;
use Modules\Xot\Contracts\UserContract;
use Modules\Xot\Facades\Profile as ProfileFacade;
use Nwidart\Modules\Facades\Module;

/**
 * Class XotBasePanelPolicy.
 */
abstract class XotBasePanelPolicy {
    use HandlesAuthorization;

    /**
     * @param UserContract $user
     * @param string       $ability
     *
     * @return bool|null
     */
    // *
    public function before($user, $ability) {
        // *--- filament non ha le policy sui panel

        if (\is_object($user)) {
            $route_params = getRouteParameters();
            $profile = ProfileService::make()->get($user);

            if (isset($route_params['module'])) {
                $module = Module::find($route_params['module']);
                $module_name = '';
                if (null !== $module) {
                    $module_name = $module->getName();
                }
                $has_area = $profile->hasArea($module_name);
                if (! $has_area) {
                    return false;
                }
                // return $has_area && $profile->isSuperAdmin();
            }
            // this means that if you're superadmin the policy will always returns "true"

            if ($profile->isSuperAdmin()) {
                return true;
            }
        }
        // */
        /*
        if (\is_object($user)) {
            $route_params = getRouteParameters();
            if (isset($route_params['module'])) {
                $module = Module::find($route_params['module']);
                $module_name = '';
                if (null != $module) {
                    $module_name = $module->getName();
                }
                $has_area = ProfileFacade::hasArea($module_name);

                return $has_area && ProfileFacade::isSuperAdmin();
            }
            if (ProfileFacade::isSuperAdmin()) {
                return true;
            }
        }*/

        return null;
    }

    // */
    /*
    public function artisan(?UserContract $user, PanelContract $panel): bool {
        return false;
    }
    */

    public function home(?UserContract $user, PanelContract $panel): bool {
        if (inAdmin() && null === $user) {
            return false;
        }

        $route_params = $panel->getRouteParams();

        if (isset($route_params['module']) && null !== $user) {
            $module = Module::find($route_params['module']);
            $module_name = '';
            if (null !== $module) {
                $module_name = $module->getName();
            }

            // $profile = ProfileService::make()->get($user);

            // return $profile->hasArea($module_name);
            $areas = \Auth::user()->areas
                ->sortBy('order_column');

            $modules = Module::getByStatus(1);
            $areas = $areas->filter(
                function ($item) use ($modules) {
                    return \in_array($item->area_define_name, array_keys($modules), true);
                }
            );

            return \is_object($areas->firstWhere('area_define_name', $module_name));
        }

        return true;
    }

    public function index(?UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function show(?UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function create(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function edit(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function update(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function store(UserContract $user, PanelContract $panel): bool {
        /*
        return $panel->isRevisionBy($user);
        non e' stato creato.. percio' sempre false
        */
        return true;
    }

    public function indexAttach(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    public function indexEdit(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    // test delle tabs
    public function index_edit(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    /**
     * @return false
     */
    public function updateTranslate(UserContract $user, PanelContract $panel): bool {
        return false; // update-translate di @can()
    }

    public function destroy(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function delete(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function restore(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function forceDelete(UserContract $user, PanelContract $panel): bool {
        return false;
    }

    public function detach(UserContract $user, PanelContract $panel): bool {
        return $panel->isRevisionBy($user);
    }

    public function clone(UserContract $user, PanelContract $panel): bool {
        return true;
    }

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     */
    public function viewAny(UserContract $user): bool {
        return true;
    }

    public function view(UserContract $user, PanelContract $panel): bool {
        return true;
    }
}
