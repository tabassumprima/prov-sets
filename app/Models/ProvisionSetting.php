<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ProvisionSetting extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['name', 'organization_id', 'description', 'status_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function mappings()
    {
        return $this->hasMany(ProvisionMapping::class);
    }

    public function reProvisionTreatyMappings()
    {
        return $this->hasMany(ReProvisionTreatyMapping::class);
    }

    public function reProvisionFacultativeMappings()
    {
        return $this->hasMany(ReProvisionFacultativeMapping::class);
    }

    public function ExpenseAllocations()
    {
        return $this->hasMany(ExpenseAllocation::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('status', function($q){
            $q->where('slug', 'started');
        });
    }
}
