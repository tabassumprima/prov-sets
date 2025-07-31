<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Journal extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'branch_info_id',
        'voucher_type_id',
        'accounting_year_id',
        'profit_center_id',
        'system_department_id',
        'system_narration1',
        'system_narration2',
        'business_type_id',
        'system_date',
        'system_posting_date',
        'created_by',
        'approved_by',
        'voucher_number',
        'transaction_type_id',
        'entry_type_id',
    ];

    public function journalEntries()
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function setSystemDateAttribute($value)
    {
        $this->attributes['system_date'] = Carbon::parse($value)->format(config('constant.date_format.set'));
    }

    public function accountingYear()
    {
        return $this->belongsTo(AccountingYear::class);
    }
}
