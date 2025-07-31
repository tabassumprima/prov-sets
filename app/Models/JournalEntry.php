<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class JournalEntry extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'voucher_number',
        'voucher_serial',
        'system_department_id',
        'gl_code_id',
        'transaction_type_id',
        'system_posting_date',
        'policy_number',
        'document_reference',
        'transaction_amount',
        'transaction_type',
        'entry_type_id',
        'created_by',
        'approved_by',
        'unique_transaction',
        'import_detail_id',
        'branch_id',
        'voucher_type_id',
        'accounting_year_id',
        'system_narration1',
        'system_narration2',
        'business_type_id',
        'system_date',
        'created_by',
        'voucher_number',
        'entry_type_id',
    ];

    // public function entryType()
    // {
    //     return $this->hasOne()
    //     return $this->hasOneThrough(EntryType::class, Journal::class,'id','id','journal_id','entry_type_id');
    // }
    protected $casts = [
        'transaction_amount' => 'double',
    ];

    public function getSystemDateAttribute($value)
    {
        return date('Y-m-d', strtotime($value));
    }
    public function glCode()
    {
        return $this->belongsTo(GlCode::class);
    }

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function chartOfAccount(){
        return $this->hasManyThrough(ChartOfAccount::class, GlCode::class);
    }

    public function journalMappings() {
        return $this->hasMany(JournalMapping::class, 'journal_entries_id');
    }

    public function importDetailConfig()
    {
        return $this->belongsTo(ImportDetailConfig::class);
    }
}
