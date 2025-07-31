<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\ChartOfAccountService;
use App\Services\OrganizationService;
use App\Http\Requests\ChartOfAccount\Request;

class ChartOfAccountController extends Controller
{
    private $chartOfAccountService, $router, $routerHelper;

    public function __construct(ChartOfAccountService $chartOfAccountService)
    {
        $this->router = 'chart-of-account.index';
        $this->chartOfAccountService = $chartOfAccountService;
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
    public function create()
    {
        return view('admin.chart-of-account.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = false;
        $message = "Csv Uploaded";
        try{
            $this->chartOfAccountService->uploadInitCsv($request);
        }catch(\Exception $e){
            $error = true;
            $message = $e->getMessage();
        }
        return $this->routerHelper->redirectBack($error, $message);
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

    // Download Chart of account file
    public function downloadFile()
    {
        $error = false;
        $message = 'Something went wrong';
        try{
            $file = $this->chartOfAccountService->downloadFileData();
            if ($file !== null) {
                return $file;
            } else {
                throw new \Exception("Uploaded file does not exist");
            }
        }catch(\Exception $e){
            $error = true;
            $message = $e->getMessage();
        }
        return $this->routerHelper->redirectBack($error, $message);
    }
}
