<?php

namespace App\Services;

use Illuminate\Support\Facades\{Log, Storage};
use App\Helpers\{ProvisionHelper, CustomHelper, AwsHelper};
use App\Models\Provision;
use Illuminate\Support\Facades\DB;
use Exception;

class ProvisionService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Provision();
    }

    public function create($request)
    {
        return $this->model->create([
            'organization_id' => $request->get('organization_id'),
            'payload' => $request->get('payload'),
            'valuation_date' => $request->get('valuation_date'),
            'import_detail_id' => $request->get('import_detail_id')
        ]);
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

    public function fetchAllLatestProvision()
    {
        return $this->model->withWhereHas('import_detail', function ($query) {
            $query->where('type', 'provision')
                ->whereIn('status_id', [CustomHelper::fetchStatus('pending', 'default'), CustomHelper::fetchStatus('approved', 'provision')]);
        })->distinct('valuation_date')->select('valuation_date', 'id', 'created_at', 'import_detail_id')->latest('valuation_date')->latest('id')->get();

    }

    public function listFiles($import_detail_id)
    {
        $organization_service = new OrganizationService;
        $organization_id = $organization_service->getAuthOrganizationId();
        $path = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_files.output') . CustomHelper::decode($import_detail_id) . '/calculation_output/';
        return Storage::disk('s3')->files($path);
    }

    public function getProvisionPayload($valuation_date, $import_detail_id, $tenant_id,  $organization_id = null)
    {
        $provisionHelper = new ProvisionHelper($valuation_date, $import_detail_id, $tenant_id,  $organization_id);
        $provisionHelper->invokeProvision();
        return $provisionHelper;
    }

    public function invokeProvision($payload)
    {
        $start = microtime(true);
        try {
            CustomHelper::log("Job is processing");

            $lambda_response = AwsHelper::invokeLambda($payload->get('payload'), 'delta-import-test');
            CustomHelper::log($lambda_response);
            $response = json_decode($lambda_response['Payload']->getContents(), true);

            //Looks For Errors In Response
            if ($lambda_response['FunctionError'] != "" || $response['statusCode'] != 200)
                throw new Exception($response['errorMessage'], 500);

        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        $time_elapsed_secs = microtime(true) - $start;
        CustomHelper::log("Job finished in " . $time_elapsed_secs . " seconds");
    }

    public function downloadFile($import_detail_id, $filename)
    {
        $organization_service = new OrganizationService;
        $organization_id = $organization_service->getAuthOrganizationId();
        $path = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_files.output') . CustomHelper::decode($import_detail_id) . '/calculation_output/';
        return Storage::disk('s3')->download($path . $filename);
    }

    public function fetchLatestApprovedValuation($startDate, $endDate)
    {
        return $this->model->whereHas('import_detail', function ($query) {
            $query->where('status_id', CustomHelper::fetchStatus('approved', 'provision'));
        })->whereBetween('valuation_date', [$startDate, $endDate])->latest()->first();
    }

    public function findMatchValuationDate()
    {
        $latestProvision = $this->model->withWhereHas('import_detail', function ($query) {
            $query->where(['status_id' => CustomHelper::fetchStatus('approved', 'provision'), 'isLocked' => 1]);
        })->distinct('valuation_date')->select('valuation_date', 'id', 'import_detail_id')->latest('valuation_date')->latest('id')->first();

        if ($latestProvision) {
                $accountingYearService = new AccountingYearService;
                $accountingYearId = $accountingYearService->getIdByEndDate($latestProvision->valuation_date);

                return $accountingYearId ? [
                    'valuation_date' => $latestProvision->valuation_date,
                    'accounting_year_id' => $accountingYearId,
                    'end_date' => $latestProvision->valuation_date,
                ] : null;
        }
        return null;
    }

    public function lockProvisionInvoke($accounting_year_end_date)
    {
        $openingService =  new OpeningBalanceService();
         return $openingService->checkEndOfYearOpeningBalance($accounting_year_end_date);

    }

    public function getFilePath($organization_id, $module, $type = null )
    {   
        $type = $type ? ".".$type : null;
        $filePath = CustomHelper::fetchOrganizationStorage($organization_id, $module . $type);
        if($type) 
            $fullPath = $filePath . 'rule.json'; 
        else 
            $fullPath = $filePath . 'new_graph.json';
        return $fullPath;
    }
}
