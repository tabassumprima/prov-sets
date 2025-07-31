<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ImportDetail;
use App\Services\ChartOfAccountService;
use App\Services\ClaimPatternService;
use App\Services\DiscountRateService;
use App\Services\FormatJsonService;
use App\Services\IbnrAssumptionService;
use App\Services\ImportDetailService;
use App\Services\OrganizationService;
use App\Services\RiskAdjustmentService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationService = new OrganizationService();
        $organization = $organizationService->fetch(request('org'));

        $importDetailService = new ImportDetailService();
        $lastSync       =  Carbon::parse($importDetailService->fetchLatestDate('sync'));
        $lastProvision  =  Carbon::parse($importDetailService->fetchLatestDate('provision'));
        $provisionAllowed   = $lastSync->gt($lastProvision);
        $lastSync           = $lastSync->format('d M, Y');
        $lastProvision      = $lastProvision->format('d M, Y');
        return view('admin.dashboard', compact('lastSync', 'lastProvision', 'provisionAllowed', 'organization'));
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

    public function importDataJob(Request $request)
    {

        $importDetailService = new ImportDetailService();
        $importDetail    = $importDetailService->create($request);
        $payload = [
            "algo-1" => [
                "message" => "This is algo-1",
                "sleep" => 5,
                "id" => $importDetail->id
            ],
            "algo-2" => [
                "message" => "This is algo-2",
                "sleep" => 5,
                "id" => $importDetail->id
            ],
            "db-insert" => [
                "message"=>"This is db",
                "sleep"=>1
            ],
              "db-fail"=>[
                "message"=>"This is db fail",
                "sleep"=>1
              ]
        ];
        $payload = json_encode($payload, true);
        ImportDetail::dispatch($payload);
        return redirect()->back();
    }

    public function toggleBoarding()
    {
        try {
            $organizationService = new OrganizationService;
            $organizationService->toggleBoarding(request('org'));
            return redirect()->back();
        }
        catch(Exception $e)
        {
            return redirect()->back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function moduleStatus()
    {
        $organizationService = new OrganizationService();
        $organizationId = $organizationService->getTenantOrganizationId();

        // Fetch provision rules
        $provisionRules = [];
        foreach (config('constant.s3_paths.provision_rules') as $key => $report) {
            $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_rules.' . $key);
            $provisionRules[$key] = CustomHelper::checkFileExistence($filePath, 'rule.json');
        }

        // Fetch dashboard files
        $dashboardFiles = [];
        $dashboardFilesList = ['cities' => 'cities-data.json', 'chart' => 'chart-data.json'];
        $dashboardFilePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dashboard');
        foreach ($dashboardFilesList as $key => $file) {
            $dashboardFiles[$key] = CustomHelper::checkFileExistence($dashboardFilePath, $file);
        }

        // Fetch report files
        $reportData = [];
        $formatJsonService = new FormatJsonService();
        $formatJson = $formatJsonService->fetchAll();
        $paths = config('constant.s3_paths.report_type');

        foreach ($paths as $key => $path) {
            $type = strtoupper($key);  
            $reportStoragePath = CustomHelper::fetchOrganizationStorage($organizationId, 'report_type.' . $key);
            $latestReport = $formatJsonService->fetchTenantLatestReportFile($type);
          
            if ($latestReport) {
                $fullPath = $reportStoragePath . '/' . $latestReport->file_name;
                $fileExists = Storage::disk('s3')->exists($fullPath);
    
                $reportData[$latestReport->type] = [
                    'status' => $fileExists ? 1 : 0,
                    'message' => $fileExists ? 'File Found' : 'File Not Found',
                    'id' => $latestReport->id,
                    'type' => $type
                ];
            } else {
                $reportData[$type] = [
                    'status' => 0,
                    'message' => 'File Not Found'
                ];
            }
        }

        
        $discountRateService = new DiscountRateService();
        $discountRates = $discountRateService->getLatestDiscountRate();

        $ibnrAssumptionService = new IbnrAssumptionService();
        $ibnrAssumption = $ibnrAssumptionService->getLatestIbnrAssumption();

        $riskAdjustmentService = new RiskAdjustmentService();
        $riskAdjustment = $riskAdjustmentService->getLatestRiskAdjustment();

        $claimPatternService = new ClaimPatternService();
        $claimPattern = $claimPatternService->getLatestClaimPattern();
        
        $provisionFiles = [
            'discount_rates' => $discountRates,
            'ibnr_assumptions' => $ibnrAssumption,
            'risk_adjustments' => $riskAdjustment,
            'claim_patterns' => $claimPattern
        ];

        foreach ($provisionFiles as $key => $value) {
            if (isset($value) && !empty($value)) {
                $fileCount = count($value->files);
                foreach ($value->files as $index => $file) {
                    $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_files.' . $key);
                    $fullPath = $filePath.$file->path;
                    $fileExists = Storage::disk('s3')->exists($fullPath);
                    $provisionFiles[$key] = [
                        'status' => $fileExists ? 1 : 0,
                        'message' => $fileExists ? 'File Found' : 'File Not Found',
                        'folder_path' => $key,
                        'files' => $fileExists ? json_encode($value->files) : [],
                        'file_count' => $fileExists ? $fileCount : 0,
                    ];
                }
            }else{
                $provisionFiles[$key] = [
                    'status' =>  0,
                    'message' => 'File Not Found',
                    'folder_path' => $key,
                    'files' =>  [],
                    'file_count' => 0,
                ];
            }
        }
        // Fetch chart of account file
        $chartOfAccountService = new ChartOfAccountService();
        $chartOfAccountFilePath =  $chartOfAccountService->fetchChartOfAccountFilePath($organizationId);
        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'chart_of_account_files');
        if(isset($chartOfAccountFilePath) && !empty($chartOfAccountFilePath)){
            $fileExists = Storage::disk('s3')->exists($filePath.$chartOfAccountFilePath->path);
            $chartOfAccountData = [
                'status' => $fileExists ? 1 : 0,
                'message' => $fileExists ? 'File Found' : 'File Not Found',
            ];
        }
        else{
            $chartOfAccountData = [
                'status' => 0,
                'message' => 'File Not Found',
            ];
        }

        // Schema File
        $configFiles = [];
        $configFilesList = ['Schema' => 'schema_config.json', 'General' => 'general.json'];
        foreach ($configFilesList as $key => $file) {
            $configFilesData[$key] = Storage::disk('s3')->exists($file);
            $configFiles[$key] =[
                'status' => $configFilesData[$key] ? 1 : 0,
                'message' => $configFilesData[$key] ? 'File Found' : 'File Not Found',
                'file_name' => $file,
            ];
        }

        return view('admin.modules.index', compact('provisionRules', 'dashboardFiles', 'chartOfAccountData','reportData','provisionFiles','configFiles'));
    }

    public function downloadDashboardFiles($file)
    {
        return CustomHelper::dashboardFiles($file);
    }

    public function FetchProvisionFiles($folder, $file)
    {
        return CustomHelper::FetchFileData($file,$folder);
    }

    public function downloadConfigFile($file)
    {
        return Storage::disk('s3')->download($file);
    }
}
