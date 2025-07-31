<?php

namespace App\Models;
use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\CustomHelper;

class ExpenseAllocation extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'gl_code_id',
        'expense_type',
        'allocation_basis',
        'allocation_rate',
        'organization_id',
        'provision_setting_id',

    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function glCode()
    {
        return $this->belongsTo(GlCode::class);
    }

    public function expenseType(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => CustomHelper::getValueByKey($value, config('constant.expense_types')),
            set: fn ($value) => CustomHelper::getKeyByValue($value, config('constant.expense_types')),
        );
    }

    public function allocationBasis(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => CustomHelper::getValueByKey($value, config('constant.allocation_basis')),
            set: fn ($value) => CustomHelper::getKeyByValue($value, config('constant.allocation_basis')),
        );
    }

}
