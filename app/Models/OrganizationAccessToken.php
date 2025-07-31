<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Loggable;


class OrganizationAccessToken extends Model
{
    use HasFactory, Loggable;
    
    protected $fillable = [
        'title',
        'organization_id',
        'secret_key',
        'expires_at',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
