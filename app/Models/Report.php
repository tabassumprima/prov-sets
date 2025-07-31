<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Report extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['organization_id', 'result', 'type', 'is_updated', 'filters', 'collection'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
}
