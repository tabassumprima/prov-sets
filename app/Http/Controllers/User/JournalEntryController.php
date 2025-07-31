<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{VoucherTypeService, ProfitCenterService, JournalEntryService, GlCodeService,  BusinessTypeService, BranchInfoService, AccountingYearService, ChartOfAccountService, CriteriaService, FacGroupCodeService, GroupCodeService, ImportDetailService, PortfolioService, SettingService, SystemDepartmentService, TreatyGroupCodeService};
use App\Http\Requests\JournalEntry\JournalEntryRequest;
use Illuminate\Support\Facades\Log;
use App\Traits\CheckPermission;
use Exception;
use Illuminate\Http\Request;

class JournalEntryController extends Controller
{
    use CheckPermission;
    private $journalEntryService, $router, $routerHelper;

    public function __construct(JournalEntryService $journalEntryService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index','show']]);

        $this->router              = 'journal-entries.index';
        $this->journalEntryService = $journalEntryService;
        $this->routerHelper        = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-journal-entry');
        $import_detail_service = new ImportDetailService();
        $import_details = $import_detail_service->fetchUnapprovedEntries();
        return view('user.journal_entry.index', compact('import_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorizePermission('create-journal-entry');
        $voucherTypeService = new VoucherTypeService();
        $voucherTypes = $voucherTypeService->fetchAll();

        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $branchInfoService = new BranchInfoService();
        $branchInfos = $branchInfoService->fetchAll();

        $businessTypesService = new BusinessTypeService();
        $businessTypes = $businessTypesService->fetchAll();

        // $systemDepartmentService = new SystemDepartmentService();
        // $systemDepartments = $systemDepartmentService->fetchAll();

        $GlCodeService = new GlCodeService();
        $glCodes = $GlCodeService->fetchAllWithRelation('chartOfAccount');

        return view('user.journal_entry.create', compact('voucherTypes', 'accountingYears', 'branchInfos', 'businessTypes', 'glCodes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JournalEntryRequest $request)
    {
        $import_detail_service = new ImportDetailService;
        $error = false;
        $message = trans('user/journal_entry.approved');
        try {
            $import_detail_params   = $import_detail_service->generateArray(config('constant.system_posting_type'), 'manual', 'posting.pending');
            $import_detail          = $import_detail_service->create($import_detail_params);
            $this->journalEntryService->create($request, $import_detail->id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message)->withInput();
        return $this->routerHelper->redirect('import-detail.index', $error, $message);
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
    public function update(JournalEntryRequest $request, $id)
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
        $this->authorizePermission('delete-journal-entry');
        $error = false;
        $message = trans('user/journal_entry.deleted');
        try{
            $this->journalEntryService->delete($id);
        }
        catch(\Exception $e)
        {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function approve($id)
    {
        $this->authorizePermission('approve-journal-entry');

        $error = false;
        $message = trans('user/journal_entry.approved');
        try {
            $this->journalEntryService->approve($id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);

        }
        if ($error)
            session()->flash('error', $message);
        session()->flash('success', $message);
        return response()->json();
    }

    public function getPortfolio(Request $request)
    {
        $criteriaService = new PortfolioService;
        return $criteriaService->fetchPortfoliosByDate($request);
    }

    public function getGroup(Request $request)
    {
        $chart_of_account_service = new ChartOfAccountService;
        $chart_of_account  = $chart_of_account_service->getCategory($request->gl_code_id);
        // dd($chart_of_account, $request->all());
        if ($chart_of_account->category == 'insurance')
        {
            $groupCodeService = new GroupCodeService;
            $group_code =  $groupCodeService->fetchByColumns($request->portfolio, ['id', 'group_code']);
        }
        elseif ($chart_of_account->category == 'fac')
        {
            $facReinsuranceService = new FacGroupCodeService;
            $group_code = $facReinsuranceService->fetchByColumns($request->portfolio, ['id', 'group_code']);
        }
        elseif ($chart_of_account->category == 'treaty')
        {
            $treatyReinsuranceService = new TreatyGroupCodeService;
            $group_code =  $treatyReinsuranceService->fetchByColumns($request->portfolio, ['id', 'group_code'] );

        }
        elseif ($chart_of_account->category == 'headoffice')
        {

            $group_code = [];

        }
        $collection = collect(['items' => $group_code]);
        $collection =  $collection->merge(['type' => $chart_of_account->category]);
        return $collection;
    }

    public function getDepartments(Request $request)
    {
        $settingService = new SettingService;
        $head_office  = $settingService->getOption('headoffice_portfolio_id');
        if ($request->type == 'headoffice')
        {
            $system_departmentService = new SystemDepartmentService;
            $system_departments = $system_departmentService->fetchSystemDepartmentsByPortfolio($head_office);
        }
        else
        {
            $system_departmentService = new SystemDepartmentService;
            $system_departments = $system_departmentService->fetchSystemDepartmentsByPortfolio($head_office, ['*'], true);
        }

        $collection = collect(['items' => $system_departments]);
        $collection =  $collection->merge(['type' => $request->type]);
        return $collection;
    }
}
