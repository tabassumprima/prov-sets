<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\GroupTreaty;

class GroupProductTreatyService
{
    protected $model;
    public function __construct()
    {
        $this->model = new GroupTreaty();
    }

    public function create($request)
    {
        $group = new GroupService();
        $group->verifyGroupStatus($request->group);
        $data = json_decode($request->input('data'), true);
        foreach ($data as $group) {
            $model = $this->model->updateOrCreate(['id' => !empty($group['groupTreaty_id']) ? $group['groupTreaty_id'] : null ], [
                're_products_treaty_id' => $group['re_product_key'],
                'measurement_model_id'  => $group['measurement_model_id'],
                'cohorts_code_id'       => $group['cohorts_code_id'],
                'product_grouping'      => $group['product_grouping'],
                'portfolio_id'          => $group['portfolio_id'],
                'onerous_threshold'     => $group['onerous_threshold'],
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
