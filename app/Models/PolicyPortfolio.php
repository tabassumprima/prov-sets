<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class PolicyPortfolio extends Model
{
    use HasFactory, Loggable;


    public function portfolios(){
        return $this->belongsTo(Portfolio::class);
    }

    public function policy(){
        return $this->belongsTo(Policy::class);
    }
}
