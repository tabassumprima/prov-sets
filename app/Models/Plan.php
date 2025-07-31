<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Plan extends Model
{
    use Loggable;
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_in_days',
        'duration_in_text',
        'status',
    ];

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'plan_feature');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
