<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LambdaFunction extends Model
{
    use HasFactory ,Loggable;

    protected $fillable = ['name', 'command', 'config', 'organization_id', 'is_active'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
    
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
