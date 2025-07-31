<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LambdaSubFunction extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['command','organization_id'];

    public function lambda()
    {
        return $this->belongsTo(LambdaFunction::class);
    }

public function lambdaEntries()
    {
        return $this->hasMany(LambdaEntry::class);
    }
}
