<?php

namespace App\Http\Controllers\User;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvisionSetting\Request;
use Illuminate\Http\Request as statusRequest;
use App\Services\ProvisionSettingService;
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;
use App\Traits\CheckPermission;
use Exception;

class ProvisionSettingController extends Controller
{
    use CheckPermission;
    private $provisionSettingService, $router, $routerHelper;
    public function __construct(ProvisionSettingService $provisionSettingService)
    {
        try {
            $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        } catch (Exception $e) {
            return $this->routerHelper->redirectBack('danger', $e->getMessage());
        }

        $this->router = 'provision-setting.index';
        $this->provisionSettingService = $provisionSettingService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-provision-setting');
        $order = ['started' => 2, 'not-started' => 1];
        $unsortedprovisionSettings = $this->provisionSettingService->fetchAll();
        $provisionSettings = $unsortedprovisionSettings->sortByDesc(fn ($item) => $order[$item->status->slug])->values();
        return view('user.provision_setting.index', compact('provisionSettings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProvisionSetting\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorizePermission('create-provision-setting');
        $error = false;
        $message = trans('user/provision_setting.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->provisionSettingService->create($request);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorizePermission('delete-provision-setting');
        $error = false;
        $message = trans('user/provision_setting.deleted');
        try {
            $this->provisionSettingService->fetchCurrentProvision($id);
            $this->provisionSettingService->delete($id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function statusUpdate(statusRequest $request, $id)
    {
        $this->authorizePermission('status-update-provision-setting');
        $provision_settings = $this->provisionSettingService->fetch($id);
        $value = $request->value;
        if ($value == 'started') {
            $slug = 'not-started';
            $status = CustomHelper::fetchStatus($slug);
        } else {
            $slug = 'started';
            $status = CustomHelper::fetchStatus($slug);
            $active_status_count = $this->provisionSettingService->verifyActiveStatus($status);
            if ($active_status_count) {
                session()->flash('error', "2 provision cannot be started at same time");
                return response()->json();
            }
            if (!$provision_settings->mappings()->exists() || !$provision_settings->reProvisionTreatyMappings()->exists() || !$provision_settings->reProvisionFacultativeMappings()->exists() || !$provision_settings->ExpenseAllocations()->exists()) {
                session()->flash('error', "Provision Setting does not have all the mappings.");
                return response()->json();
            }
        }

        $provision_settings->status_id = $status;
        $provision_settings->save();
        return $provision_settings;
    }

}

