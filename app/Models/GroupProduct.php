<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class GroupProduct extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'product_code_id',
        'portfolio_id',
        'measurement_model_id',
        'cohorts_code_id',
        'product_grouping',
        'onerous_threshold',
        'portfolio_id',
        'group_id',
    ];

    public function products()
    {
        return $this->belongsTo(ProductCode::class, "product_code_id");
    }

    public function portfolio()
    {
       return $this->belongsTo(Portfolio::class, 'portfolio_id');
    }

    public function group()
    {
       return $this->belongsTo(Group::class, 'group_id');
    }
}
