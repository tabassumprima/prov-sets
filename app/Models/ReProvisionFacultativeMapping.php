<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ReProvisionFacultativeMapping extends Model
{
    use HasFactory, Loggable;
    
    protected $fillable= [
        'system_department_id',
        'provision_setting_id',
        'organization_id',
        'product_code_id',
        'risk_adjustments_id',
        'ibnr_assumptions_id',
        'claim_patterns_id',
        'expense_allocation',
        'discount_rates_id',
        'earning_pattern',
        're_recovery_ratio'
        ];

        public function organization()
        {
            return $this->belongsTo(Organization::class);
        }

        public function discountRates()
        {
            return $this->belongsTo(DiscountRate::class);
        }

        public function ibnrAssumptions()
        {
            return $this->belongsTo(IbnrAssumption::class);
        }

        public function riskAdjustments()
        {
            return $this->belongsTo(RiskAdjustment::class);
        }

        public function claimPatterns()
        {
            return $this->belongsTo(claimPatterns::class);
        }
}
