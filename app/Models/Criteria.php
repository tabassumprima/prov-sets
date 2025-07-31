<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Loggable;

class Criteria extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'description',
        'applicable_to',
        'start_date',
        'organization_id',
        'end_date',
        'status_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function group()
    {
        return $this->hasMany(Group::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function groupProducts()
    {
        return $this->hasManyThrough(GroupProduct::class, Group::class);
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::parse($value)->format(config('constant.date_format.set')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::parse($value)->format(config('constant.date_format.set')) : null;
    }

    public function scopeApplicableTo($query, $type)
    {
        return $query->where('applicable_to', $type);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status_id', $status);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('status', function($q){
            $q->where('slug', 'started');
        });
    }
}
