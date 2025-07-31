<?php

namespace App\Http\Controllers\User;

use App\Helpers\CustomHelper;
use App\Services\{ProvisionSettingService, OrganizationService};
use App\Http\Controllers\Controller;
use App\Helpers\RouterHelper;
use App\Http\Requests\ProvisionMapping\Request;
use Illuminate\Support\Facades\Log;

class ProvisionMappingController extends Controller
{
    private $provisionSettingService, $router, $routerHelper;
    public function __construct(ProvisionSettingService $provisionSettingService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->provisionSettingService = $provisionSettingService;
        $this->router       = 'provision-setting.index';
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($provisionSettingId)
    {
        $provisionSetting = $this->provisionSettingService->fetch($provisionSettingId);
        $organizationService = new OrganizationService();
        $error = false;
        $message = '';
        try {
            // $this->provisionSettingService->verifyProvisionSetting(CustomHelper::decode($provisionSettingId));
            $count = $this->provisionSettingService->compareCount($provisionSettingId);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
        }
        if ($error || $count == null) {
            if(!$message)
                $message = 'General settings have not been configured for this organization. Please contact admin.';
            return $this->routerHelper->redirectBack(true, $message);
        }
        if (!$organizationService->verifyIfProvisionFilesAreEmpty())
            return $this->routerHelper->redirectBack(true, 'Provision files not found or InActive.');
        return view('user.provision_mapping.index', compact('provisionSetting', 'count'));
    }

    public function createInsurance($provisionSettingId)
    {
        $organizationService      = new OrganizationService();
        $isBoarding = $organizationService->isBoarding();
        $provisionSetting         = $this->provisionSettingService->fetch($provisionSettingId);
        $provisionMappingProducts = $this->provisionSettingService->fetchJsTable($provisionSettingId);
        return view('user.provision_mapping.insurance.create', compact('provisionSetting', 'provisionMappingProducts','isBoarding'));
    }

    public function createFacultative($provisionSettingId)
    {
        $organizationService      = new OrganizationService();
        $isBoarding = $organizationService->isBoarding();
        $provisionSetting         = $this->provisionSettingService->fetch($provisionSettingId);
        $provisionMappingProducts = $this->provisionSettingService->fetchJsReFacultativeTable($provisionSettingId);
        return view('user.provision_mapping.re-insurance.facultative.create', compact('provisionSetting', 'provisionMappingProducts','isBoarding'));
    }

    public function createTreaty($provisionSettingId)
    {
        $organizationService      = new OrganizationService();
        $isBoarding = $organizationService->isBoarding();
        $provisionSetting         = $this->provisionSettingService->fetch($provisionSettingId);
        $provisionMappingProducts = $this->provisionSettingService->fetchJsReTreatyTable($provisionSettingId);
        return view('user.provision_mapping.re-insurance.treaty.create', compact('provisionSetting', 'provisionMappingProducts','isBoarding'));
    }

    public function storeInsurance(Request $request, $provisionSetting)
    {
        $error = false;
        $message = trans('user/provision_mapping.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->provisionSettingService->createMapping($request, $provisionSetting);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return response()->json(['message' => $message], 400);
        session()->flash('success', $message);
        return response()->json(['message' => $message], 200);
    }

    public function storeFacultative(Request $request, $provisionSetting)
    {
        $error = false;
        $message = trans('user/provision_mapping.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->provisionSettingService->createFacultativeMapping($request, $provisionSetting);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return response()->json(['message' => $message], 400);
        session()->flash('success', $message);
        return response()->json(['message' => $message], 200);
    }

    public function storeTreaty(Request $request, $provisionSetting)
    {
        $error = false;
        $message = trans('user/provision_mapping.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->provisionSettingService->createTreatyMapping($request, $provisionSetting);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return response()->json(['message' => $message], 400);
        session()->flash('success', $message);
        return response()->json(['message' => $message], 200);
    }
}
