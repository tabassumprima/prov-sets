<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class GroupCodePortfolio extends Model
{
    use HasFactory, Loggable;

    public function portfolios(){
        return $this->belongsTo(Portfolio::class);
    }

    public function groupCode(){
        return $this->belongsTo(GroupCode::class);
    }
}
