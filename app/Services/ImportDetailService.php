<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Helpers\CustomHelper;
use App\Models\ImportDetail;
use Exception;

class ImportDetailService
{
    protected $model;
    public function __construct()
    {
        $this->model = new ImportDetail();
    }

    public function fetchAllProvision()
    {
        return $this->model->with('status')->withCount('journalEntries')->where('type', 'provision')->get();
    }

    public function fetchByRelations($relations, $id)
    {
    return $this->model->with($relations)->findOrFail($id);
    }
    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function delete($id)
    {
        $provision = $this->model->findOrFail($id);

        // // extra check to verify type
        // if ($provision->type != 'provision')
        //     throw new Exception('Only provision can be deleted');

        // check if provision is locked
        if ($provision->isLocked)
            throw new Exception("Cannot delete locked provision");

        $this->deleteJournalEntries($provision);

        return $provision;

    }

    /**
     * Returns array to use in create($detail) function .
     *
     * @param string $type is used for differentiate in table. default value can be picked from constant "system_posting_type".
     * @param string $identifier is slug for differentiate in table.
     * @param string $status is used to set status of import detail. input example {model}.{slug} i.e "model.started"
     * @param string $organization_id Optional. Default is null.
     * @return array Return associative array to use in create function
     */
    public function generateArray($type, $identifier, $status, $starts_at = null, $ends_at = null, $organization_id = null)
    {
        list($model, $status_command) = explode('.', $status);
        $organizationService = new OrganizationService;
        $statusService = new StatusService;
        return [
            'organization_id' => $organization_id ? $organization_id : $organizationService->getAuthOrganizationId(),
            'type' => $type,
            'starts_at'  => $starts_at,
            'ends_at'    => $ends_at,
            'identifier' => $identifier,
            'run_by' => Auth::user()->id,
            'status_id' => $statusService->fetchStatusByModelSlug($model, $status_command)->id
        ];
    }

    public function fetchLatestDate($type)
    {
        return $this->model->select('ends_at')->where([['type', $type], ['isLocked',  1]])
            ->orderBy('ends_at', 'desc')->first()?->ends_at;
    }

    public function fetchLatestProvision($type) {
        $statusService = new StatusService;
        $approved_id = $statusService->fetchStatusByModelSlug('provision', 'approved')->id;
        return $this->model->withWhereHas('provision')->where([['type', $type], ['isLocked',  1], ['status_id', $approved_id]])
        ->orderBy('ends_at', 'desc')->first()?->provision?->valuation_date;
    }

    public function fetchStartDate($type)
    {
        return $this->model->select('starts_at')->where([['type', $type], ['is_lambda_processed', 1]])
            ->orderBy('starts_at')->first()?->starts_at;
    }

    public function create($details)
    {
        $organizationService = new OrganizationService;

        $model = $this->model;
        $model->organization_id = isset($details['organization_id']) ? $details['organization_id']:  $organizationService->getAuthOrganizationId();
        $model->type            = $details['type'];
        $model->identifier      = $details['identifier'];
        $model->status_id       = $details['status_id'];
        $model->run_by          = $details['run_by'];
        $model->starts_at       = $details['starts_at'] ? $details['starts_at'] : now()->toDateTimeString();
        $model->ends_at         = $details['ends_at']  ? $details['ends_at'] : now()->toDateTimeString();

        $model->save();
        return $model;
    }

    public function deleteJournalEntries($provision)
    {
        if ($provision->journalEntries()->exists()) {
            $provision->journalMappings()->delete();
            $provision->journalEntries()->delete();
        }

    }

    public function fetchUnapprovedImport($models, $types, $slugs = ['pending'] )
    {
        $statuses = CustomHelper::fetchStatusesByModelSlug($models, $slugs)->pluck('id');
        return $this->model->whereNull('approved_by')
            ->where(['isLocked' => 0])->whereIn('type', $types)->whereIn('status_id', $statuses)->with('runBy')->withCount('journalEntries')->get();
    }
    public function fetchUnapprovedProvisions()
    {
        // TODO: Refactor this to handle import alert
        $statuses = CustomHelper::fetchStatusesByModelSlug(['provision', 'import', 'posting', 'default'], ['pending'])->pluck('id');
        return $this->model->whereNull('approved_by')
            ->where(['isLocked' => 0, 'type' => 'provision'])->whereIn('status_id', $statuses)->with('runBy')->withCount('journalEntries')->get();
    }

    public function fetchUnapprovedEntries()
    {
        $statuses = CustomHelper::fetchStatusesByModelSlug(['provision', 'import', 'posting', 'default'], ['pending'])->pluck('id');
        return $this->model->whereNull('approved_by')
            ->where(['isLocked'  =>  0, 'type' => 'provision'])->whereIn('status_id', $statuses)->with('runBy')->withCount('journalEntries')->get();
    }

