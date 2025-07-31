<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class OpeningBalanceMapping extends Model
{
    use HasFactory, Loggable;

    public function opening_balance()
    {
        return $this->belongsTo(OpeningBalance::class);
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
