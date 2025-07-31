<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImportDetailConfig\Request;
use App\Models\{GroupProduct, ImportDetail, JournalEntry};
use App\Services\{GroupProductService, ImportDetailConfigService, OrganizationService, ImportDetailService};
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Log;

class ImportDetailConfigController extends Controller
{
    private $importDetailConfigService, $router, $routerHelper;

    public function __construct(ImportDetailConfigService $importDetailConfigService)
    {
        $this->router = 'import-detail-configs.index';
        $this->importDetailConfigService = $importDetailConfigService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationService = new OrganizationService();
        $isBoarding = $organizationService->tenantIsBoarding();

        $importDetailService = new ImportDetailService();
        $import_details = $importDetailService->fetchAll();

        return view('admin.import_detail.index', compact('import_details', 'isBoarding'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.import_detail.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = false;
        $message = trans('user/import_detail_config.created');

        try {
            $this->importDetailConfigService->create($request);
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $importDetailService = new ImportDetailService();
        $countAll =$importDetailService->fetchCount(['AccountingYear','Branch','BusinessType','ClaimPaidRegister','SystemDepartment','InsuranceType','DocumentType','EndorsementType','TransactionType','ProductCode','PremiumRegister','GlCode','VoucherType','JournalEntry'],$id);

        $organizationService = new OrganizationService();
        $isBoarding = $organizationService->tenantIsBoarding();

        $importDetailIdShow = ImportDetail::with(['productCode', 'status'])->find($id);
        $completedSlug = $importDetailIdShow->status->slug;

        $products_id = $importDetailIdShow->productCode->pluck('id');
        $mappingExist = GroupProduct::whereIn('product_code_id',$products_id)->count();
        $type = $importDetailIdShow->type;

        $importDetailService = new ImportDetailService();
        $latest_id = $importDetailService->fetchLatest();
        return view('admin.import_detail.show',compact('countAll','isBoarding', 'mappingExist','latest_id', 'id', 'completedSlug' , 'type'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $config_model = $this->importDetailConfigService->fetch($id);
        $config = $this->importDetailConfigService->fetchConfig($config_model->path, $config_model->organization_id);
        $config =json_encode($config, JSON_PRETTY_PRINT);

        return view("admin.import_detail.edit", compact('config_model', 'config'));
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

    public function createAndImport(Request $request)
    {
        $error = false;
        $message = trans('user/import_detail_config.created_and_started');
        try {
            $import_detail_config = $this->importDetailConfigService->create($request);
            $this->importDetailConfigService->import($import_detail_config->id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function rollBack(HttpRequest $request)
    {
        $organizationService = new OrganizationService();
        $isBoarding = $organizationService->tenantIsBoarding();  //getting onBoarding status

        $importDetailService = new ImportDetailService();
        $latest_id = $importDetailService->fetchLatest(); //getting the import id which is created recently

        $importDetailIdShow = ImportDetail::with(['productCode', 'status'])->find($request->import_id);
        $completedSlug = $importDetailIdShow->status->slug; //getting slug against that import id

        $products_id = $importDetailIdShow->productCode->pluck('id');
        $mappingExist = GroupProduct::whereIn('product_code_id',$products_id)->count(); // getting total count of group products against that import id
        $type = $importDetailIdShow->type; //getting type of that import id

        if($isBoarding && $latest_id->id == $request->import_id && $completedSlug == 'completed' && $mappingExist)
        {
            if($type == 'provision')
            {
                JournalEntry::where('import_detail_id',$request->import_id)->delete();
            }

        }
        else{
            return redirect()->back();
        }
    }
}
