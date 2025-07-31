<?php

namespace App\Services;

use App\Models\SubImport;
use App\Helpers\CustomHelper;
use Illuminate\Support\Facades\{Storage};

class SubImportService {

        protected $model, $data;

        public function __construct()
        {
            $this->model = new SubImport();
        }
        public function create($request, $id)
        {
            // dd($request);
            $organizationService = new OrganizationService();
            $organization_id     = $organizationService->getAuthOrganizationId();
            $importDetailService = new ImportDetailService();
            $importDetail        = $importDetailService->fetch(CustomHelper::decode($id));

            //  Rename from dropdown names
            $fileName       = $this->generateUniqueName($request->table_type);
            $storagePath    = CustomHelper::fetchOrganizationStorage($organization_id, 'manual_upload_path', CustomHelper::decode($id));

            // path inside to upload data in un process/summary

            Storage::disk('s3')->putFileAs($storagePath, $request['import_file'], $fileName);
            $file_exists = CustomHelper::checkFileExistence($storagePath, $fileName);
            // dd($file_exists);
            if($file_exists['status'] == 1){
                $importDetail->status_id = CustomHelper::fetchStatus('uploading', 'default');
                $importDetail->save();
            }

            $fileData = ['name'=>$fileName, 'path'=> $storagePath];


            return   $fileData;
        }

        public function update($data, $id)
        {
            $currency = $this->fetch($id);
            return $currency->fill($data->all())->save();
        }

        function generateUniqueName($name)
        {
            // Add a unique identifier such as current timestamp and a random number
            $uniqueName = $name . '_' . uniqid().".csv";
            return $uniqueName;
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

        public function fetchAllBy($value, $column)
        {
            return $this->model->where($column, $value)->get();
        }

        public function fetchAll()
        {
            return $this->model->all();
        }

    public function getFullPath($id)
    {
        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();
        $storagePath = CustomHelper::fetchOrganizationStorage($organization_id, 'manual_uploaded_path', $id);

        return $storagePath;
    }

    static function downloadFiles($filePath, $fileName)
    {
        $path = $filePath."/".$fileName;
        $file = Storage::disk('s3')->download($path);

        return $file;
    }

    private function loadJsonData()
    {
        $fileName = "dependent-tables.json";
        $organizationService = new OrganizationService();
        $organizationId     = $organizationService->getAuthOrganizationId();


        if (app()->environment('local')) 
        {
            // Read data from local server
            $filePath = Storage::disk('private')->path('dependent-tables.json');
              // Get the content of the JSON file
            $this->data = json_decode(file_get_contents($filePath), true);
        } 
        else 
        {
            $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dependency');
            $fullName = $filePath . $fileName;
            $jsonData = Storage::disk('s3')->get($fullName);

            // Get the content of the JSON file
            $this->data =  json_decode($jsonData);   
        } 
    }

    public function getDependentTables($tableName)
    {
        $this->loadJsonData();
        // Check if the table exists in the dependencies
        //  if you want it from local than
        if (app()->environment('local'))
        {
            if (isset($this->data[$tableName]))
                return $this->data[$tableName];
        }
        else
        {

            if ( isset($this->data->$tableName))
                return $this->data->$tableName;
        }
        // Return an empty array if no dependencies are found
        return [];
    }

    public function checkIfTablesExistForOrg($tableNames, $import_detail_id)
    {
        $organizationService = new OrganizationService();
        $organizationId     = $organizationService->getAuthOrganizationId();

        $exists =  $this->model->whereIn('table_name', $tableNames)
                    ->where('organization_id', $organizationId)
                    ->where('import_detail_id', $import_detail_id)
                    ->exists();

        // Return true if at least one of the table names exists for the given organization ID, false otherwise
        return $exists;
    }

    public function prepareRollBackPayload($sub_import_id, $import_detail_id)
    {
        $organizationService    =   new OrganizationService;
        $organization_id        =   $organizationService->getAuthOrganizationId();
        $tenant_id              =   $organizationService->getTenantId($organization_id);
        $path                   =   CustomHelper::fetchOrganizationStorage($organization_id, 'manual_uploaded_path', $import_detail_id);
        $rollBackPayload = [
            "command"       => "rollback",
            "sub_command"   => "sub_import",
            "tenant_id"     => $tenant_id,
            "payload"       => [],
            "rollback"      =>
            [
                "organization_id"   => $organization_id ,
                "sub_import_id"  => CustomHelper::decode($sub_import_id),
                "path"             => $path

            ],
        ];

        return json_encode($rollBackPayload);
    }

}
