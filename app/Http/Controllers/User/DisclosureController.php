<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Disclosure\Request;
use App\Services\{AccountingYearService, BusinessTypeService, ReportService, DisclosureService};
use Illuminate\Support\Facades\Log;
use App\Traits\CheckPermission;
use Exception;

class DisclosureController extends Controller
{
    use CheckPermission;
    private $service, $router, $routerHelper;

    public function __construct(DisclosureService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-disclosure'); 

        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $businessTypeService = new BusinessTypeService();
        $businessTypes = $businessTypeService->fetchAllWithColumns(['id', 'description']);

        $reportService = new ReportService;
        $report = $reportService->fetchByType(['result', 'filters', 'is_updated'], 'disclosure');

        return view('user.disclosure.index', compact('accountingYears', 'businessTypes', 'report'));
    }

    public function download(Request $request)
    {
        try {
            return $this->service->updateExcelSheet($request);
        } catch (Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', "Something went wrong");
        }
    }
}
