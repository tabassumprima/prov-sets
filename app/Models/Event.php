<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Event extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'title',
        'color',
        'type',
        'starts_at',
        'ends_at',
        'description'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
}
