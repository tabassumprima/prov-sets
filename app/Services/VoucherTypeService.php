<?php

namespace App\Services;

use App\Models\VoucherType;

class VoucherTypeService{

    protected $model;

    public function __construct()
    {
        $this->model = new VoucherType();
    }
    
    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getId($type)
    {
        return $this->model->where('type', $type)->first()->id;
    }
}