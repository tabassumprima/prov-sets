<?php

namespace App\Services;

use App\Models\Report;
use App\Helpers\CustomHelper;

class ReportService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Report();
    }
    public function create($request)
    {
        return $this->model->create($request->all());
    }

    public function updateOrCreate($request)
    {
        return $this->model->updateOrCreate(
            ['organization_id' => $request['organization_id'], 'type' => $request['type']],
            $request
        );
    }

    public function update($data, $id)
    {
        $currency = $this->fetch($id);
        return $currency->fill($data->all())->save();
    }

    public function delete($id)
    {
        $currency = $this->fetch($id);
        return $currency->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetchByType($columns, $type)
    {
        return $this->model->select($columns)->where('type', $type)->first();
    }

    static function invalidate()
    {
        return Report::where('is_updated', true)->update([
            'is_updated' => false
        ]);
    }
}
