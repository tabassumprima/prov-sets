<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class ImportDetailConfig extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ['organization_id', 'path', 'import_detail_id', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function importDetail()
    {
        return $this->belongsTo(ImportDetail::class);
    }

}
