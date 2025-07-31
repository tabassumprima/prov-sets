<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class SystemDepartment extends Model
{
    use HasFactory, Loggable;
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
    public function portfolios()
    {
        return $this->belongsToMany(Portfolio::class)->withPivot('criteria_id');
    }

    public function journalEntries()
    {
        return $this->hasManyThrough(JournalEntry::class, Journal::class);
    }
    public function journal()
    {
        return $this->hasMany(Journal::class);
    }

    public function provisionMappings()
    {
        return $this->hasMany(ProvisionMapping::class);
    }

    public function reProvisionTreatyMappings()
    {
        return $this->hasMany(ReProvisionTreatyMapping::class);
    }

    public function reProvisionFacultativeMappings()
    {
        return $this->hasMany(ReProvisionFacultativeMapping::class);
    }

    public function importDetailConfig()
    {
        return $this->belongsTo(ImportDetailConfig::class);
    }
}
