<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LambdaEntry\Request;
use App\Services\LambdaEntryService;
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;

class LambdaEntryController extends Controller
{
    private $lambdaEntryService, $router, $routerHelper;

    public function __construct(LambdaEntryService $lambdaEntryService)
    {
        $this->router = 'lambda-entries.index';
        $this->lambdaEntryService = $lambdaEntryService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lambdaEntries = $this->lambdaEntryService->fetchAllWithRelations(['lambda', 'glcode', 'level']);
        return view('admin.lambda_entries.index', compact('lambdaEntries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.lambda_entries.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LambdaEntry\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = false;
        $message = trans('admin/lambda_entry.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->lambdaEntryService->create($request);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
        return view('admin.lambda_entries.create');
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
        $currency = $this->lambdaEntryService->fetch($id);
        return view('admin.lambda_entries.edit', compact('currency'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LambdaEntry\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $message = trans('admin/lambda_entry.updated', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->lambdaEntryService->update($request, $id);
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
        $message = trans('admin/lambda_entry.deleted');
        try {
            $this->lambdaEntryService->delete($id);
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
