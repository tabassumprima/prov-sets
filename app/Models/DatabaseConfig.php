<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class DatabaseConfig extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['name', 'data'];
}
