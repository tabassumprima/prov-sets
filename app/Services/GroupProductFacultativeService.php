<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\GroupFacultative;

class GroupProductFacultativeService
{
    protected $model;
    public function __construct()
    {
        $this->model = new GroupFacultative();
    }

    public function create($request)
    {
        $group = new GroupService();
        $group->verifyGroupStatus($request->group);
        $data = json_decode($request->input('data'), true);
        foreach ($data as $group) {
            $model = $this->model->updateOrCreate(['id' =>  !empty($group['groupFacultative_id']) ? $group['groupFacultative_id'] : null], [
                'product_code_id' => $group['product_key'],
                'measurement_model_id'  => $group['measurement_model_id'],
                'cohorts_code_id'       => $group['cohorts_code_id'],
                'product_grouping'      => $group['product_grouping'],
                'onerous_threshold'     => $group['onerous_threshold'],
                'portfolio_id'          => $group['portfolio_id'],
                'group_id'              => !empty($request->group) ? $request->group : null,
            ]);
        }

        return $model;
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
}
