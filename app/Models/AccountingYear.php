<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class AccountingYear extends Model
{
    use HasFactory, Loggable;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function importDetailConfig()
    {
        return $this->belongsTo(ImportDetailConfig::class);
    }
}
