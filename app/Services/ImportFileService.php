<?php

namespace App\Services;
use App\Models\ImportFile;
use Illuminate\Support\Facades\{Storage,File};
use App\Helpers\CustomHelper;

class ImportFileService
{
    protected $model, $data;  


    // public function __construct()
    // {
    //     $this->model = new ImportFile();
    //     $this->loadJsonData();
    // }

    // private function loadJsonData()
    // {
    //     $fileName = "dependent-tables.json";
    //     $organizationService = new OrganizationService();
    //     $organizationId     = $organizationService->getAuthOrganizationId();


    //     if (app()->environment('local')) 
    //     {
    //         // Read data from local server
    //         $filePath = Storage::disk('private')->path('dependent-tables.json');
    //           // Get the content of the JSON file
    //         $this->data = json_decode(file_get_contents($filePath), true);
    //     } 
    //     else 
    //     {
    //         $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dependency');
    //         $fullName = $filePath . $fileName;
    //         $jsonData = Storage::disk('s3')->get($fullName);

    //         // Get the content of the JSON file
    //         $this->data =  json_decode($jsonData);   
    //     } 
    // }

    // public function getDependentTables($tableName)
    // {
        // Check if the table exists in the dependencies

        if (app()->environment('local')) 
        {
            if (isset($this->data['dependencies'][$tableName])) 
                return $this->data['dependencies'][$tableName];
        }
        else
        {
            if ( isset($this->data->dependencies->$tableName)) 
                return $this->data->dependencies->$tableName;
        }   
        // Return an empty array if no dependencies are found
        return [];
    // }

   
}