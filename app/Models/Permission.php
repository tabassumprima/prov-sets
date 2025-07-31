<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;
use Spatie\Permission\Guard;
use Spatie\Permission\Models\Permission as ModelsPermission;
use App\Traits\Loggable;

class Permission extends ModelsPermission
{
    use HasFactory, Loggable;

    protected static function booted()
    {
        static::addGlobalScope(new OrganizationScope());
    }

    // Override Spatie/permission create function to create unique permission on organization level instead of guard_name
    public static function create(array $attributes = [])
    {
        $attributes['guard_name'] = $attributes['guard_name'] ?? Guard::getDefaultName(static::class);

        $permission = static::getPermission(['name' => $attributes['name'], 'module' => $attributes['module'], 'module_type' => $attributes['module_type'], 'organization_id' => $attributes['organization_id']]);

        if ($permission) {
            throw PermissionAlreadyExists::create($attributes['name'], $attributes['module'], $attributes['module_type'], $attributes['organization_id']);
        }

        return static::query()->create($attributes);
    }
}
