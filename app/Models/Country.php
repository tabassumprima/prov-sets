<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Country extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['name', 'code', 'zone', 'offset'];

}
