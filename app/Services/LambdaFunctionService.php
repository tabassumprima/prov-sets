<?php

namespace App\Services;

use App\Models\LambdaFunction;

class LambdaFunctionService {

    private $model;

    public function __construct()
    {
        $this->model = new LambdaFunction();
    }

    public function create($request)
    {
        return $this->model->create($request->toArray());
    }

    public function update($data, $id)
    {
        $currency = $this->fetch($id);
        return $currency->fill($data->all())->save();
    }

    public function fetchAll()
    {
        return $this->model->with('status')->get();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchAllActive($exclude = [])
    {
        return $this->model->where('is_active', 1)->whereNotIn('id', $exclude)->get();
    }


}
