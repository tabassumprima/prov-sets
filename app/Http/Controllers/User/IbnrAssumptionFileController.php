<?php

namespace App\Http\Controllers\User;

use App\Helpers\{RouterHelper, CustomHelper};
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Requests\IbnrFile\Request;
use App\Services\{IbnrAssumptionService,IbnrAssumptionFileService};
use App\Http\Controllers\Controller;
use App\Traits\CheckPermission;
use Illuminate\Support\Str;
use Exception;

class IbnrAssumptionFileController extends Controller
{
    use CheckPermission;

    private $ibnrAssumptionService, $router, $routerHelper;
    public function __construct(IbnrAssumptionService $ibnrAssumptionService,IbnrAssumptionFileService $ibnrAssumptionFileService)
    {
        $this->router                = 'ibnr-assumptions.index';
        $this->ibnrAssumptionService = $ibnrAssumptionService;
        $this->ibnrAssumptionFileService = $ibnrAssumptionFileService;
        $this->routerHelper          = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($discount_rate_id)
    {
        $this->authorizePermission('view-ibnr-assumption-file');
        $records = $this->ibnrAssumptionService->fetchWithRelations($discount_rate_id, ['files']);
        return view("user.ibnr_assumptions.files.index", compact("records"));
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
    public function store($ibnr_assumption_id, Request $request)
    {
        $this->authorizePermission('create-ibnr-assumption-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully added new file.';
        try {
            DB::beginTransaction();
            $this->ibnrAssumptionService->createFile($ibnr_assumption_id, $request);
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
    public function edit($ibnr_assumption_id, $file_id)
    {
        $this->authorizePermission('update-ibnr-assumption-file');
        $record = $this->ibnrAssumptionFileService->fetch($file_id);
        return view("user.ibnr_assumptions.files.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($ibnr_assumption_id, Request $request, $file_id)
    {
        $this->authorizePermission('update-ibnr-assumption-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully update file.';
        try {
            DB::beginTransaction();
            $this->ibnrAssumptionFileService->update($request, $file_id);
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
        return redirect()->route('ibnr-assumptions.files.index', ['ibnr_assumption' => $ibnr_assumption_id])->with(['success' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($ibnr_assumption_id, $file_id)
    {
        $this->authorizePermission('delete-ibnr-assumption-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully delete file.';
        try {
            DB::beginTransaction();
            $this->ibnrAssumptionFileService->delete($file_id);
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
        $this->authorizePermission('download-ibnr-assumption-file');
        return CustomHelper::downloadFiles($id,'ibnr_assumptions');
    }
    
    public function getErrorFile()
    {   
        return CustomHelper::downloadInvalidData();
    }

    public function fetchFile($file)
    {
        return CustomHelper::getFileData($file,'ibnr_assumptions');
    }
}
