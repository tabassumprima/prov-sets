<?php 

namespace App\Services;

use App\Models\Cohort;

class CohortService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Cohort();
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
