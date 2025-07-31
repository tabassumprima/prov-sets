<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use App\Traits\Loggable;

class Group extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name', 'description', 'criteria_id', 'status_id', 'start_date', 'end_date', 'applicable_to',
    ];

    protected static function boot()
    {
        parent::boot();
        $route = Route::getCurrentRoute();
        if ($route && $status = $route->parameter('type')) {
            Group::addGlobalScope('status', function (Builder $builder) use ($status) {
                $builder->applicableTo($status);
            });
        }
    }

    public function scopeApplicableTo($query, $type)
    {
        return $query->where('applicable_to', $type);
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function treaty()
    {
        return $this->hasOne(GroupTreaty::class);
    }

    public function facultative()
    {
        return $this->hasOne(GroupFacultative::class);
    }

    public function product()
    {
        return $this->hasOne(GroupProduct::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value ? Carbon::parse($value)->format(config('constant.date_format.set')) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value ? Carbon::parse($value)->format(config('constant.date_format.set')): null;
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status_id', $status);
    }
}
