<?php

namespace App\Services;

use App\Models\GroupCode;
use App\Helpers\CustomHelper;

class GroupCodeService {

        protected $model;

        public function __construct()
        {
            $this->model = new GroupCode();
        }
        public function create($request)
        {
            return $this->model->create($request->all());
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

        public function fetchByColumns($portfolio, $columns = ["*"])
        {
            return $this->model->select($columns)->where('portfolio_id', $portfolio)->get();
        }
}
