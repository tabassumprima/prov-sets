<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use App\Traits\Loggable;

class ChartOfAccount extends Model
{
    use HasFactory, NodeTrait, Loggable;

    protected $fillable = ['gl_code_id','organization_id', 'level_id', 'type', 'category', 'parent_id', '_lft', '_rgt' ];

    protected $casts = [
       'id' => 'string',
    ];

    public function getParentAttribute($value)
    {
        if (is_null($value)) {
            $value = "#";
        }

        return $value;
    }

    public function level()
    {
        return $this->belongsTo(Level::class);

    }

    public function glcode(){
        return $this->belongsTo(GlCode::class, 'gl_code_id');
    }

    protected function getScopeAttributes()
    {
        return [ 'organization_id' ];
    }
}
