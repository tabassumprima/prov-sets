<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Services\ImportDetailService;
use App\Services\ReportService;
use App\Http\Requests\ImportDetail\Request;
use App\Http\Controllers\Controller;
use App\Services\SummaryService;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class ImportDetailController extends Controller
{
    use CheckPermission;

    private $importDetailService, $router, $routerHelper;
    public function __construct(ImportDetailService $importDetailService)
    {
        $this->router = 'import-detail.index';
        $this->importDetailService = $importDetailService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-approve-entry');
        $imports = $this->importDetailService->fetchAllImports();
        return view('user.import_detail.index', compact('imports'));
    }

    public function approveEntryIndex()
    {
        $this->authorizePermission('view-approve-entry');
        $provisions = $this->importDetailService->fetchUnapprovedImport(['posting'], ['delta']);
        return view('user.journal_entry.approve-entry-index', compact('provisions'));
    }

    public function importDetailIndex()
    {
        $this->authorizePermission('view-approve-entry');
        $provisions = $this->importDetailService->fetchUnapprovedImport(['default'], ['provision']);
        return view('user.import_detail.import-detail-index', compact('provisions'));
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
        DB::beginTransaction();
        $error = false;
        $message = trans('user/journal_entry.approved');
        try {
            $summaryService = new SummaryService();

            if ($this->importDetailService->hasUnlockedImports($request->start_date, $request->end_date)) {
                throw new Exception('Found existing import. Please lock it before proceeding.');
            }

            if ($summaryService->hasOverlappingDates($request->start_date, $request->end_date)) {
                throw new Exception('The provided dates overlap with or are earlier than an existing import');
            }

            $payload = $this->importDetailService->generateArray('import', 'import', 'default.started', $request->start_date, $request->end_date);
            $import = $this->importDetailService->create($payload);
            $summaryPayload = $summaryService->generateSummaryPayload($import->id, $request->start_date, $request->end_date, 'default.started');
            $summaryService->create($summaryPayload);
            DB::commit();

        }
        catch(Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
            DB::rollBack();
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
    public function destroy(Request $request, $id)
    {
        $this->authorizePermission('delete-approve-entry');
        $message = "All Journal Entries with import id ". $id . " has been deleted";
        DB::beginTransaction();
        try {
            $provision = $this->importDetailService->delete($id);
            $this->importDetailService->saveMessage($provision,$request);
            $this->importDetailService->lockProvision($provision);
            $this->importDetailService->changeStatus($provision, 'revoked');
            ReportService::invalidate();
            DB::commit();
            return $this->routerHelper->redirect($this->router, false, $message);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->routerHelper->redirectBack(true, $e->getMessage());
        }
    }

    public function approveEntries($import)
    {
        $this->authorizePermission('approve-approve-entry');
        $message = "All Journal Entries with import id ". $import . " has been approved";
        DB::beginTransaction();
        try {
            $import = $this->importDetailService->approveEntriesByImport($import);
            $this->importDetailService->lockProvision($import);
            DB::commit();
            return $this->routerHelper->redirect($this->router, false, $message);
        }
        catch(Exception $e) {
            DB::rollBack();
            return $this->routerHelper->redirectBack(true, $e->getMessage());
        }
    }

    public function approveJournal($journal_id)
    {
        $this->authorizePermission('approve-approve-entry');
        $message = "All Journal Entries has been approved";
        DB::beginTransaction();
        try {
            $provision = $this->importDetailService->approveEntriesByImport($journal_id);
            $this->importDetailService->lockProvision($provision);
            DB::commit();
            session()->flash('success', $message);
            return response()->json();
        }
        catch(Exception $e) {
            session()->flash('success', $message);
            DB::rollBack();
            Log::info($e);
            return response()->json();
        }
    }
}
