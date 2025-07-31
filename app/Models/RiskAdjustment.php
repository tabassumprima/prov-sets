<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Loggable;

class RiskAdjustment extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ['organization_id', 'name', 'triangle_type', 'frequency', 'status_id'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function files()
    {
        return $this->morphMany(ProvisionFile::class, 'file');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('status', function($q){
            $q->where('slug', 'started');
        });
    }

    public function provisionMappings()
    {
        return $this->hasMany(ProvisionMapping::class, 'risk_adjustments_id');
    }
}
