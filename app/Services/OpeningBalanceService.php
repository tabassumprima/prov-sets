<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\OpeningBalance;
use App\Helpers\{CustomHelper, OpeningHelper, AwsHelper};
use Exception;

class OpeningBalanceService
{
    protected $model;

    public function __construct()
    {
        $this->model = new OpeningBalance();
    }
    public function create($request)
    {
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

    // Opening balances sum
    public function fetchLedgerOpeningSum($gl_code_id, $accounting_year, $business_type_id = "All", $portfolio_id = 'All', $branch_id = 'All', $headOffice_id = null)
    {
        return $this->model->when($portfolio_id != 'All', function ($query) use ($portfolio_id, $headOffice_id) {
            $query->with(['journalMappings'=>function($query) use($portfolio_id, $headOffice_id) {
                $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
        }]);
        })->when($business_type_id != 'All', function ($query) use ($business_type_id) {
            $query->where('business_type_id', $business_type_id);
        })->when($branch_id != 'All', function ($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })
            ->where([['gl_code_id', $gl_code_id], ['accounting_year_id', $accounting_year]])->sum('balance');
    }

    public function getOpeningPayload($valuation_date, $import_detail_id, $tenant_id,  $organization_id = null)
    {
        $openingHelper = new OpeningHelper($valuation_date, $import_detail_id, $tenant_id,  $organization_id);
        $openingHelper->invokeOpening();

        return $openingHelper->getPayload();
    }

    public function invokeOpening($payload)
    {
        $start = microtime(true);
        try {
            CustomHelper::log("Job is processing");
            $lambda_response = AwsHelper::invokeoOpeningLambda($payload);
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

    public function checkEndOfYearOpeningBalance($accounting_year_end_date)
    {
        $accounting_yearService = new AccountingYearService();
        $next_accounting_year = $accounting_yearService->getNextAccountingYear($accounting_year_end_date);
        if (!$next_accounting_year) {
            return false;
        }
        $result = $this->model->withWhereHas('importDetail', function ($query){
           return $query->where('type', 'opening');

        })->where('accounting_year_id',$next_accounting_year->id)
        ->first();

        if ($result) {
            return true;  // Return the accounting_year_id if found
        }

        return null;
    }

}
