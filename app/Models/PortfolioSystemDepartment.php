<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class PortfolioSystemDepartment extends Model
{
    use HasFactory, Loggable;
    protected $table = "portfolio_system_department";
    public function systemDepartment(){
        return $this->belongsTo(SystemDepartment::class);
    }

    public function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }

    public function criteria() {
        return $this->belongsTo(Criteria::class);
    }
}
