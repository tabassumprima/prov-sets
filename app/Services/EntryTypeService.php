<?php

namespace App\Services;

use App\Models\EntryType;

class EntryTypeService
{

    protected $model;

    public function __construct()
    {
        $this->model = new EntryType();
    }

    public function fetchAll()
    {
       return $this->model->all();
    }

    public function getId($value, $column= 'description')
    {
        return $this->model->where($column, $value)->first()->id;
    }

    public function fetchByType($type)
    {
        return $this->model->where('type', $type)->first();
    }


}
