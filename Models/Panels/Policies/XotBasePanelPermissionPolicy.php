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
abstract class XotBasePanelPermissionPolicy {
    use HandlesAuthorization;

    /**
     * @param UserContract $user
     * @param string       $ability
     *
     * @return bool|null
     */
    // *
    public function before($user, $ability) {
        // *

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

            $profile = ProfileService::make()->get($user);

            return $profile->hasArea($module_name);
        }

        return true;
    }

    public function index(?UserContract $user, PanelContract $panel): bool {
        $permission = $panel->getPath().'-'.__FUNCTION__;
        if (null == $user) {
            return false;
        }
        $profile = ProfileService::make()->get($user);
        // $profile->givePermissionTo($permission);

        return $profile->hasPermissionTo($permission);
    }

    // public function show(?UserContract $user, PanelContract $panel): bool {
    //     return true;
    // }

    public function show(?UserContract $user, PanelContract $panel): bool {
        if (null == $user) {
            return false;
        }

        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function create(UserContract $user, PanelContract $panel): bool {
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function edit(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function update(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        $profile = ProfileService::make()->get($user);
        $permission = $panel->getPath().'-'.__FUNCTION__;
        // $profile->givePermissionTo($permission);

        return $profile->hasPermissionTo($permission);
    }

    public function store(UserContract $user, PanelContract $panel): bool {
        /*
        return $panel->isRevisionBy($user);
        non e' stato creato.. percio' sempre false
        */
        // return true;
        $profile = ProfileService::make()->get($user);
        $permission = $panel->getPath().'-'.__FUNCTION__;
        // $profile->givePermissionTo($permission);
        return $profile->hasPermissionTo($permission);
    }

    public function indexAttach(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function indexEdit(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    // test delle tabs
    public function index_edit(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function updateTranslate(UserContract $user, PanelContract $panel): bool {
        // return false; // update-translate di @can()
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function destroy(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function delete(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function restore(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function forceDelete(UserContract $user, PanelContract $panel): bool {
        // return false;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function detach(UserContract $user, PanelContract $panel): bool {
        // return $panel->isRevisionBy($user);
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function clone(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    /**
     * Determine whether the user can view any DocDummyPluralModel.
     */
    public function viewAny(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }

    public function view(UserContract $user, PanelContract $panel): bool {
        // return true;
        return ProfileService::make()->get($user)->hasPermissionTo($panel->getPath().'-'.__FUNCTION__);
    }
}
