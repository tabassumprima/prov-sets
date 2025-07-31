<?php

namespace App\Services;

use App\Models\ProfitCenter;

class ProfitCenterService{

    protected $model;

    public function __construct()
    {
        $this->model = new ProfitCenter();
    }
    
    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchColumns($columns, $id)
    {
        return $this->model->select($columns)->where('id', $id)->first();
    }

    public function getId($code)
    {
        return $this->model->where('code', $code)->first()->id;
    }
}