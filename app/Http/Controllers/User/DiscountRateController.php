<?php

namespace App\Http\Controllers\User;

use App\Helpers\{RouterHelper, CustomHelper};
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Services\DiscountRateService;
use App\Services\OrganizationService;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\{DB, Log};
use Illuminate\Http\Request;
use App\Http\Requests\DiscountRate\DiscountRateRequest;
use Exception;


class DiscountRateController extends Controller
{
    use CheckPermission;
    private $discountRateService, $router, $routerHelper;
    public function __construct(DiscountRateService $discountRateService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router = 'discount-rates.index';
        $this->discountRateService = $discountRateService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-discount-rate');
        $organizationService = new OrganizationService();
        $isboarding = $organizationService->isBoarding();
        $discountRates = $this->discountRateService->fetchAllWithStatus();
        return view("user.discount_rates.index", compact('discountRates','isboarding'));
    }
public function indexgmm()
    {
        $this->authorizePermission('view-discount-rate-gmm');
        $organizationService = new OrganizationService();
        $isboarding = $organizationService->isBoarding();
        $discountRates = $this->discountRateService->fetchAllWithStatus();
        return view("user.discount_rates_gmm.index", compact('discountRates','isboarding'));
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
    public function store(DiscountRateRequest $request)
    {
        $this->authorizePermission('create-discount-rate');
        $error   = false;
        $message = 'You have successfully added new discount rate.';
        DB::beginTransaction();
        try {
            $this->discountRateService->create($request);
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
        $discountRate = $this->discountRateService->fetch($id);
        $record = $discountRate;
        return view("user.discount_rates.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DiscountRateRequest $request, $file_id)
     {
         $error   = false;
         $message = 'You have successfully updated Discount Rate Information.';
         try {
             DB::beginTransaction();
             $this->discountRateService->update($request, $file_id);
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
        return redirect()->route('discount-rates.index')
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
            $this->discountRateService->delete($id);
            return redirect()->route('discount-rates.index')->with('success', 'The record has been successfully deleted.');
        } catch (Exception $e) {
            if(Str::contains($e->getMessage(),'foreign key constraint')){
                $message = 'The file is being used in a provision and cannot be deleted.';
            }
            else{
                $message = $e->getMessage();
            }
        return redirect()->route('discount-rates.index')->with('error', $message);
        }
    }

    public function statusUpdate(Request $request, $id)
    {
        $this->authorizePermission('update-status-discount-rate');
        $value         = $request->value;
        $discount_rate = $this->discountRateService->fetch($id);
        if($value == 'started'){
            if ($discount_rate->provisionMappings()->exists()) {
                session()->flash('error', "Discount rate file is already being used");
                return response()->json();
            }
            $slug = 'not-started';
        }
        else
            $slug = 'started';

        $discount_rate->status_id = CustomHelper::fetchStatus($slug);
        $discount_rate->save();
        return $discount_rate;
    }
}
