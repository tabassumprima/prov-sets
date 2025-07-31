<?php

namespace App\Services;

use App\Models\Plan;

class PlanService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Plan();
    }

    public function fetchActivePlans()
    {
        return $this->model->where('status', true)->get();
    }
}
