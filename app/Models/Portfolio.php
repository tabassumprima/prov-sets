<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Portfolio extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'name',
        'shortcode',
        'type',
        'organization_id'
    ];
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }
    public function systemDepartments()
    {
        return $this->belongsToMany(SystemDepartment::class)->withTimestamps()->withPivot('criteria_id');
    }

    public function documentPortfolio(){
        return $this->hasMany(DocumentPortfolio::class);
    }

    public function journalEntries(){
        return $this->hasManyThrough(JournalEntry::class, DocumentPortfolio::class, 'portfolio_id', 'document_reference_id', 'id', 'id');
    }

    public function journal(){
        return $this->hasMany(Journal::class);
    }

    public function portfolioSystemDepartments() {
        return $this->hasMany(PortfolioSystemDepartment::class);
    }

    public function FacGroupCode() {
        return $this->hasMany(FacGroupCode::class);
    }

    public function GroupFacultative() {
        return $this->hasMany(GroupFacultative::class);
    }

    public function GroupProduct() {
        return $this->hasMany(GroupProduct::class);
    }
}
