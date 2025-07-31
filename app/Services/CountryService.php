<?php

namespace App\Services;

use App\Models\Country;
use App\Helpers\CustomHelper;

class CountryService {

    protected $model;

    public function __construct() {
        $this->model = new Country();
    }

    public function create($request)
    {
        $timeZone = explode('|', $request->timeZone);
        $request->request->add(['zone' => head($timeZone)]);
        $request->request->add(['offset' => last($timeZone)]);
        return $this->model->create($request->except(['timeZone']));
    }

    public function update($data, $id)
    {
        $country = $this->fetch($id);
        $timeZone = explode('|', $data->timeZone);
        $data->request->add(['zone' => head($timeZone)]);
        $data->request->add(['offset' => last($timeZone)]);
        return $country->fill($data->all())->save();
    }

    public function delete($id)
    {
        $country = $this->fetch($id);
        return $country->delete();
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
