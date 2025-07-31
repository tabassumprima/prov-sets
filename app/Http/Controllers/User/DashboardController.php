<?php

namespace App\Http\Controllers\User;

use App\Services\{ImportDetailService, JournalEntryPortfolioService, BranchInfoService, BusinessTypeService, CurrencyService, GroupService, PortfolioService, OpeningBalanceService};
use App\Services\{OrganizationService, ProvisionService, ProvisionSettingService, AccountingYearService, ReportService, SettingService};
use Illuminate\Support\Facades\{Session, Storage};
use App\Http\Requests\Provision\Request;
use App\Http\Controllers\Controller;
use App\Helpers\CustomHelper;
use App\Models\OpeningBalance;
use Illuminate\Support\Carbon;
use App\Models\ProvisionFile;

use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organizationService = new OrganizationService();

        $importDetailService = new ImportDetailService();
        $lastSync = Carbon::parse($importDetailService->fetchLatestDate('import'));
        $lastProvision = Carbon::parse($importDetailService->fetchLatestDate('provision'));
        if ($organizationService->isBoarding() && !(Session::has('active_provision') || Session::has('provision_alert')))
            $provisionAllowed = true;
        else if (Session::has('active_provision') || Session::has('provision_alert'))
            $provisionAllowed = false;
        else
            $provisionAllowed = $lastSync->gt($lastProvision);
        $lastSync = $lastSync->format('d M, Y');
        $lastProvision = $lastProvision->format('d M, Y');

        // Get Organization Currency
        $getOrganizationId = CustomHelper::encode($organizationService->getAuthOrganizationId());
        $getOrgCurrency = $organizationService->fetch($getOrganizationId, 'currency');

        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $branchServices = new BranchInfoService();
        $branches = $branchServices->fetchAllWithColumns(['id', 'description']);

        $businessTypeService = new BusinessTypeService();
        $businessTypes = $businessTypeService->fetchAllWithColumns(['id', 'description']);

        $portfolioService = new PortfolioService();
        $portfolios = $portfolioService->fetchAll();

        $reportService = new ReportService;
        $report = $reportService->fetchByType(['result', 'filters', 'is_updated'], 'dashboard');

        return view('user.dashboard', compact('lastSync', 'lastProvision', 'provisionAllowed', 'getOrgCurrency', 'accountingYears', 'branches', 'businessTypes', 'portfolios', 'report'));
    }

    // V2 Dashboard
    public function dashboardV2(Request $request)
    {
        $organizationService = new OrganizationService();
        $currency = new CurrencyService();
        $settingService = new SettingService();
        $transition_date = $settingService->getOption('transition_date');

        $importDetailService = new ImportDetailService();

        $lastSync = Carbon::parse($importDetailService->fetchLatestDate('import'));
        $lastProvision = $importDetailService->fetchLatestProvision('provision');
        $lastProvision = $lastProvision ? Carbon::parse($lastProvision) : null;

        if ($organizationService->isBoarding() && !(Session::has('active_provision') || Session::has('provision_alert')))
            $provisionAllowed = true;
        else if (Session::has('active_provision') || Session::has('provision_alert'))
            $provisionAllowed = false;
        else
            $provisionAllowed = (bool) $lastSync;

        $lastSync = $lastSync->format('d M, Y');

        $lastProvision = $lastProvision ? $lastProvision->format('d M, Y') : null;

        // Get Organization Currency
        $getOrganizationId = CustomHelper::encode($organizationService->getAuthOrganizationId());
        $getOrgCurrency = $organizationService->fetch($getOrganizationId, 'currency');
        $currency = $currency->fetch(CustomHelper::encode($getOrgCurrency->currency_id));

        $accountingYearService = new AccountingYearService();
        $accountingYears = $accountingYearService->fetchByTransitionDate();

        $branchServices = new BranchInfoService();
        $branches = $branchServices->fetchAllWithColumns(['id', 'description']);

        $businessTypeService = new BusinessTypeService();
        $businessTypes = $businessTypeService->fetchAllWithColumns(['id', 'description']);

        $portfolioService = new PortfolioService();
        $portfolios = $portfolioService->fetchAll();

        $reportService = new ReportService();
        $report = $reportService->fetchByType(['result', 'filters', 'is_updated'], 'dashboard-v2');


        // Get matching data between provision and accounting_years table
        $provisionService = new ProvisionService;
        $valuationData = $provisionService->findMatchValuationDate();

        $lockedProvisionYear = "";
        // TODO: isLocked variable should changed to $provisionAllowed
        $isLocked = true;
        if($valuationData)
        {
            $isLocked = $provisionService->lockProvisionInvoke($valuationData['end_date']);
            $lockedProvisionYear = $valuationData['accounting_year_id'];
        }

        return view('user.dashboard-v2', compact('lastSync', 'lastProvision', 'provisionAllowed', 'getOrgCurrency', 'accountingYears', 'branches', 'businessTypes', 'portfolios', 'report', 'lockedProvisionYear', 'transition_date', 'isLocked', 'currency'));
    }

    public function leaveImpersonate()
    {
        auth()->user()->leaveImpersonation();
        return redirect('/admin/users');
    }

    public function fetchProvision(Request $request)
    {
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();

        $reportService = new ReportService;
        $report = $reportService->fetchByType('is_updated', 'dashboard-v2');

        $importDetailService = new ImportDetailService;
        $importDetail = $importDetailService->fetchUnapprovedImport(['default', 'provision'], ['provision', 'import', 'opening'], ['started', 'running', 'pending', 'rollback-inprogress']);

        // $runningImport = $importDetailService->fetchUnapprovedImport(['default'], ['import'], ['running']);
        $firstImport = $importDetail->first();

        // If nothing found clear all sessions
        if(!$firstImport)
            CustomHelper::clearSession($request);


        return response()->json([
            "status" => $firstImport ? $firstImport->status->slug : "not found",
            "session" => session('provision_alert'),
            "refresh" => $report->is_updated,
            "type" => $firstImport ? $firstImport->type : "not found",
        ]);

    }

    public function invokeProvision(Request $request)
    {
        // Additional Check
        if (Session::has('active_provision') || Session::has('provision_alert'))
            return redirect()->back()->withErrors('Cannot re-run provision while provision or import is running');

        $importDetailService = new ImportDetailService();
        $provisionService = new ProvisionSettingService;
        $settingService = new SettingService();
        $options = $settingService->getOptions();

        if (!isset($options['fail_lambda_id']) || !isset($options['management_expense_level_id']))
            return redirect()->back()->withErrors('General or Provision Tab settings have not been configured for this organization. Please contact admin.');

        if ($provisionService->fetchActiveProvisionSetting() == null)
            return redirect()->back()->withErrors("No Active Provision");

        if (CustomHelper::hasMissingRelations())
            return redirect()->back()->withErrors("Group mapping for some products have not been set.");

        $pendingImport = $importDetailService->fetchUnapprovedImport(['default'], ['import']);;
        if (count($pendingImport) > 0)
            return redirect()->back()->withErrors('Import has not been approved.');

        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();
        $organization = $organizationService->fetch(CustomHelper::encode($organization_id));
        $tenant_id = $organization->tenant_id;
        $checkLimit = $organization->checkSubscriptionUsage('provision-run');
        if ($checkLimit !== true)
            return $checkLimit;

        $importDetailService = new ImportDetailService();

        $importDetailPayload = $importDetailService->generateArray('provision', 'provision', 'provision.started');
        $importDetail = $importDetailService->create($importDetailPayload);

        $provisionService = new ProvisionService;
        $payload = $provisionService->getProvisionPayload($request->valuation_date, $importDetail->id, $tenant_id, $organization_id);
        $data = $provisionService->create($payload);

        $requiredFiles = [
            'App\Models\DiscountRate' => 'Discount Rate',
            'App\Models\ClaimPattern' => 'Claim Pattern',
            'App\Models\RiskAdjustment' => 'Risk Adjustment',
            'App\Models\IbnrAssumption' => 'IBNR Assumption',
        ];

        $missingFiles = [];
        foreach ($requiredFiles as $type => $label) {
            $latestFile = ProvisionFile::where('file_type', $type)
                ->whereDate('valuation_date', '<=', $request->valuation_date)
                ->orderByDesc('valuation_date')
                ->first();

            if (!$latestFile) {
                $missingFiles[] = $label;
            }
        }

        if (count($missingFiles) > 0)
            return redirect()->back()->withErrors('Provision files are missing for date prior to the selected valuation date: ' . implode(', ', $missingFiles));

        $provisionService->invokeProvision($payload);

        $request->session()->put('active_provision', true);
        $request->session()->put('valuation_date', "Valuation Date: " . $request->valuation_date);
        $data->addToCalendar('Run Provision', 'run-provision', 'warning');
        return redirect()->back();
    }

    // Dashboard Filters
    public function filters(Request $request)
    {
        $journalEntries = new JournalEntryPortfolioService();
        if ($request->has('version') && $request->version == 'v2')
            $json = $journalEntries->dashboardSummaryV2($request);
        else
            $json = $journalEntries->dashboardSummary($request);
        return $json;
    }

    public function invokeOpening(Request $request)
    {
        $importDetailService    = new ImportDetailService();
        $importDetailPayload    = $importDetailService->generateArray('opening', 'opening', 'default.started');
        $importDetail           = $importDetailService->create($importDetailPayload);

        $organizationService    = new OrganizationService;
        $organization_id        = $organizationService->getAuthOrganizationId();
        $tenant_id              = $organizationService->getAuthTenantId($organization_id);

        $openingBalanceService  = new OpeningBalanceService;

        $provisionService       = new ProvisionService;
        $valuationData           = $provisionService->findMatchValuationDate();
        $endDate = $valuationData ? $valuationData['end_date'] : "";
        $payload                = $openingBalanceService->getOpeningPayload($endDate, $importDetail->id, $tenant_id, $organization_id);
        $openingBalanceService->invokeOpening($payload);
        $request->session()->put('active_provision', true);

        return redirect()->back();
    }
}
