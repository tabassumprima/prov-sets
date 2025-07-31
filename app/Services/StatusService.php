<?php

namespace App\Services;

use App\Models\Status;

class StatusService
{
    protected $model;

    public function __construct()
    {
        $this->model = new Status();
    }

    public function fetchStatusByModelSlug($model,$slug)
    {
       return $this->model->where(['model' => $model, 'slug' => $slug])->first();
    }

    public function fetchStatusesByModelSlug($models = [], $slugs = [])
    {
       return $this->model->whereIn('model', $models)->whereIn('slug', $slugs)->get();
    }


}
