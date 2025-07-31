<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\AccountingYearService;
use App\Services\JournalEntryPortfolioService;
use App\Services\PortfolioService;
use App\Services\ReportService;
use App\Services\SystemDepartmentService;
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use App\Http\Requests\JournalEntry\JournalEntryPortfolioRequest;

class JournalEntryGroupingController extends Controller
{
    use CheckPermission;

    private $JournalEntryPortfolioService, $router, $routerHelper;

    public function __construct(JournalEntryPortfolioService $JournalEntryPortfolioService)
    {
        // $this->middleware('prevent_transaction', ['except' => ['index','show']]);

        $this->router = 'groups.index';
        $this->JournalEntryPortfolioService = $JournalEntryPortfolioService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorizePermission('view-reports');
        $systemDepartmentService = new SystemDepartmentService;
        $systemDepartments = $systemDepartmentService->fetchAll();

        $porfolioService = new PortfolioService;
        $portfolios = $porfolioService->fetchAll();

        $accountingYearService = new AccountingYearService;
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $reportService = new ReportService;
        $report = $reportService->fetchByType(['result', 'filters', 'is_updated'], $request->slug);

        $reportNotAllowed = $request->session()->get('active_provision');

        return view('user.reports.pl-statement', compact('portfolios', 'report', 'accountingYears', 'reportNotAllowed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function filter(JournalEntryPortfolioRequest $request)
    {
        return $this->JournalEntryPortfolioService->fetchDocumentReferenceByPortfolioIds($request);
    }

    public function exportCSV(JournalEntryPortfolioRequest $request)
    {
        return $this->JournalEntryPortfolioService->generateCSV($request);
    }

    public function fetchAccountingYear($accountingYear)
    {
        $accountingYearService = new AccountingYearService;
        return $accountingYearService->fetch($accountingYear);
    }

    public function report()
    {
        $this->authorizePermission('view-reports');
        return view('user.reports.index');
    }
}
