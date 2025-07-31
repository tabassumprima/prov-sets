<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LambdaSubFunction\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\RouterHelper;
use App\Services\GlCodeService;
use App\Services\LambdaEntryService;
use App\Services\LambdaFunctionService;
use App\Services\LambdaSubFunctionService;
use App\Services\LevelService;
use Illuminate\Support\Facades\DB;

class LambdaSubFunctionController extends Controller
{
    private $LambdaSubFunctionService, $router, $routerHelper;

    public function __construct(LambdaSubFunctionService $LambdaSubFunctionService)
    {
        $this->router = 'lambda-sub-functions.index';
        $this->LambdaSubFunctionService = $LambdaSubFunctionService;
        $this->routerHelper = new RouterHelper;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCommands = $this->LambdaSubFunctionService->fetchAllWithRelations('lambda');
        return view('admin.sub_commands.index', compact('subCommands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subCommands = CustomHelper::lambdaCommands();
        
        $glcodeService = new GlCodeService;
        $glcodes = $glcodeService->fetchAll();

        $levelService = new LevelService;
        $levels = $levelService->fetchAll();

        $lambdaService = new LambdaFunctionService;
        $lambdas = $lambdaService->fetchAll();
        return view('admin.sub_commands.create', compact('glcodes', 'levels', 'lambdas', 'subCommands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SubCommand\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $lambdaEnriesService = new LambdaEntryService;
        DB::beginTransaction();
        $error = false;
        $message = trans('admin/sub_command.created', ['NAME' => $request->name]);
        $request->validated();
        try {
            $sub_function = $this->LambdaSubFunctionService->create($request);
            $lambdaEnriesService->create($request, $sub_function->id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
        return view('admin.sub_commands.create');
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
        $subCommands = CustomHelper::lambdaCommands();

        $glcodeService = new GlCodeService;
        $glcodes = $glcodeService->fetchAll();

        $levelService = new LevelService;
        $levels = $levelService->fetchAll();

        $lambdaService = new LambdaFunctionService;
        $lambdas = $lambdaService->fetchAll();

        $lambdaSubFunction = $this->LambdaSubFunctionService->fetchWithRelation(['lambdaEntries'], $id);
        $entries = $lambdaSubFunction->lambdaEntries;
        return view('admin.sub_commands.edit', compact('lambdaSubFunction', 'glcodes', 'levels', 'lambdas', 'entries', 'subCommands'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SubCommand\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $error = false;
        $message = trans('admin/sub_command.updated', ['NAME' => $request->name]);
        $request->validated();
        DB::beginTransaction();
        try {
            $this->LambdaSubFunctionService->update($request, $id);
            $this->LambdaSubFunctionService->syncEntries($request, $id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
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
        $message = trans('admin/sub_command.deleted');
        DB::beginTransaction();
        try {
            $this->LambdaSubFunctionService->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }
}
