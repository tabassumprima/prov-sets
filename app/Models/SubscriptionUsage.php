<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class SubscriptionUsage extends Model
{
    use Loggable;
    
    protected $fillable = [
        'subscription_id',
        'feature_id',
        'used',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
