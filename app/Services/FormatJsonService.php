<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\ReportFormatJson;
use Illuminate\Support\Facades\{Auth, Storage};

class FormatJsonService
{
    protected $model, $organizationService;
    public function __construct()
    {
        $this->model = new ReportFormatJson();
        $this->organizationService = new OrganizationService();
    }

    public function create($request)
    {
        extract($request->toArray());
        $organizationId = $this->organizationService->getTenantOrganizationId();
        $fileName = $this->generateJsonName($report_format_file);
        $model = $this->model;
        $model->organization_id = $organizationId;
        $model->file_name       = $fileName;
        $model->type            = $report_type;
        $model->is_validate     = true; //because dls are now fixed so reports cannot be invalidated
        $model->save();

        $storagePath = CustomHelper::fetchOrganizationStorage($organizationId, 'report_type.' . strtolower($report_type));

        Storage::disk('s3')->putFileAs($storagePath, $report_format_file, $fileName);

        return $model;
    }

    public function generateJsonName($report_format_file)
    {
        return CustomHelper::generateUniqueName($report_format_file);
    }

    public function fetchAll()
    {
        $organizationId = $this->organizationService->getTenantOrganizationId();
        return $this->model->where('organization_id',$organizationId)->get();
    }

    public function fetchAuthOrganizationReport()
    {
        $organization = Auth::user()->organization_id;
        return $this->model->where('organization_id', $organization)->get();
    }

    public function delete($id)
    {
        $model = $this->fetch($id);
        return $model->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }

    public function fetchWithRelation($id, $relations = array())
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    public function invalidateReport($id)
    {
        $organizationService = new OrganizationService();
        $organization = $organizationService->fetch(CustomHelper::encode($id));
        $organization->jsonReports()->update(['is_validate' => false]);
    }

    public function initCreate($organization_id)
    {
        $default_reports     = ['PNL' => '/PNL.json', 'BS' => '/BS.json',
                                'SOP' => '/SOP.json', 'SOC' => '/SOC.json','SOE' => '/SOE.json'];

        foreach($default_reports as $key => $report){
            $adminStorage        = CustomHelper::fetchAdminStorage('report_type.'.strtolower($key));
            $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'report_type.' .strtolower($key));
            $this->model->create([
                'file_name' => $report,
                'type' => $key,
                'organization_id' => $organization_id,
                'is_validate'  => true,
            ]);
            Storage::disk('s3')->copy($adminStorage.$report,$organizationStorage.$report);
        }
    }

    public function fetchLatestReportFile($type)
    {
        $organizationService = new OrganizationService();
        return $this->model->where(['type'=> $type, 'organization_id'=> $organizationService->getAuthOrganizationId()])->latest()->first();
    }

    public function fetchTenantLatestReportFile($type)
    {
        $organizationService = new OrganizationService();
        return $this->model->where(['type'=> $type, 'organization_id'=> $organizationService->getTenantOrganizationId()])->latest()->first();
    }

    public function downloadFile($id)
    {
        $filename = $this->fetch($id);
        $type = $filename->type;

        $organizationService = new OrganizationService();
        
        $organizationId      = $organizationService->getTenantOrganizationId();
        
        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'report_type.'.strtolower($type));
        
        $path = $filePath.'/'.$filename->file_name;
        
        $file     = Storage::disk('s3')->download($path);
        
        return $file;
        
    }
}
