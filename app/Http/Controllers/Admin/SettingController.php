<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Helpers\DynamoHelper;
use App\Services\{OrganizationAccessTokenService, LambdaFunctionService, ProductService, TreatyService, VoucherTypeService, PortfolioService, LevelService, SettingService, OrganizationService};
use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\Request;
use App\Http\Requests\CloudSetting\Request as CloudSetting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request as RequestHttp;

class SettingController extends Controller
{
    private $settingService, $router, $routerHelper;

    public function __construct(SettingService $settingService)
    {
        $this->router         = 'settings.create';
        $this->settingService = $settingService;
        $this->routerHelper   = new RouterHelper;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(RequestHttp $request)
    {
        $portfolioService = new PortfolioService;
        $portfolios       = $portfolioService->fetchAll();

        $productService = new ProductService();
        $products       = $productService->fetchAll();

        $reproducttreatyService = new TreatyService();
        $reproducts       = $reproducttreatyService->fetchAll();

        $levelService = new LevelService;
        $levels       = $levelService->fetchAll();

        $voucherTypeService  = new VoucherTypeService;
        $voucherTypes        = $voucherTypeService->fetchAll();

        $lambdaService = new LambdaFunctionService;
        $lambdas       = $lambdaService->fetchAllActive();

        $settings = $this->settingService->fetchOrganizationSetting();
        $settings = collect($settings?->options);

        $organizationAccessTokenService = new OrganizationAccessTokenService;
        $accessKeys = $organizationAccessTokenService->organizationKeys($request->organization_id);

        return view('admin.settings.create', compact('portfolios', 'levels', 'voucherTypes', 'lambdas', 'settings', 'accessKeys', 'products', 'reproducts'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Setting\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error   = false;
        $message = trans('admin/setting.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->settingService->create($request);
        } catch (\Exception $e) {
            $error   = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function generateAccessKey(RequestHttp $request)
    {
        $organizationAccessTokenService = new OrganizationAccessTokenService;
        $accessKey = $organizationAccessTokenService->createAccessToken($request->organization_id);
        return redirect()->back()->with('success', 'Key has been generated')->withInput($request->all());
    }

    public function revokeAccessKey(RequestHttp $request)
    {
        $organizationAccessTokenService = new OrganizationAccessTokenService;
        $organizationAccessTokenService->delete($request->organization_id);

        return redirect()->back()->with('success', 'Key has been revoked');
    }

    public function cloudSetting()
    {
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getTenantOrganizationId();

        // dd($organization_id);
        $organization = $organizationService->fetch(CustomHelper::encode($organization_id));
        $dynamodb = new DynamoHelper;
        $items = $dynamodb->getItem($organization->tenant_id);

        $organizationAccessTokenService = new OrganizationAccessTokenService;
        $accessKeys = $organizationAccessTokenService->organizationKeys($organization_id);
        $isExpired =  Carbon::now()->gte($accessKeys?->expires_at);

        $tenantID = $organization->tenant_id;


        return view("admin.settings.cloud-settings.create", compact('accessKeys', 'tenantID', 'isExpired', 'items'));
    }

    public function updateCloudSetting(CloudSetting $request)
    {
        try {

            extract($request->all());
            $dynamodb = new DynamoHelper;
            $organizationService = new OrganizationService;
            $organization_id = $organizationService->getTenantOrganizationId();

            $formattedExpiryDate = Carbon::createFromFormat(config('constant.datetime_format'), $access_token_expiry)->toDateString();

            $organization = $organizationService->fetch(CustomHelper::encode($organization_id));
            $dynamodb = new DynamoHelper;
            $items = $dynamodb->itemMapping($organization->tenant_id, $organization->shortcode, $access_token, $formattedExpiryDate, $bucket, $rds_db_name, $rds_host, $rds_port, $rds_password, $rds_user);
            $dynamodb->dynamoInsert($items);
            return redirect()->back()->with('success', 'Update cloud setting successfully');
        }
        catch(Exception $e)
        {
            Log::error($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
