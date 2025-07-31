<?php

namespace App\Services;

use App\Helpers\CsvValidation;
use App\Helpers\CustomHelper;
use App\Models\IbnrAssumption;
use Illuminate\Support\Facades\Storage;

class IbnrAssumptionService
{
    protected $model, $organizationService;
    public function __construct()
    {
        $this->organizationService = new OrganizationService;
        $this->model = new IbnrAssumption();
    }
    public function create($request)
    {
        $request->merge(['organization_id' => $this->organizationService->getAuthOrganizationId()]);
        $request->merge(['status_id'       => 1]);  //TODO: change this approach
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

    public function fetchWithRelations($id, $relations = array())
    {
        return $this->model->with($relations)->findOrFail(CustomHelper::decode($id));
    }

    public function fetchAllWithStatus()
    {
        return $this->model->with('status')->withCount('files')->get();
    }

    public function fetchAllWithRelations($relations = array())
    {
        return $this->model->with($relations)->get();
    }

    public function fetchFilesById($id)
    {
        return $this->model->with('files')->findOrFail($id);
    }

    public function createFile($id, $request)
    {
        $data            = $this->fetch($id);
        $organization_id = $this->organizationService->getAuthOrganizationId();

        $fileName = CustomHelper::generateUniqueName($request->ibnr_file);
        //merge path
        $request->merge(["path" => $fileName]);
        $data->files()->create($request->all());

        $storagePath = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_files.ibnr_assumptions');

        //storing file temporary. File will be deleted after validaton
        $path = Storage::disk("private")->putFileAs($storagePath, $request->ibnr_file, $fileName);

        //Storing file to s3
        if ($this->validateCsv($path, $organization_id))
            Storage::disk('s3')->putFileAs($storagePath, $request->ibnr_file, $fileName);
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

    public function getLatestIbnrAssumption()
    {
        $result = $this->model->with('files')
            ->where('status_id', CustomHelper::fetchStatus('started'))
            ->latest()
            ->first();

        if ($result !== null && $result->files->isNotEmpty()) {
            return $result;
        }

        return null;
    }
}
