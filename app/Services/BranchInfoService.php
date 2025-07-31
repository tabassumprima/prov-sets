<?php

namespace App\Services;

use App\Models\Branch;

class BranchInfoService{

    protected $model;

    public function __construct()
    {
        $this->model = new Branch();
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchAllWithColumns($columns)
    {
        return $this->model->select($columns)->get();
    }

    public function getId($description)
    {
        return ($this->model->where('description', $description)->first()->id)?? 1;
    }
}
