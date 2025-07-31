<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrialBalance\Request;
use App\Services\{AccountingYearService, BranchInfoService, BusinessTypeService, PortfolioService, ReportService, TrialBalanceService, SystemDepartmentService};
use App\Traits\CheckPermission;

class TrialBalanceController extends Controller
{
    use CheckPermission;
    private $trialBalance, $router, $routerHelper;

    public function __construct(TrialBalanceService $trialBalance)
    {
        $this->trialBalance = $trialBalance;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-trial-balance');

        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $branchServices = new BranchInfoService();
        $branches = $branchServices->fetchAllWithColumns(['id', 'description']);

        $businessTypeService = new BusinessTypeService();
        $businessTypes = $businessTypeService->fetchAllWithColumns(['id', 'description']);

        $systemDepartmentService = new SystemDepartmentService();
        $systemDepartments = $systemDepartmentService->fetchAll();

        $porfolio_service = new PortfolioService();
        $porfolios = $porfolio_service->fetchAll();

        $reportService = new ReportService;
        $report = $reportService->fetchByType(['result', 'filters', 'is_updated'], 'trial-balance');

        return view('user.trial_balance.index', compact('accountingYears', 'branches', 'businessTypes', 'porfolios', 'report'));
    }


    public function filter(Request $request)
    {
        $trials =  $this->trialBalance->queryBuilder($request);
        return $trials;
    }

    public function download(Request $request)
    {
        $response = $this->trialBalance->generateCSV($request);
        return $response;
    }
}
