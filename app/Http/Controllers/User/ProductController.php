<?php

namespace App\Http\Controllers\User;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\{ProductService, GroupService, CohortsCodeService, GroupProductService, MeasurementModelService, PortfolioService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    private $productService, $router, $routerHelper;

    public function __construct(ProductService $productService)
    {
        $this->router = 'groups.index';
        $this->productService = $productService;
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
        
        $departments = $this->productService->fetchAllWithRelatedData($group);

        return view('user.product.create', compact('group', 'departments'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
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
