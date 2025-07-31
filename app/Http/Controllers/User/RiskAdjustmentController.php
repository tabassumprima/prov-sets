<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\{DB, Log};
use App\Services\RiskAdjustmentService;
use App\Services\OrganizationService;
use App\Http\Controllers\Controller;
use App\Helpers\{RouterHelper, CustomHelper};
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use App\Http\Requests\RiskAdjustment\RiskAdjustmentRequest;
use Illuminate\Support\Str;
use Exception;

class RiskAdjustmentController extends Controller
{
    use CheckPermission;

    private $riskAdjustmentService, $router, $routerHelper;
    public function __construct(RiskAdjustmentService $riskAdjustmentService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router = 'risk-adjustments.index';
        $this->riskAdjustmentService = $riskAdjustmentService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-risk-adjustment');

        $records = $this->riskAdjustmentService->fetchAllWithStatus();
        $organizationService = new OrganizationService();
        $isboarding = $organizationService->isBoarding();
        return view("user.risk_adjustments.index", compact('records','isboarding'));
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
    public function store(RiskAdjustmentRequest $request)
    {
        $this->authorizePermission('create-risk-adjustment');

        $error   = false;
        $fileDownload = false;
        $message = 'You have successfully added new record.';
        DB::beginTransaction();
        try {
            $this->riskAdjustmentService->create($request);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;

            $message = $e->getMessage();
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, "file" => $fileDownload]);
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
        $riskAdjustment = $this->riskAdjustmentService->fetch($id);
        $record = $riskAdjustment;
        return view("user.risk_adjustments.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RiskAdjustmentRequest $request, $file_id)
    {
        $error   = false;
        $message = 'You have successfully updated Risk Adjusments Information.';
        try {
            DB::beginTransaction();
            $this->riskAdjustmentService->update($request, $file_id);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
        }
        if ($error) {
            return redirect()->back()->with('error', $message);
        }
        return redirect()->route('risk-adjustments.index')
                        ->with('success', $message);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->riskAdjustmentService->delete($id);
            return redirect()->route('risk-adjustments.index')->with('success', 'The record has been successfully deleted.');

        } catch (Exception $e) {
            if(Str::contains($e->getMessage(),'foreign key constraint')){
                $message = 'The file is being used in a provision and cannot be deleted.';
            }
            else{
                $message = $e->getMessage();
            }
        return redirect()->route('risk-adjustments.index')->with('error', $message);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $this->authorizePermission('update-status-risk-adjustment');

        $value = $request->value;
        $riskAdjustment = $this->riskAdjustmentService->fetch($id);

        if($value == 'started'){
            if ($riskAdjustment->provisionMappings()->exists()) {
                session()->flash('error', "Risk adjustment file is already being used");
                return response()->json();
            }
            $slug = 'not-started';
        }
        else
            $slug = 'started';


        $riskAdjustment->status_id = CustomHelper::fetchStatus($slug);
        $riskAdjustment->save();
        return $riskAdjustment;
    }
}
