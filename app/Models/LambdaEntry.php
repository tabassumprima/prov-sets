<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LambdaEntry extends Model
{
    use HasFactory, Loggable;

    protected $fillable = [
        'organization_id',
        'lambda_function_id',
        'gl_code_id',
        'leg',
        'lambda_sub_function_id',
        'level_id',
        'transaction_type',
        'narration',
        'reverse_opening'
    ];


    public function lambda()
    {
        return $this->belongsTo(LambdaFunction::class, 'lambda_function_id');
    }

    public function glcode()
    {
        return $this->belongsTo(GlCode::class, 'gl_code_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
}
