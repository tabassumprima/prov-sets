<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Criteria\Request;
use App\Services\{CriteriaService, OrganizationService, StatusService, SystemDepartmentService};
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;
use App\Traits\CheckPermission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CriteriaController extends Controller
{
    use CheckPermission;

    private $criteriaService, $router, $routerHelper;
    public function __construct(CriteriaService $criteriaService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);

        $this->router = 'criteria.index';
        $this->criteriaService = $criteriaService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $this->authorizePermission('view-' . $type . '-portfolio-criteria');

        $unsortedCriterias     = $this->criteriaService->fetchColumns(['*'], $type)->load('status');
        $order = ['expired' => 3, 'started' => 2, 'not-started' => 1];
        $criterias =  $unsortedCriterias->sortByDesc(fn ($item) => $order[$item->status->slug])->values();
        $dateStartFrom = $this->criteriaService->availableFutureDate();
        $organizationService = new OrganizationService();
        $isBoarding          = $organizationService->isBoarding();
        return view('user.criterias.index', compact('criterias', 'dateStartFrom', 'isBoarding', 'type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorizePermission('create-' . $request->applicable_to . '-portfolio-criteria');
        $error   = false;
        $message = trans('user/criteria.created');

        try {
            $this->criteriaService->create($request);

            $statusService = new StatusService;
            $expired = $statusService->fetchStatusByModelSlug('criteria', 'expired')->id;

            $this->criteriaService->updateEndingCriteria(Carbon::now()->startOfDay()->toDateString(), $expired);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        $params = ['type' => $request->applicable_to];
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message, $params);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $systemDepartmentService = new SystemDepartmentService;
        $departments = $systemDepartmentService->fetchAll();

        $portfolio = $this->criteriaService->fetch($id);
        return view('user.criterias.edit', compact('portfolio', 'departments'));
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
        $error = false;
        $message = trans('user/criteria.updated');

        try {
            $this->criteriaService->update($request, $id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        $params = ['type' => $request->applicable_to];
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message, $params);
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
        $message = trans('user/criteria.deleted');
        DB::beginTransaction();
        try {
            $portfolio = $this->criteriaService->fetch($id);
            $this->authorizePermission('delete-' . $portfolio->applicable_to . '-portfolio-criteria');

            $response = $this->criteriaService->delete($id);
            if (!$response['success']) {
                $error = true;
                $message = $response['message'];
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        return $this->routerHelper->redirectBack($error, $message);
    }
}
