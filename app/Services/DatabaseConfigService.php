<?php

namespace App\Services;

use App\Models\DatabaseConfig;
use App\Helpers\CustomHelper;

class DatabaseConfigService {

    protected $model;

    public function __construct()
    {
        $this->model = new DatabaseConfig();
    }

    public function create($request)
    {
        return $this->model->create($request->all());
    }

    public function update($data, $id)
    {
        $databaseConfig = $this->fetch($id);
        return $databaseConfig->fill($data->all())->save();
    }

    public function delete($id)
    {
        $databaseConfig = $this->fetch($id);
        return $databaseConfig->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }



    public function fetchAll()
    {
        return $this->model->all();
    }
}
