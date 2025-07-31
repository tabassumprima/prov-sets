<?php

namespace App\Models;

use App\Traits\{SubscriptionUsageManager,Loggable};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Organization extends Model
{
    use HasFactory, SubscriptionUsageManager, Loggable;

    protected $fillable =
    [
        'name',
        'type',
        'sales_tax_number',
        'ntn_number',
        'country_id',
        'currency_id',
        'tenant_id',
        'shortcode',
        'logo',
        'isBoarding',
        'address',
        'subscription_type',
        'database_config_id',
        'timezone',
        'date_format',
        'financial_year',
        'agent_config',
    ];

    //Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function database_config()
    {
        return $this->belongsTo(DatabaseConfig::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class);
    }

    public function criteria()
    {
        return $this->hasOne(Criteria::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function jsonReports()
    {
        return $this->hasMany(ReportFormatJson::class);
    }

    public function discountRates()
    {
        return $this->hasMany(DiscountRate::class);
    }

    public function activeDiscountRates()
    {
        return $this->discountRates()->active();
    }

    public function ibnrAssumptions()
    {
        return $this->hasMany(IbnrAssumption::class);
    }

    public function claimPatterns()
    {
        return $this->hasMany(ClaimPattern::class);
    }

    public function activeIbnrAssumptions()
    {
        return $this->ibnrAssumptions()->active();
    }

    public function riskAdjustments()
    {
        return $this->hasMany(RiskAdjustment::class);
    }

    public function products()
    {
        return $this->hasMany(ProductCode::class);
    }

    public function re_products_treaties()
    {
        return $this->hasMany(ReProductsTreaty::class);
    }

    public function activeRiskAdjustments()
    {
        return $this->riskAdjustments()->active();
    }

    public function activeClaimPatterns()
    {
        return $this->claimPatterns()->active();
    }

    public function systemDepartments()
    {
        return $this->hasMany(SystemDepartment::class);
    }

    public function import_details()
    {
        return $this->hasMany(ImportDetail::class);
    }

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function activePlan()
    {
        return $this->hasOne(Subscription::class)->where('status', true);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function accessTokens()
    {
        return $this->hasMany(OrganizationAccessToken::class, 'organization_id');
    }

    // Mutators
    protected function logo(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return $value;
            },
            set: function ($value) {
                if ($value) {
                    $explode = explode(".", $value->getClientOriginalName());
                    $data = file_get_contents($value);

                    $base64 = 'data:image/' . last($explode) . ';base64,' . base64_encode($data);
                    return $base64;
                }
                return $value;
            }
        );
    }
}
