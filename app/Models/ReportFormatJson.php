<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Traits\Loggable;

class ReportFormatJson extends Model
{
    use HasFactory, Loggable;

    protected $fillable = ["organization_id", "file_name", "type", "is_validate"];
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format(config()->get('constant.datetime_format'));
    }
}
