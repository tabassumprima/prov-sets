<?php

namespace App\Services;

use App\Models\LambdaEntry;
use App\Helpers\CustomHelper;

class LambdaEntryService {

        protected $model;

        public function __construct()
        {
            $this->model = new LambdaEntry();
        }
        public function create($request, $sub_function_id)
        {
          
            $organizationService = new OrganizationService;
            foreach ($request->entries as $key => $entry)
            {
                $model = $this->model->create([
                    'organization_id'           => $organizationService->getTenantOrganizationId(),
                    'gl_code_id'                => $entry['gl_code_id'] ?? null,
                    'level_id'                  => $entry['level_id'] ?? null,
                    'transaction_type'          => $entry['transaction_type'],
                    'leg'                       => $key + 1,
                    'lambda_function_id'        => $request->lambda_function_id,
                    'lambda_sub_function_id'    => $sub_function_id,
                    'narration'                 => $request->narration,
                    'reverse_opening'           => isset($entry['reverse_opening']) ? $entry['reverse_opening'] : false,
                ]);
            }
            return $model;

        }

        public function update($data, $id, )
        {
            $lambdaEntry                = $this->fetch($id);
            return $lambdaEntry->fill($data->all())->save();
        }

        public function delete($id)
        {
            $lambdaEntry = $this->fetch($id);
            return $lambdaEntry->delete();
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
}
