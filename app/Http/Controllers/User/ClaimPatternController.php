<?php

namespace App\Http\Controllers\User;

use App\Helpers\{RouterHelper, CustomHelper};
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Services\ClaimPatternService;
use App\Services\OrganizationService;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Http\Request;
use App\Http\Requests\ClaimPattern\ClaimPatternRequest;
use Exception;


class ClaimPatternController extends Controller
{
    use CheckPermission;

    private $claimPatternService, $router, $routerHelper;
    public function __construct(ClaimPatternService $claimPatternService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router              = 'claim-patterns.index';
        $this->claimPatternService = $claimPatternService;
        $this->routerHelper        = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-claim-pattern');
        $organizationService = new OrganizationService();
        $isboarding = $organizationService->isBoarding();
        $claimPatterns = $this->claimPatternService->fetchAllWithStatus();
        return view("user.claim_patterns.index", compact('claimPatterns','isboarding'));
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
    public function store(ClaimPatternRequest $request)
    {
        $this->authorizePermission('create-claim-pattern');

        $error   = false;
        $message = 'You have successfully added new claim apptern.';
        DB::beginTransaction();
        try {
            $this->claimPatternService->create($request);
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
        $claimPattern = $this->claimPatternService->fetch($id);
        $record = $claimPattern;
        return view("user.claim_patterns.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClaimPatternRequest $request, $file_id)
     {
         $error   = false;
         $message = 'You have successfully updated Claim Pattern Information.';

         try {
            DB::beginTransaction();
            $this->claimPatternService->update($request, $file_id);
            DB::commit();
        }
        catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
         }
         if ($error) {
            return redirect()->back()->with('error', $message);
         }
         return redirect()->route('claim-patterns.index')
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
            $this->claimPatternService->delete($id);
            return redirect()->route('claim-patterns.index')->with('success', 'The record has been successfully deleted.');
        } catch (Exception $e) {
            if(Str::contains($e->getMessage(),'foreign key constraint')){
                $message = 'The file is being used in a provision and cannot be deleted.';
            }
            else{
                $message = $e->getMessage();
            }
        return redirect()->route('claim-patterns.index')->with('error', $message);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $this->authorizePermission('update-status-claim-pattern');

        $value = $request->value;

        if($value == 'started')
            $slug = 'not-started';
        else
            $slug = 'started';

        $claim_pattern            = $this->claimPatternService->fetch($id);
        $claim_pattern->status_id = CustomHelper::fetchStatus($slug);
        $claim_pattern->save();
        return $claim_pattern;
    }
}
