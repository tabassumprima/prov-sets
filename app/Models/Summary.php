<?php

namespace App\Models;

use App\Scopes\OrganizationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Summary extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['organization_id', 'csv_summary', 'db_summary', 'import_detail_id', 'path', 'status_id','approved_by'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrganizationScope);
    }

    public function importDetail()
    {
        return $this->belongsTo(ImportDetail::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function status(){
        return $this->belongsTo(Status::class );
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class,'approved_by');
    }
}
