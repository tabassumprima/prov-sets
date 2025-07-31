<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Level extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ['organization_id', 'level', 'code', 'category'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function chart_of_accounts()
    {
        return $this->hasMany(ChartOfAccount::class);
    }

    public function lambda_entries()
    {
        return $this->hasMany(LambdaEntry::class);
    }
}
