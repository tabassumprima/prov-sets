<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ImportDetail extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'type',
        'identifier',
        'starts_at',
        'ends_at',
        'message',
        'status_id',
        'run_by',
        'isLocked',
        'is_lambda_processed',
        'approved_by'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    //relations
    public function journalEntries(){
        return $this->hasMany(JournalEntry::class, 'import_detail_id', 'id' );
    }

    public function runBy()
    {
        return $this->belongsTo(User::class, 'run_by');
    }

    //relations
    public function status(){
        return $this->belongsTo(Status::class );
    }

    public function accountingYear()
    {
        return $this->hasMany(AccountingYear::class);
    }

    public function branch()
    {
        return $this->hasMany(Branch::class);
    }

    public function businessType()
    {
        return $this->hasMany(BusinessType::class);
    }
    public function claimPaidRegister()
    {
        return $this->hasMany(ClaimPaidRegister::class);
    }
    public function claimPattern()
    {
        return $this->hasMany(ClaimPattern::class);
    }
    public function systemDepartment()
    {
        return $this->hasMany(SystemDepartment::class);
    }
    public function insuranceType()
    {
        return $this->hasMany(InsuranceType::class);
    }
    public function documentType()
    {
        return $this->hasMany(DocumentType::class);
    }
    public function endorsementType()
    {
        return $this->hasMany(EndorsementType::class);
    }
    public function transactionType()
    {
        return $this->hasMany(TransactionType::class);
    }
    public function productCode()
    {
        return $this->hasMany(ProductCode::class);
    }
    public function premiumRegister()
    {
        return $this->hasMany(PremiumRegister::class);
    }
    public function glCode()
    {
        return $this->hasMany(GlCode::class);
    }
    public function voucherType()
    {
        return $this->hasMany(VoucherType::class);
    }
    public function journalEntry()
    {
        return $this->hasMany(JournalEntry::class);
    }
    public function journalMappings()
    {
        return $this->hasMany(JournalMapping::class);
    }
    public function importDetailConfig()
    {
        return $this->hasOne(ImportDetailConfig::class);
    }
    public function importDetailSummary()
    {
        return $this->hasOne(Summary::class);
    }
    public function provision()
    {
        return $this->hasOne(Provision::class);
    }

    public function subImports()
    {
        return $this->hasMany(SubImport::class);
    }

    public function getStartsAtAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

    public function getEndsAtAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }

}
