<?php

namespace App\Models;

use App\Traits\{EventTrait,Loggable};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Provision extends Model
{
    use HasFactory, EventTrait, Loggable;

    protected $fillable = ['organization_id', 'payload', 'valuation_date', 'import_detail_id'];


    public function import_detail()
    {
       return $this->belongsTo(ImportDetail::class);
    }


    protected function payload() : Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return json_decode($value,true);
            },
            set: function($value)  {
                return json_encode($value);
            }
        );
    }
}
