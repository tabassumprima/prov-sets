<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class GroupTreaty extends Model
{
    use Loggable;
    
    protected $table = 'group_treaty';

    protected $fillable = [
        're_products_treaty_id',
        'measurement_model_id',
        'cohorts_code_id',
        'group_id',
        'product_grouping',
        'onerous_threshold',
        'portfolio_id'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}






