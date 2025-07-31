<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class GeneralConfiguration extends Model
{
    use HasFactory, Loggable;
    
    protected $fillable = [
        'organization_id',
        'identifier',
        'file_url',
        'data',
        'status'
    ];
}
