<?php

namespace App\Http\Controllers\User\Report;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\BalanceTrialService;
use Exception;
use Illuminate\Http\Request;

class BalanceTrialController extends Controller
{
    private $balanceTrialService, $router, $routerHelper;

    public function __construct(BalanceTrialService $balanceTrialService)
    {
        $this->router = 'groups.index';
        $this->balanceTrialService = $balanceTrialService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request);
        // $systemDepartmentService = new SystemDepartmentService;
        // $systemDepartments = $systemDepartmentService->fetchAll();

        // $businessTypeService = new BusinessTypeService;
        // $businessTypes = $businessTypeService->fetchAll();
        // dd('s');
        // try{
        // $data  = $this->balanceTrialService->updateRecords($request);
        // }
        // catch(Exception $e)
        // {
        //     return $this->routerHelper->redirectBack(true, $e->getMessage());
        // }

        return view('user.reports.financial-position');
    }

    public function filter(Request $request)
    {
        return $this->balanceTrialService->updateRecords($request);
    }
}
