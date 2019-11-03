<?php

namespace Softworx\RocXolid\Http\Controllers\Traits;

use Auth;

/**
 * Enables object to restrict user access to certain actions.
 *
 * @author softworx <hello@softworx.digital>
 * @package Softworx\RocXolid
 * @version 1.0.0
 */
trait Permissionable
{
    public function userCan(string $action): bool
    {
        if (!config('rocXolid.admin.auth.check_permissions', false)) {
            return true;
        }

        $permission = sprintf('\%s.%s', get_class($this), $action);

        if ($user = Auth::guard('rocXolid')->user()) {
            if (!config('rocXolid.admin.auth.check_permissions_root', false) && $user->isRoot()) {
                return true;
            }

            foreach ($user->permissions as $extra_permission) {
                if (($extra_permission->controller_class == sprintf('\%s', static::class)) && ($extra_permission->controller_action == $action)) {
                    return true;
                } elseif (($action == 'read-only') && (($extra_permission->controller_class == sprintf('\%s', static::class)) && ($extra_permission->controller_action == 'write'))) {
                    return true;
                }
            }

            foreach ($user->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if (($permission->controller_class == sprintf('\%s', static::class)) && ($permission->controller_action == $action)) {
                        return true;
                    } elseif (($action == 'read-only') && (($permission->controller_class == sprintf('\%s', static::class)) && ($permission->controller_action == 'write'))) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
