<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\Role;
use Exception;

class RoleService
{
    public function getAllRoles()
    {
        return Role::all();
    }


    /**
     *
     * returns all roles
     * **/
    public function getAllRoleNames()
    {
        return Role::all()->pluck('name');
    }

    /**
     *
     * returns all roles except admin
     * **/
    public function getAllRoleNamesExceptAdmin($shortcode = 'web')
    {
        return Role::whereNot('name', 'admin')->get()->pluck('name', 'id');
    }

    /**
     *
     * returns all roles
     * **/
    public function getAdminRoleName()
    {
        return Role::withoutGlobalScopes()->where('name', 'admin')->first()->name;
    }

    public function fetchRoleById($id)
    {
        return Role::find(CustomHelper::decode($id));
    }


    public function fetchRoleByIdWithPermission($id)
    {
        return Role::with('permissions')->find(CustomHelper::decode($id));
    }

    /**
     *
     * Add roles
     */
    public function create($request)
    {
        extract($request->toArray());
        dd($request);
        $role = Role::create(['name' => $name, 'organization_id' => $request->session()->get('org')]);
        return $role;
    }


    public function update($request, $id)
    {
        $request->mergeIfMissing(['permissions' => []]);
        extract($request->toArray());
        $role = Role::find(CustomHelper::decode($id));
        $role->name = $name;
        $role->save();
        $role->syncPermissions($permissions);
        return $role;
    }

    /**
     *
     * Create And Sync Permissions
     */
    public function createAndSyncPermissions($request, $organization_id)
    {
        extract($request->toArray());
        $data = [
            'name'              => $name,
            'organization_id'   => $organization_id,
        ];
        $role = Role::create($data);
        if (isset($permissions))
            $this->syncPermissions($role, $permissions);
        return true;
    }
    /**
     *
     * sync Roles
     */
    public function syncPermissions($role, $permissions)
    {
        return $role->syncPermissions($permissions);
    }

    /**
     *
     * returns user role name
     * **/
    function fetchUserRoleName($user)
    {
        return $user->roles()->pluck('name')->first();
    }

    /**
     *
     * Delete Roles
     */
    public function delete($id)
    {
        $decodedId = CustomHelper::decode($id);

        $role = Role::find($decodedId);
    
        if (!$role) {
            throw new Exception('Role not found');
        }
    
        $usersWithRole = $role->users()->count();
    
        if ($usersWithRole > 0) {
            throw new Exception('Cannot delete role. Users are associated with this role.');
        }
        $role->delete();
    }
}
