<?php

namespace App\Services;

use App\Models\LambdaSubFunction;
use App\Helpers\CustomHelper;

class LambdaSubFunctionService {

        protected $model;

        public function __construct()
        {
            $this->model = new LambdaSubFunction();
        }
        public function create($request)
        {
            return $this->model->create($request->except(['entries', 'lambda_function_id', 'narration']));
        }

        public function update($data, $id)
        {
            $lambdaSubFunction = $this->fetchWithRelation(['lambdaEntries'], $id);
            $lambdaSubFunction->fill($data->except(['entries', 'lambda_function_id', 'narration']))->save();
            return $lambdaSubFunction;
        }

        public function delete($id)
        {
            $lambdaSubFunction = $this->fetch($id);
            $lambdaSubFunction->lambdaEntries()->delete();
            return $lambdaSubFunction->delete();
        }

        public function fetch($id)
        {
            return $this->model->findOrFail($id);
        }

        public function fetchAll()
        {
            return $this->model->all();
        }

        public function fetchAllWithRelations($relations)
        {
            return $this->model->with($relations)->get();
        }

        public function fetchWithRelation($relations, $id)
        {
            return $this->model->with($relations)->findOrFail($id);
        }

        public function syncEntries($request, $id)
        {
            $lambdaEntryService = new LambdaEntryService;

            $subFunction = $this->fetch($id);
            $subFunction->lambdaEntries()->delete();

            $lambdaEntryService->create($request, $subFunction->id);

        }
}
