<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class GlCode extends Model
{
    use HasFactory, Loggable;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function chartOfAccount(){
        return $this->hasOne(ChartOfAccount::class);
    }

    public function journalEntries(){
        return $this->hasMany(JournalEntry::class);
    }

    public function openingBalances()
    {
        return $this->hasMany(OpeningBalance::class);
    }

    public function importDetailConfig()
    {
        return $this->belongsTo(ImportDetailConfig::class);
    }

    public function expenseAllocation()
    {
        return $this->hasOne(ExpenseAllocation::class);
    }
}
