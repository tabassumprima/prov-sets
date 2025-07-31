<?php 

namespace App\Services;

use App\Models\MeasurementModel;

class MeasurementModelService
{
    protected $model;

    public function __construct()
    {
        $this->model = new MeasurementModel();
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetchColumns(array $columns)
    {
        return $this->model->select($columns)->get();
    }

}