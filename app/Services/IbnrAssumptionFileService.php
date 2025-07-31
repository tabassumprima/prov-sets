<?php

namespace App\Services;

use App\Helpers\CsvValidation;
use App\Helpers\CustomHelper;
use App\Models\ProvisionFile;
use Illuminate\Support\Facades\Storage;

class IbnrAssumptionFileService
{
    protected $model, $organizationService;
    public function __construct()
    {
        $this->organizationService = new OrganizationService;
        $this->model = new ProvisionFile();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }

    public function update($request,$id)
    {
        $existingFile  = $this->fetch($id);
        $organization_id = $this->organizationService->getAuthOrganizationId();

        if (isset($request->ibnr_file) && !empty($request->ibnr_file)) {
            // Remove the existing file from storage before updating
            $storagePath = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_files.ibnr_assumptions');
            Storage::disk('private')->delete($storagePath . '/' . $existingFile->path);

            $fileName = CustomHelper::generateUniqueName($request->ibnr_file);
            // Merge path
            $request->merge(["path" => $fileName]);

            // Store the updated file temporarily. The file will be deleted after validation
            $path = Storage::disk("private")->putFileAs($storagePath, $request->ibnr_file, $fileName);

            // Store the updated file to s3
            if ($this->validateCsv($path, $organization_id)) {     
                Storage::disk('s3')->delete($storagePath . '/' . $existingFile->path);
                Storage::disk('s3')->putFileAs($storagePath, $request->ibnr_file, $fileName);
            }
        }
        // Update the existing file with new data
        $existingFile->update($request->all());
        
    }

    public function delete($id)
    {
        $existingFile  = $this->fetch($id);

        $organization_id = $this->organizationService->getAuthOrganizationId();

        // Remove the existing file from storage
        $storagePath = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_files.ibnr_assumptions');
        Storage::disk('private')->delete($storagePath . '/' . $existingFile->path);
        Storage::disk('s3')->delete($storagePath . '/' . $existingFile->path);

        // Delete the file record from the database
        $existingFile->delete();
    }

    public function validateCsv($filePath, $organization_id)
    {
        $csv  = new CsvValidation();
        $path = Storage::disk("private")->path($filePath);
        $invalid_path     = CustomHelper::fetchOrganizationStorage($organization_id, 'organization_path');
        $rulePath         = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_rules.ibnr_assumptions');
        $validationHelper = $csv->validateCSV($path, ',', $rulePath.'/rule.json', $invalid_path);
        Storage::delete($path);                       //delete local file
        return $validationHelper;
    }
}
