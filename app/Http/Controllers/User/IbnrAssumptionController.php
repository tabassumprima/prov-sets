<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\{DB, Log};
use App\Services\IbnrAssumptionService;
use App\Services\OrganizationService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Helpers\{RouterHelper, CustomHelper};
use App\Traits\CheckPermission;
use Illuminate\Http\Request;
use App\Http\Requests\IbnrFile\IbnrRequest;
use Exception;

class IbnrAssumptionController extends Controller
{
    use CheckPermission;

    private $ibnrAssumptionService, $router, $routerHelper;
    public function __construct(IbnrAssumptionService $ibnrAssumptionService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router = 'ibnr-assumptions.index';
        $this->ibnrAssumptionService = $ibnrAssumptionService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-ibnr-assumption');
        $organizationService = new OrganizationService();
        $isboarding = $organizationService->isBoarding();
        $records = $this->ibnrAssumptionService->fetchAllWithStatus();
        return view("user.ibnr_assumptions.index", compact('records','isboarding'));
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
    public function store(IbnrRequest $request)
    {
        $this->authorizePermission('create-ibnr-assumption');

        $error   = false;
        $message = 'You have successfully added new record.';
        DB::beginTransaction();
        try {
            $this->ibnrAssumptionService->create($request);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
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
        $ibnrAssumption = $this->ibnrAssumptionService->fetch($id);
        $record = $ibnrAssumption;
        return view("user.ibnr_assumptions.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function update(IbnrRequest $request, $file_id)
     {
         $error   = false;
         $message = 'You have successfully updated IBNR Assumption Information.';
         try {
             DB::beginTransaction();
             $this->ibnrAssumptionService->update($request, $file_id);
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
         return redirect()->route('ibnr-assumptions.index')
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
            $this->ibnrAssumptionService->delete($id);
            return redirect()->route('ibnr-assumptions.index')->with('success', 'The record has been successfully deleted.');

        } catch (Exception $e) {
            if(Str::contains($e->getMessage(),'foreign key constraint')){
                $message = 'The file is being used in a provision and cannot be deleted.';
            }
            else{
                $message = $e->getMessage();
            }
        return redirect()->route('ibnr-assumptions.index')->with('error', $message);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $this->authorizePermission('update-status-ibnr-assumption');

        $value          = $request->value;
        $ibnrAssumption = $this->ibnrAssumptionService->fetch($id);

        if($value == 'started'){
            if ($ibnrAssumption->provisionMappings()->exists()) {
                session()->flash('error', "Ibnr Assumption file is already being used");
                return response()->json();
            }
            $slug = 'not-started';
        }
        else
            $slug = 'started';

        $ibnrAssumption->status_id = CustomHelper::fetchStatus($slug);
        $ibnrAssumption->save();
        return $ibnrAssumption;
    }
}
