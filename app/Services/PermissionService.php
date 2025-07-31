<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService
{
    /**
     *
     * returns all Permissions
     * **/
    public function getAllPermissions($shortcode)
    {
        $permissions = Permission::all();
        // Group by Module type and module
        $groupedPermissions = $permissions->groupBy(['module_type',function ($permission) {
                return $permission->module;
            }
        ]);
        // Format data
        $result = $groupedPermissions->map(function ($modules, $moduleType) {
            $formattedModules = $modules->map(function ($permissions) {
                return $permissions->pluck('name')->toArray();
            })->toArray();
            return  $formattedModules;
        })->toArray();

        return $result;
    }

    /**
     *
     * returns user role name
     * **/
    public function fetchUserRoleName($user)
    {
        return $user->roles()->pluck('name')->first();
    }

    /**
     *
     * create default permission of newly created organization
     * **/
    public function createNewOrganizationPermissions($organization)
    {
        $permissions = config('permission.permissions');
        foreach($permissions as $moduleType => $data){
            foreach ($data as $module => $modulePermissions) {
                foreach ($modulePermissions as $permission) {
                    Permission::create(['name' => $permission, 'module' => $module, 'module_type' => $moduleType , 'organization_id' => $organization->id]);
                }
            }
        }
    }
}
