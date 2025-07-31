<?php

namespace App\Http\Controllers\User;

use App\Helpers\AwsHelper;
use App\Helpers\RouterHelper;
use App\Services\SummaryService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;
use Exception;
use Storage;

class SummaryController extends Controller
{
    use CheckPermission;

    private $summaryService, $router, $routerHelper;
    public function __construct(SummaryService $summaryService)
    {
        $this->router = 'summaries.index';
        $this->summaryService = $summaryService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-summary');
        $summaries = $this->summaryService->fetchAll();
        return view('user.summary.index', compact('summaries'));
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
        $this->authorizePermission('delete-summary');
        $message = "Summary ID " . $id . " is being deleted. It may take a few minutes to delete associated data.";
        DB::beginTransaction();
        try {
            $summaryStatus = $this->summaryService->getSummaryStatus($id);
            $pendingStatusId = $this->summaryService->getStatusBySlug('pending');
            $summaryData = $this->summaryService->changeStatus($id, 'revoked');
            if($summaryStatus->status_id == $pendingStatusId) {
                $this->summaryService->updateImportStatus($id, 'rollback-inprogress');
                $rollbackPayload = $this->summaryService->prepareRollback($summaryData);
                AwsHelper::invokeImport($rollbackPayload);
            }
            DB::commit();
            return $this->routerHelper->redirect($this->router, false, $message);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->routerHelper->redirectBack(true, $e->getMessage());
        }
    }

    public function invokeImport($summary)
    {
        $this->authorizePermission('approve-summary');
        $message = "Summary id ". $summary . " has been approved";
        DB::beginTransaction();
        try {
            $summaryData = $this->summaryService->updateStatus($summary);
            $prepareInput = $this->summaryService->prepareInput($summaryData);
            AwsHelper::invokeImport($prepareInput);
            DB::commit();
            return $this->routerHelper->redirect($this->router, false, $message);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->routerHelper->redirectBack(true, $e->getMessage());
        }
    }

    public function downloadErrorFile($summary_id)
    {
        try {

            $filePath = $this->summaryService->getErrorFilePath($summary_id);

            if (Storage::disk('s3')->exists($filePath)) {

                return Storage::disk('s3')->download($filePath);
            } else {

                return redirect()->back()->with('error', 'Error file does not exist.');
            }
        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    function lockSummariesAndImport($summary_id)
    {
        $summaryStatus = $this->summaryService->lockSummaryAndImport($summary_id);

        if($summaryStatus)
            return $this->routerHelper->redirect($this->router, false, 'summary has been locked');

    }
}
