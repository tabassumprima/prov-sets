<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\{AccountingYearService, BranchInfoService, BusinessTypeService, EntryTypeService, GeneralLedgerService, GlCodeService, PortfolioService, SystemDepartmentService};
use App\Http\Requests\GeneralLedger\Request;
use App\Traits\CheckPermission;

class GeneralLedgerController extends Controller
{

    use CheckPermission;

    private $generalLedgerService;

    public function __construct(GeneralLedgerService $generalLedgerService)
    {
        $this->generalLedgerService = $generalLedgerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorizePermission('view-general-ledger');
        $request->validated();
        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $branchServices = new BranchInfoService();
        $branches = $branchServices->fetchAllWithColumns(['id', 'description']);

        $businessTypeService = new BusinessTypeService();
        $businessTypes = $businessTypeService->fetchAllWithColumns(['id', 'description']);

        $glCodeService = new GlCodeService();
        $glCodes = $glCodeService->fetchAllWithColumns(['id', 'code', 'description']);

        $portfolioService = new PortfolioService();
        $portfolios = $portfolioService->fetchAll();

        $entryTypeService = new EntryTypeService();
        $entryTypes = $entryTypeService->fetchAll();

        return view('user.general_ledger.index', compact('accountingYears', 'branches', 'businessTypes', 'glCodes', 'entryTypes', 'portfolios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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

    public function filter(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');

        $data = $this->generalLedgerService->queryBuilder($request, $start, $length);


        if (isset($data['error'])) {
            return response()->json([
                "draw" => $draw,
                "data" => [],
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "error" => $data['message'],
            ], 500); 
        }

        return response()->json([
            "draw"  => $draw,
            "data"  => $data['data'],
            "recordsTotal"  => $data['total'],
            "recordsFiltered"  => $data['total'],
        ]);

    }

    public function download(Request $request)
    {
        $response = $this->generalLedgerService->generateCSV($request);
        return $response;
    }
}
