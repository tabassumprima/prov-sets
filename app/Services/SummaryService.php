<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\Summary;
use App\Services\{OrganizationService, ImportDetailService};
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SummaryService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Summary();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function updateStatus($summary_id)
    {
        $summary = $this->model->findOrFail($summary_id);

        // get summary status
        $summaryStatus =  $this->getSummaryStatus($summary_id);
        $pending_import_status = CustomHelper::fetchStatus('pending_import','default');

        if($summaryStatus->status_id == $pending_import_status)
        {
            $summary->importDetail->status_id = CustomHelper::fetchStatus('running','default');
            $summary->importDetail->save();
            $summary->update(['approved_by' => Auth::user()->id, 'status_id' =>  CustomHelper::fetchStatus('running','default')]);
        }

        return $summary;
    }

    public function changeStatus($id, $status)
    {
        $summary = $this->fetch($id);
        $status_id = CustomHelper::fetchStatus($status,'default');
        $summary->status_id = $status_id;
        $summary->save();
        return $summary;
    }

    public function fetchAll()
    {
        return $this->model->with('status')->get();
    }

    public function prepareInput($summary)
    {
        $organizationService = new OrganizationService();
        $tenantId = $organizationService->getAuthTenantId($summary['organization_id']);
        $starts_at = Carbon::parse($summary['starts_at'])->toDateString();
        $ends_at = Carbon::parse($summary['ends_at'])->toDateString();
        $dataPath = Str::replaceArray('?', [$tenantId, $summary['import_detail_id']], config('constant.s3_paths.data_path'));
        $input = [
            "command" => "prepare_json",
            "sub_command" => "prepare_json",
            "tenant_id"    => $tenantId,
            "prepare_json" => [
                "organization_id" => $summary['organization_id'],
                "data_path" => $dataPath,
                "start_date" => $starts_at,
                "end_date" => $ends_at,
                "summary_id" => $summary['id'],
            ],
            "payload" =>[]
        ];

        return $input;
    }

    public function prepareRollback($summary)
    {
        $organizationService = new OrganizationService();
        $tenantId = $organizationService->getAuthTenantId($summary['organization_id']);
        $dataPath = Str::replaceArray('?', [$tenantId, $summary['import_detail_id']], config('constant.s3_paths.manual_uploaded_path'));
        $input = [
            "command" => "rollback",
            "sub_command" => "import",
            "tenant_id"    => $tenantId,
            "rewrite_summary" => "True",
            "complete_import" => "True",
            "rollback" => [
                "organization_id" => $summary['organization_id'],
                "path" => $dataPath,
                "import_detail_id" => $summary['import_detail_id'],
            ],
            "payload" =>[]
        ];

        return $input;
    }

    public function getErrorFilePath($summary_id)
    {
        $summary = $this->fetch($summary_id);
        $organizationId = $summary->organization_id;

        $dataPath = CustomHelper::fetchOrganizationStorage($organizationId, 'error_path', $summary->import_detail_id, $summary_id);

        return $dataPath . 'error.csv';
    }

    public function create($data)
    {
        $model = $this->model;

        $model->import_detail_id    = $data['import_detail_id'];
        $model->starts_at           = $data['starts_at'];
        $model->ends_at             = $data['ends_at'];
        $model->organization_id     = $data['organization_id'];
        $model->csv_summary         = "{}";
        $model->path                = "";
        $model->status_id           = $data['status_id'];

        $model->save();

        return $model;
    }

    public function generateSummaryPayload($import_detail_id, $starts_at, $ends_at, $status, $organization_id = null)
    {
        list($model, $status_command) = explode('.', $status);
        $status_id = CustomHelper::fetchStatus($status_command,$model);
        $organizationService = new OrganizationService();

        return [
            'import_detail_id'  => $import_detail_id,
            'starts_at'         => $starts_at,
            'ends_at'           => $ends_at,
            'organization_id'   => $organization_id ? $organization_id : $organizationService->getAuthOrganizationId(),
            'status_id'         => $status_id
        ];
    }

    public function fetchAllStartedSummary()
    {
        $statusStart = CustomHelper::fetchStatus('started','default');
        $statusPending = CustomHelper::fetchStatus('pending','default');
        $statusRunning = CustomHelper::fetchStatus('running','default');

        return $this->model->whereIn('status_id', [$statusStart, $statusPending, $statusRunning])->get();
    }

    public function lockSummaryAndImport($summary_id)
    {
        // lock summary
        $summary = $this->fetch($summary_id);
        $status_id = CustomHelper::fetchStatus('locked','default');
        $summary->status_id = $status_id;
        $summary->approved_by = Auth::user()->id;
        $summary->save();

        // // lock import
        if($summary){
            $summary->importDetail->status_id = $status_id;
            $summary->importDetail->isLocked = true;
            $summary->importDetail->save();
        }

        return $summary;
    }

    public function updateImportStatus($summaryId, $status)
    {
        $summary = $this->fetch($summaryId);
        $statusId = CustomHelper::fetchStatus($status,'default');
        $summary->importDetail->status_id = $statusId;
        $summary->importDetail->save();
    }

    public function getSummaryStatus($summary_id)
    {
        return $this->model->where('id',$summary_id)->get()->first();
    }

    public function hasOverlappingDates($startDate, $endDate)
    {
        return $this->model
            ->where(function ($query) use ($startDate, $endDate) {
            $query->whereBetween('starts_at', [$startDate, $endDate])
                  ->orWhereBetween('ends_at', [$startDate, $endDate])
                  ->orWhere(function ($query) use ($startDate, $endDate) {
                  $query->where('starts_at', '<=', $startDate)
                    ->where('ends_at', '>=', $endDate);
                  })
                  ->orWhere(function ($query) use ($endDate) {
                  $query->where('starts_at', '>', $endDate);
                  });
            })
            ->exists();
    }

    public function getStatusBySlug($slug)
    {
        return CustomHelper::fetchStatus($slug, 'default');
    }
}
