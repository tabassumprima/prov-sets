<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;


class ProductCode extends Model
{
    use HasFactory, Loggable;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
    // protected $table = 'product_informations';

    public function systemDepartments()
    {
        return $this->belongsTo(SystemDepartment::class, 'system_department_id');
    }

    public function organizations()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function businessTypes()
    {
        return $this->belongsTo(BusinessType::class, 'business_type_id');
    }

    public function group()
    {
        return $this->hasMany(Group::class);
    }

    public function cohortsCode()
    {
        return $this->belongsTo(CohortsCode::class, 'cohorts_code_id');
    }

    public function measurementModel()
    {
        return $this->belongsTo(MeasurementModel::class, 'measurement_model_id');
    }

    public function groupProducts()
    {
        return $this->hasMany(GroupProduct::class);
    }

    public function groupFacultative()
    {
        return $this->hasMany(GroupFacultative::class);
    }
}
