<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Loggable;

class GroupFacultative extends Model
{
    use Loggable;
    
    protected $table = 'group_facultative';

    protected $fillable = [
        'product_code_id',
        'measurement_model_id',
        'cohorts_code_id',
        'group_id',
        'product_grouping',
        'portfolio_id',
        'onerous_threshold',
    ];

    public function productCode(): BelongsTo
    {
        return $this->belongsTo(ProductCode::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }
}