    public function fetchAllImports() {
        return $this->model->with('status')->where('type', 'import')->get();
    }

    // public function approveProvisionJournalEntries($provision_id)
    // {
    //     $provision = $this->model->findOrFail($provision_id);
    //     $journal_entry = $provision->journalEntries();
    //     if ($journal_entry->exists())
    //         $journal_entry->update(['approved_by' => Auth::user()->id]);

    //     return $provision;

    // }

    // public function approveJournalEntries($journal_id)
    // {
    //     $journal = $this->model->findOrFail($journal_id);
    //     $journal->update(['approved_by' => Auth::user()->id]);
    //     $journal->save();
    //     return $journal;
    // }

    public function lockImport($import_id)
    {
        $import = $this->model->findOrFail($import_id);
        $locked_status = CustomHelper::fetchStatus('locked','default');
        $import->status = $locked_status;
        $import->save();
    }

    public function approveEntriesByImport($import_id)
    {
        $journal = $this->model->findOrFail($import_id);
        $journal->update(['approved_by' => Auth::user()->id, 'status_id' => CustomHelper::fetchStatus('approved', 'provision')]);
        $journal->save();
        return $journal;
    }

    public function lockProvision($provision)
    {
        $provision->isLocked = true;
        $provision->save();
    }

    public function changeStatus($provision, $status)
    {
        $status_id = CustomHelper::fetchStatus($status, 'provision');
        $provision->status_id = $status_id;
        $provision->save();
    }

    public function fetchLatestImport($organization_id)
    {
        return $this->model->where(['type' => 'import', 'organization_id' => $organization_id])->latest()->first();
    }

    public function fetchStartedRecord($organization_id)
    {
        return $this->model->where(['organization_id' => $organization_id, 'status', 'Started'])->latest()->first();
    }

    // Might Affect fetchSessionProvision
    public function fetchLatestProvisionStatus($organization_id)
    {
        $types = ['provision','posting' ,'opening'];
        return $this->model->with('status')->where(['organization_id' => $organization_id])
            ->whereIn('type', $types)->first();
    }

    public function fetchRunningImport($organization_id, $types, $slugs){
        // $types = ['provision','posting' ,'opening'];
        return $this->model->with('status')->where(['organization_id' => $organization_id])
            ->whereIn('type', $types)->first();
    }

    public function fetchCount($array, $id)
    {
        return $this->model->withCount($array)->find($id);
    }

    public function fetchAll()
    {
        return $this->model->with('runBy')->with('status')->get();
    }

    public function fetchLatest()
    {
        return $this->model->latest()->first();
    }

    public function saveMessage($provision, $request)
    {
        $provision->message = $request->message;
        $provision->save();
    }

    public function fetchLatestApprovedValuation($startDate, $endDate)
    {
        return $this->model->where(['status_id', CustomHelper::fetchStatus('approved', 'provision')])
            ->whereNotNull('approved_by')->whereBetween('starts_at', [$startDate, $endDate])->latest()->first();
    }

    public function getImportStatus($import_id)
    {
        $organizationService = new OrganizationService();
        $organizationId = $organizationService->getAuthOrganizationId();
        $importDetailStatus = false;

        $approvedStatusId = CustomHelper::fetchStatus('completed', 'default');

        $importDetail = ImportDetail::with('status')
            ->where('status_id', $approvedStatusId)
            ->where('organization_id', $organizationId)
            ->where('id', $import_id)
            ->first();

        // Return true if the record is found, otherwise return false
        $importDetailStatus ? true : false;

        return response()->json(['status' => $importDetailStatus]);
    }

    function fetchRunningImportStatus($organization_id)
    {
        $statuses = [
            CustomHelper::fetchStatus('running', 'default'),
            // CustomHelper::fetchStatus('revoked', 'default')
        ];

        $runningImport = $this->model
            ->with('status')
            ->where('organization_id', $organization_id)
            ->whereIn('status_id', $statuses )
            ->where('type', 'import')
            ->get();


        return $runningImport;
    }

    public function isApprovedProvision($importDetailId)
    {
        return $this->model
            ->where('id', $importDetailId)
            ->where('status_id', CustomHelper::fetchStatus('approved', 'provision'))
            ->exists();
    }

    public function hasUnlockedImports(){
        $organizationService = new OrganizationService();
        $organizationId = $organizationService->getAuthOrganizationId();
        return $this->model
            ->where('organization_id', $organizationId)
            ->where('type', 'import')
            ->where('status_id', '!=', CustomHelper::fetchStatus('locked', 'default'))
            ->where('isLocked', 0)
            ->exists();
    }

}
