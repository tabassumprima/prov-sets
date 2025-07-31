<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class JournalMapping extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'journal_entries_id',
        'portfolio_id',
        'group_code_id',
        'treaty_group_code_id',
        'fac_group_code_id',
        'import_detail_id'
    ];

    public function JournalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    public function portfolio()
    {
        return $this->belongsTo(Portfolio::class);
    }
}
