<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{CountryService, DatabaseConfigService, FormatJsonService, PermissionService, PlanService, SubscriptionService};
use App\Services\{DisclosureService, ProvisionSettingService, ChartOfAccountService, OrganizationService, CurrencyService};
use App\Http\Requests\Organization\Request;
use Illuminate\Support\Facades\{Log, DB};
use Exception;

class OrganizationController extends Controller
{
    private $organizationService, $router, $routerHelper;

    public function __construct(OrganizationService $organizationService)
    {
        $this->router = 'tenant.index';
        $this->organizationService = $organizationService;
        $this->routerHelper = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countryService = new CountryService();
        $countries = $countryService->fetchAll();

        $currencyService = new CurrencyService();
        $currencies = $currencyService->fetchAll();

        $databaseConfigService = new DatabaseConfigService();
        $configs = $databaseConfigService->fetchAll();

        $financial_years = config("constant.default_financial_year");
        $insurance_types = config("constant.default_organization_types");

        $organizations = $this->organizationService->fetchAll();

        return view('admin.organizations.index', compact('organizations', 'countries', 'currencies', 'configs', 'financial_years', 'insurance_types'));
    }

    public function create()
    {
        $countryService = new CountryService();
        $countries = $countryService->fetchAll();

        $currencyService = new CurrencyService();
        $currencies = $currencyService->fetchAll();

        $databaseConfigService = new DatabaseConfigService();
        $configs = $databaseConfigService->fetchAll();

        $planService = new PlanService();
        $plans       = $planService->fetchActivePlans();

        $financial_years = config("constant.default_financial_year");
        $insurance_types = config("constant.default_organization_types");

        return view('admin.organizations.create', compact('countries', 'currencies', 'configs', 'financial_years', 'insurance_types', 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error   = false;
        $data    = $request->validated();
        $message = trans('admin/organization.created', ['NAME' => $data['name']]);
        try {
            DB::beginTransaction();
            $organization = $this->organizationService->create($request);

            // For default entry in chart of account
            $chartOfAccountService = new ChartOfAccountService();
            $chartOfAccountService->initCreate($organization->id);

            // Default entry of json format
            $formatJsonService = new FormatJsonService();
            $formatJsonService->initCreate($organization->id);

            // Provision settings
            $provisionSettingService = new ProvisionSettingService();
            $provisionSettingService->initCreate($organization->id);

            // Assign subscription
            $subscriptionService = new SubscriptionService();
            $subscriptionService->addSubscription($organization->id, $data);

            // Default permission set
            $permissionService = new PermissionService();
            $permissionService->createNewOrganizationPermissions($organization);

            // Copy disclosure file from default organization
            $disclosureService = new DisclosureService();
            $disclosureService->initDisclosureFile($organization->id);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization = $this->organizationService->fetchWithRelation($id, ['users', 'activePlan']);

        $countryService = new CountryService();
        $countries = $countryService->fetchAll();

        $currencyService = new CurrencyService();
        $currencies = $currencyService->fetchAll();

        $databaseConfigService = new DatabaseConfigService();
        $configs = $databaseConfigService->fetchAll();

        $planService = new PlanService();
        $plans       = $planService->fetchActivePlans();

        $financial_years = config("constant.default_financial_year");

        return view('admin.organizations.edit', compact('organization', 'countries', 'currencies', 'configs', 'financial_years', 'plans'));
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
        $error = false;
        $data = $request->validated();
        $message = trans('admin/organization.updated', ['NAME' => $data['name']]);
        try {
            $this->organizationService->update($request, $id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirectBack($error, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $error = false;
        $message = trans('admin/organization.deleted');
        try {
            $this->organizationService->delete($id);
        } catch (Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function getLogo($id)
    {
        $organization = $this->organizationService->fetch($id);
        $img = $organization->logo;
        return view('admin.organizations.logo', compact('img'));
    }
}
