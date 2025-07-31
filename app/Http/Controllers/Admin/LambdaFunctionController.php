<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LambdaFunction\Request;
use App\Services\LambdaFunctionService;
use Illuminate\Support\Facades\Log;

class LambdaFunctionController extends Controller
{
    private $lambdaFunctionService, $router, $routerHelper;

    public function __construct(LambdaFunctionService $lambdaFunctionService)
    {
        $this->router = 'lambda.index';
        $this->lambdaFunctionService = $lambdaFunctionService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $functions = $this->lambdaFunctionService->fetchAll();
        return view('admin.lambda.index', compact('functions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $commands = CustomHelper::lambdaCommands();
        return view('admin.lambda.create', compact('commands'));
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
        $message = trans('admin/lambda.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->lambdaFunctionService->create($request);
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
        $commands = CustomHelper::lambdaCommands();
        $function = $this->lambdaFunctionService->fetch($id);
        return view('admin.lambda.edit', compact('function','commands'));
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
        $message = trans('admin/lambda.updated', ['NAME' => $request->name]);
        $request->validated();
        try {
            $this->lambdaFunctionService->update($request, $id);
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
        //
    }
}
