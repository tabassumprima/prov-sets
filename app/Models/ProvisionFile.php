<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Loggable;

class ProvisionFile extends Model
{
    use HasFactory, Loggable;
    protected $fillable = ['name', 'path', 'valuation_date', 'file'];

    public function typeable()
    {
        return $this->morphTo();
    }

    public function setValuationDateAttribute($value)
    {
        $this->attributes['valuation_date'] = Carbon::parse($value)->format('Y-m-d');
    }
}
