<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Feature extends Model
{
    use Loggable;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_feature')->withPivot('limit');
    }

    public function subscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'subscription_usage')
            ->withPivot('used')
            ->withTimestamps();
    }
}
