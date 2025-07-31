<?php 

namespace App\Services;

use App\Models\EndorsementType;

class EndoTypeService
{

    protected $model;

    public function __construct()
    {
        $this->model = new EndorsementType();
    }

    public function getId($type)
    {
        return $this->model->where('type', $type)->first()->id;
    }


}