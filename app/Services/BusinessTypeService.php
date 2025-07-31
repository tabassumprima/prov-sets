<?php

namespace App\Services;

use App\Models\BusinessType;

class BusinessTypeService
{

    protected $model;

    public function __construct()
    {
        $this->model = new BusinessType();
    }

    public function getId($type)
    {
        return $this->model->where('type', $type)->first()->id;
    }

    public function fetch($id)
    {
        $this->model = $this->model->findOrFail($id);
        return $this->model;
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetchAllWithColumns($columns)
    {
        return $this->model->select($columns)->get();
    }
}
