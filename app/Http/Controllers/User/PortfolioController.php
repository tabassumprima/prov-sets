<?php

namespace App\Http\Controllers\User;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portfolio\Request;
use App\Services\{CriteriaService, PortfolioService, SystemDepartmentService};
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;
use App\Traits\CheckPermission;
use Illuminate\Support\Facades\DB;

class PortfolioController extends Controller
{
    use CheckPermission;

    private $portfolioService, $router, $routerHelper;
    public function __construct(PortfolioService $portfolioService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router           = 'portfolio.index';
        $this->portfolioService = $portfolioService;
        $this->routerHelper     = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        $this->authorizePermission('view-' . $type . '-portfolio');
        $portfolios = $this->portfolioService->fetchColumns(["*"], $type);
        return view('user.portfolio.index', compact('portfolios', 'type'));
    }

    public function create($criteria)
    {
        $criteriaService = new CriteriaService();
        $criteria = $criteriaService->fetch($criteria);

        $isBoarding = $criteria->organization->isBoarding;

        $systemDepartmentsService = new SystemDepartmentService;
        $departments = $systemDepartmentsService->fetchSystemDepartments($criteria->id);

        return view('user.portfolio.create', compact('criteria', 'departments', 'isBoarding'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorizePermission('create-' . $request->type . '-portfolio');
        DB::beginTransaction();
        $error = false;
        $message = trans('admin/portfolio.created');
        try {
            $this->portfolioService->create($request);
            DB::commit();
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
            DB::rollBack();
        }

        $params = ['type' => $request->type];
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
        $portfolio = $this->portfolioService->fetch(CustomHelper::decode($id));
        $this->authorizePermission('update-' . $portfolio->type . '-portfolio');
        return view('user.portfolio.edit', compact('portfolio'));
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
        $message = trans('admin/portfolio.updated');
        try {
            $this->portfolioService->update($request, $id);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        $params = ['type' => $request->type];
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
        $message = trans('admin/portfolio.deleted');
        try {
            $portfolio = $this->portfolioService->fetch(CustomHelper::decode($id));
            if ( CustomHelper::has_any_relations($portfolio, ['FacGroupCode', 'GroupFacultative', 'GroupProduct'])) {
                throw new \Exception('Portfolio cannot be deleted because it has linked group codes.');
            }

            $this->authorizePermission('delete-' . $portfolio->type . '-portfolio');

            $this->portfolioService->delete(CustomHelper::decode($id));
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        return $this->routerHelper->redirectBack($error, $message);
    }

    public function saveMapping(Request $request)
    {
        DB::beginTransaction();
        $error = false;
        $message = trans('admin/portfolio.created');
        try {
            $portfolio = $this->portfolioService->saveMapping($request);
            $params = ['type' => $portfolio->type];
            DB::commit();
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
            DB::rollBack();
        }


        if ($error)
            return response()->json(['message' => $message], 500);
        $params = ['type' => $portfolio->type];
        return $this->routerHelper->redirect($this->router, $error, $message, $params);
    }

    public function showMapping($criteria)
    {
        $criteriaService = new CriteriaService();
        $criteria = $criteriaService->fetch($criteria);

        $portfolios = $this->portfolioService->fetchColumns(['id', 'name'], $criteria->applicable_to);

        $isBoarding = $criteria->organization->isBoarding;

        $systemDepartmentsService = new SystemDepartmentService();
        $departments = $systemDepartmentsService->fetchSystemDepartments($criteria->id);

        return view('user.portfolio.mapping', compact('criteria', 'departments', 'isBoarding', 'portfolios'));
    }
}
