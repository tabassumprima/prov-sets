<?php

namespace App\Http\Controllers\User;

use App\Helpers\AwsHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubImport\Request;
use App\Services\ImportDetailService;
use App\Services\SettingService;
use App\Services\SubImportService;
use CustomHelper;
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;

class SubImportController extends Controller
{
    private $subImportService, $router, $routerHelper;

    public function __construct(SubImportService $subImportService)
    {
        // $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router = 'sub-import.index';
        $this->subImportService = $subImportService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($importDetail)
    {
        $importDetailService = new ImportDetailService();
        $importDetail = $importDetailService->fetchByRelations(['subImports', 'status'],CustomHelper::decode($importDetail));
        $importStatus = $importDetail->status;
        $summaryStatus = $importDetail->importDetailSummary->status;
        $subImports = $importDetail->subImports->where('status_id', CustomHelper::fetchStatus('completed', 'default'));
        $tables = config('constant.import_table_types');

        $settingService = new SettingService();
        $setting = $settingService->getOption('is_auto_import');

        return view('user.import_detail.sub_import.index', compact('subImports', 'importDetail', 'importStatus', 'summaryStatus', 'tables', 'setting'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.sub_import.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubImport\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $error = false;
        $request->validated();
        try
        {
            $this->subImportService->create($request, $id);
        }
        catch(Exception $e)
        {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        if ($error)
            return redirect()->back()->with('error', 'Failed to add file.'.$message);


        return response()->json(['success' => 'Data import has been uploaded', 'id' => $id]);
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
        $currency = $this->subImportService->fetch($id);
        return view('user.sub_import.edit', compact('currency'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SubImport\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $message = trans('user/sub_import.updated', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->subImportService->update($request, $id);
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
        $error = false;
        $message = trans('user/sub_import.deleted');
        try {
            $this->subImportService->delete($id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function getImportStatus($id)
    {
        $importDetailService = new ImportDetailService();
        $importDetail = $importDetailService->fetchByRelations(['status'], CustomHelper::decode($id));
        $importStatus = $importDetail->status;
        return response()->json(['status' => $importStatus]);
    }

    public function getFile($id)
    {
        $subimport = $this->subImportService->fetch($id);
        $filePath =  $this->subImportService->getFullPath($subimport->import_detail_id) ;

        return $this->subImportService->downloadFiles($filePath, $subimport->file_name);
    }

    function deleteFile($subimportId)
    {
        $subImport                  =   $this->subImportService->fetch($subimportId);
        $DependantTableList         =   $this->subImportService->getDependentTables($subImport->table_name);
        $isTableDependant           =   $this->subImportService->checkIfTablesExistForOrg( $DependantTableList, $subImport->import_detail_id );

        if($isTableDependant)
            return redirect()->back()->withErrors(['error' => 'You must first delete the dependent record before proceeding']);

        $preparedRollBackPayload =  $this->subImportService->prepareRollBackPayload($subimportId, $subImport->import_detail_id);

        AwsHelper::invokeRollBack($preparedRollBackPayload);
        return redirect()->back()->with('success', 'File deletion process has started successfully!');
    }

}
