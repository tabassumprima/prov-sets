<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class OpeningBalance extends Model
{
    use HasFactory, Loggable;

    public function openingBalanceMappings()
    {
        return $this->hasMany(OpeningBalanceMapping::class);
    }

    public function importDetail()
    {
        return $this->belongsTo(ImportDetail::class);
    }
}
