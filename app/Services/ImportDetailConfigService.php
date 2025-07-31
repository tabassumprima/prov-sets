<?php

namespace App\Services;

use App\Models\ImportDetailConfig;
use App\Helpers\CustomHelper;
use App\Helpers\JsonToYaml;
use App\Models\Organization;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ImportDetailConfigService {

        protected $model;

        public function __construct()
        {
            $this->model = new ImportDetailConfig();
        }
        public function create($request)
        {
            $organizationService = new OrganizationService();
            $organization_id = $organizationService->getTenantOrganizationId();

            $json = $this->injectOrganizationId($request->config, $organization_id);
            $status_id = CustomHelper::fetchStatus('not-started', 'import');
            // Create entry for import detail table
            $importDetail = $this->createImportDetail($organization_id, $status_id);
            // Save json to s3
            $file = $this->createAndSaveConfig($json, $importDetail->id, $organization_id);
            // Merge remaing columns
            $request->merge(['import_detail_id' => $importDetail->id, 'created_by' => Auth::user()->id, 'path' => $file]);
            // Create entry for import detail config
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
            return $this->model->with(['user', 'importDetail'])->get();
        }

        public function createImportDetail($organization_id, $status_id)
        {
            $importDetailService = new ImportDetailService();
            $payload = $importDetailService->generateArray('import', 'db-import', 'import.not-started', $organization_id);
            $importDetail = $importDetailService->create($payload);
            return $importDetail;
        }

        public function createAndSaveConfig($json, $import_id, $organization_id)
        {
            $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'import_config');
            $fileName = 'config_'.$import_id.'.json';
            Storage::disk('s3')->put($organizationStorage.$fileName   , json_encode($json));
            return $fileName;
        }

        public function injectOrganizationId($json, $organization_id)
        {
            $json = json_decode($json,true);
            $json['args']['organization'] = $organization_id;
            return $json;

        }

        public function fetchConfig($path, $organization_id)
        {
            $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'import_config');
            $fileName = $path;
            // dd($organizationStorage.$fileName);
            return Storage::disk('s3')->get($organizationStorage.$fileName);

        }

        public function import($import_id)
        {
            $status_id = CustomHelper::fetchStatus('started', 'import');
            return $this->model->findOrFail($import_id)->importDetail()->update(['status_id'=> $status_id]);
        }
}
