<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\ExpenseAllocation\Request;
use App\Services\ExpenseAllocationService;
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;
use App\Services\OrganizationService;
use App\Services\ProvisionSettingService;

class ExpenseAllocationController extends Controller
{
    private $expenseAllocationService, $router, $routerHelper;

    public function __construct(ExpenseAllocationService $expenseAllocationService)
    {
        $this->router = 'expense-allocation.create';
        $this->expenseAllocationService = $expenseAllocationService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenseAllocations = $this->expenseAllocationService->fetchAll();
        return view('user.expense_allocation.index', compact('expenseAllocations','isBoarding'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($provisionSettingId)
    {
        $provisionSettingService = new ProvisionSettingService();
        $organizationService      = new OrganizationService();
        $isBoarding = $organizationService->isBoarding();
        $provisionSetting         = $provisionSettingService->fetch($provisionSettingId);
        $data = $this->expenseAllocationService->fetchData($provisionSettingId);
        $data['provisionSetting'] = $provisionSetting;
        $data['isBoarding'] = $isBoarding;
        return view('user.expense_allocation.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ExpenseAllocation\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $provisionSetting)
    {
        $error = false;
        $message = trans('user/expense_allocation.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->expenseAllocationService->create($request, $provisionSetting);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return response()->json(['message' => $message], 400);
        session()->flash('success', $message);
        return response()->json(['message' => $message], 200);
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
        $currency = $this->expenseAllocationService->fetch($id);
        return view('user.expense_allocation.edit', compact('currency'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ExpenseAllocation\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $message = trans('user/expense_allocation.updated', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->expenseAllocationService->update($request, $id);
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
        $message = trans('user/expense_allocation.deleted');
        try {
            $this->expenseAllocationService->delete($id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }
}
