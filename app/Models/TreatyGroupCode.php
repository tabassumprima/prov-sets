<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class TreatyGroupCode extends Model
{
    use HasFactory, Loggable;

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }

}
