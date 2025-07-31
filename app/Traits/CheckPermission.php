<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

trait CheckPermission
{
    public function authorizePermission($permission, $view = false, $guard = null,)
    {
        $authGuard = Auth::guard($guard);
        if ($authGuard->guest()) {
            throw UnauthorizedException::notLoggedIn();
        }

        if(auth()->user()->isAdmin())
            return true;

        $userPermissions = Auth::user()->getPermissionsViaRoles()->pluck('name')->toArray();
        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (in_array($permission, $userPermissions)) {
                return true;
            }
        }
        if($view)
            return false;

        abort(403);
    }
}
