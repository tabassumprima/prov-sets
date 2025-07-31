<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Setting extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['organization_id', 'options'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    protected function options(): Attribute
    {
        return Attribute::make(
            get: function($value ){
                return collect(json_decode($value, true));
            },
            set: function($value) {
                return json_encode($value);
            }

        );
    }
}
