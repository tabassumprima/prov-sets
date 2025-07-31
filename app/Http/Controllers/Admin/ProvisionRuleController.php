<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Requests\ProvisionRule\Request as ProvisionRuleRequest;
use Illuminate\Support\Facades\{DB, Log, Storage};
use App\Http\Controllers\Controller;
use App\Helpers\RouterHelper;
use App\Services\OrganizationService;
use App\Services\ProvisionService;
use Exception;

class ProvisionRuleController extends Controller
{
    private $router, $routerHelper;

    public function __construct()
    {
        $this->router       = 'provision-rules.index';
        $this->routerHelper = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.provision-rules.index');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvisionRuleRequest $request)
    {
        $request->validated();
        $error   = false;
        $message = 'Provision file added.';
        DB::beginTransaction();
        try {
            $organizationService = new OrganizationService();
            $organizationId      = $organizationService->getTenantOrganizationId();
            $filePath            = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_rules.' . $request->type);
            Storage::disk('s3')->putFileAs($filePath, $request->rule_file, 'rule.json');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function storeGraphJson(ProvisionRuleRequest $request)
    {
        $request->validated();
        $error   = false;
        $message = 'Provision file added.';
        DB::beginTransaction();
        try {
            $organizationService = new OrganizationService();
            $organizationId      = $organizationService->getTenantOrganizationId();
            $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dashboard');
            Storage::disk('s3')->putFileAs($filePath, $request->rule_file, 'new_graph.json');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function getRuleFile($module, $type=null)
    {
        $error    = false;
        $message  = 'File download';
        try {
            $provisionService = new ProvisionService();
            $organizationService = new OrganizationService();
            $organizationId      = $organizationService->getTenantOrganizationId();
            $fullPath =  $provisionService->getFilePath($organizationId, $module, $type);
            $file     = Storage::disk('s3')->get($fullPath);
            $headers  = [
                'Content-Type' => 'application/json',
                'Content-Disposition' => 'attachment; filename="' . basename($fullPath) . '"',
            ];
            return response()->make($file, 200, $headers);
        } catch (Exception $e) {
            $error   = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        return $this->routerHelper->redirectBack($error, $message);
    }
}
