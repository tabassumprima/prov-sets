<?php

namespace App\Http\Controllers\User;

use App\Http\Requests\RiskAdjustment\Request;
use App\Helpers\{CustomHelper, RouterHelper};
use Illuminate\Support\Facades\{DB, Log};
use App\Services\{RiskAdjustmentService,RiskAdjustmentFileService};
use App\Http\Controllers\Controller;
use App\Traits\CheckPermission;
use Illuminate\Support\Str;
use Exception;

class RiskAdjustmentFileController extends Controller
{
    use CheckPermission;

    private $riskAdjustmentService, $router, $routerHelper;
    public function __construct(RiskAdjustmentService $riskAdjustmentService, RiskAdjustmentFileService $riskAdjustmentFileService)
    {
        $this->router                = 'risk-adjustments.index';
        $this->riskAdjustmentService = $riskAdjustmentService;
        $this->riskAdjustmentFileService = $riskAdjustmentFileService;
        $this->routerHelper          = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($risk_adjustment_id)
    {
        $this->authorizePermission('view-risk-adjustment-file');
        $records = $this->riskAdjustmentService->fetchWithRelations($risk_adjustment_id, ['files']);
        return view("user.risk_adjustments.files.index", compact("records"));
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
    public function store($risk_adjustment_id, Request $request)
    {
        $this->authorizePermission('create-risk-adjustment-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully added new file.';
        DB::beginTransaction();
        try {
            $this->riskAdjustmentService->createFile($risk_adjustment_id, $request);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();

            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
        return $this->routerHelper->redirectBack($error, $message);
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
    public function edit($risk_adjustment_id, $file_id)
    {
        $this->authorizePermission('update-risk-adjustment-file');
        $record = $this->riskAdjustmentFileService->fetch($file_id);
        return view("user.risk_adjustments.files.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($risk_adjustment_id, Request $request, $file_id)
    {
        $this->authorizePermission('update-risk-adjustment-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully update file.';
        try {
            DB::beginTransaction();
            $this->riskAdjustmentFileService->update($request, $file_id);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
        return redirect()->route('risk-adjustments.files.index', ['risk_adjustment' => $risk_adjustment_id])->with(['success' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($risk_adjustment_id, $file_id)
    {
        $this->authorizePermission('delete-risk-adjustment-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully delete file.';
        try {
            DB::beginTransaction();
            $this->riskAdjustmentFileService->delete($file_id);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $message = $e->getMessage();
            $error   = true;return $this->routerHelper->redirectBack($error, $message);
            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
        return $this->routerHelper->redirectBack($error, $message);
    }

    public function getFile($id)
    {
        $this->authorizePermission('download-risk-adjustment-file');
        return CustomHelper::downloadFiles($id,'risk_adjustments');
    }
    
    public function getErrorFile()
    {   
        return CustomHelper::downloadInvalidData();
    }

    public function fetchFile($file)
    {
        return CustomHelper::getFileData($file,'risk_adjustments');
    }
}
