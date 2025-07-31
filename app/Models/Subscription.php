<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Subscription extends Model
{
    use Loggable;
    
    protected $fillable = [
        'organization_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'status',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'subscription_usage')
            ->withPivot('used')
            ->withTimestamps();
    }
}
