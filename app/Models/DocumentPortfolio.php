<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class DocumentPortfolio extends Model
{
    use HasFactory, Loggable;
    protected $table = 'document_portfolios';

    public function journalEntries(){
        return $this->hasManyThrough(JournalEntry::class, DocumentReference::class, 'id', 'document_reference_id');
    }
}
