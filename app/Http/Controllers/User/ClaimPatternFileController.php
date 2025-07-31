<?php

namespace App\Http\Controllers\User;

use App\Helpers\{CustomHelper, RouterHelper};
use App\Http\Requests\ClaimPattern\Request;
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Controllers\Controller;
use App\Services\{ClaimPatternService, ClaimPatternFileService};
use App\Traits\CheckPermission;
use Illuminate\Support\Str;
use Exception;

class ClaimPatternFileController extends Controller
{
    use CheckPermission;

    private $claimPatternService, $router, $routerHelper;
    public function __construct(ClaimPatternService $claimPatternService, ClaimPatternFileService $claimPatternFileService)
    {
        $this->router              = 'claim_pattern_files.index';
        $this->claimPatternService = $claimPatternService;
        $this->claimPatternFileService = $claimPatternFileService;
        $this->routerHelper        = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($claim_pattern_id)
    {
        $this->authorizePermission('view-claim-pattern-file');
        $claim_pattern = $this->claimPatternService->fetchWithRelations($claim_pattern_id, ['files']);
        return view('user.claim_patterns.files.index', compact('claim_pattern'));
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
    public function store($claim_pattern_id, Request $request)
    {
        $this->authorizePermission('create-claim-pattern-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully added new claim pattern file.';
        try {
            DB::beginTransaction();
            $this->claimPatternService->createFile($claim_pattern_id, $request);
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
    public function edit($claim_pattern_id, $file_id)
    {
        $this->authorizePermission('update-claim-pattern-file');
        $record = $this->claimPatternFileService->fetch($file_id);
        return view("user.claim_patterns.files.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($claim_pattern_id, Request $request, $file_id)
    {
        $this->authorizePermission('update-claim-pattern-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully update file.';
        try {
            DB::beginTransaction();
            $this->claimPatternFileService->update($request, $file_id);
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
        return redirect()->route('claim-patterns.files.index', ['claim_pattern' => $claim_pattern_id])->with(['success' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($claim_pattern_id,$file_id)
    {
        $this->authorizePermission('delete-claim-pattern-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully delete file.';
        try {
            DB::beginTransaction();
            $this->claimPatternFileService->delete($file_id);
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
        $this->authorizePermission('download-claim-pattern-file');
        return CustomHelper::downloadFiles($id,'claim_patterns');
    }

    public function getErrorFile()
    {   
        return CustomHelper::downloadInvalidData();
    }

    public function fetchFile($file)
    {
        return CustomHelper::getFileData($file,'claim_patterns');
    }
}
