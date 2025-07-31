<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ClaimPaidRegister extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'id',
        'organization_id',
        'branch_id',
        'system_department_id',
        'business_type_id',
        'document_type_id',
        'product_code_id',
        'entry_no',
        'salvage_amount',
        'sales_tax',
        'intimation_date',
        'loss_date',
        'final_tag',
        'document_reference',
        'policy_number',
        'system_posting_date',
        'claim_amount',
        'payment_date',
        'import_detail_id'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function importDetailConfig()
    {
        return $this->belongsTo(ImportDetailConfig::class);
    }
}
