<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{FacultativeService, GroupProductTreatyService, GroupService, GroupProductFacultativeService, TreatyService};
use App\Http\Requests\GroupProduct\GroupProductRequest;
use Illuminate\Support\Facades\Log;

class GroupProductReinsuranceController extends Controller
{
    private $router, $routerHelper;
    public function __construct()
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);

        $this->router        = 'groups.re-insurance';
        $this->routerHelper  = new RouterHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($group)
    {
        $groupService = new GroupService();
        $group        = $groupService->fetch($group);
        return view('user.product.re-insurance.index', compact('group'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function createFacultative($group)
    {
        $groupService = new GroupService();
        $group        = $groupService->fetch($group);

        $facultativeService = new FacultativeService();
        $departments        = $facultativeService->fetchAllWithRelatedData($group);

        return view('user.product.re-insurance.facultative.create', compact('group', 'departments'));
    }

    public function createTreaty($group)
    {
        $groupService = new GroupService();
        $group        = $groupService->fetch($group);

        $treatyService = new TreatyService();
        $departments   = $treatyService->fetchAllWithRelatedData($group);

        return view('user.product.re-insurance.treaty.create', compact('group', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFacultative(GroupProductRequest $request)
    {
        $error   = false;
        $message = trans('user/group.mapping_complete');
        try {
            $groupProductFacultativeService = new GroupProductFacultativeService();
            $groupProductFacultativeService->create($request);
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

    public function storeTreaty(GroupProductRequest $request)
    {
        $error = false;
        $message = trans('user/group.mapping_complete');
        try {
            $groupProductTreatyService = new GroupProductTreatyService();
            $groupProductTreatyService->create($request);
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
}
