<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\GroupProductService;
use App\Services\GroupService;
use App\Services\ProductService;
use App\Http\Requests\GroupProduct\GroupProductRequest;
use Illuminate\Support\Facades\Log;

class GroupProductController extends Controller
{
    private $groupProductService, $router, $routerHelper;
    public function __construct(GroupProductService $groupProductService)
    {
        $this->middleware('prevent_transaction', ['except' => ['index', 'show']]);
        $this->router = 'groups.index';
        $this->groupProductService = $groupProductService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($group)
    {
        $groupService = new GroupService();
        $group = $groupService->fetch($group);

        $productService = new ProductService();
        $departments    = $productService->fetchAllWithRelatedData($group);

        return view('user.product.create', compact('group', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupProductRequest $request)
    {
        $error = false;
        $message = trans('user/group.mapping_complete');
        try {
            $this->groupProductService->create($request);
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
        //
    }
}
