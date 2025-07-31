<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ProvisionService;
use Illuminate\Support\Facades\Log;
use App\Traits\CheckPermission;
use Exception;

class ProvisionController extends Controller
{
    use CheckPermission;
    
    private $provisionService;

    public function __construct(ProvisionService $provisionService)
    {
        $this->provisionService = $provisionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorizePermission('view-provision-output');
        $provisions = $this->provisionService->fetchAllLatestProvision();
        return view('user.provisions.index', compact('provisions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorizePermission('view-provision-output');
        $provision_files = $this->provisionService->listFiles($id);
        return view('user.provisions.show', compact('provision_files', 'id'));
    }

    public function downloadFile($id, $filename)
    {
        try {
            return $this->provisionService->downloadFile($id, $filename);
        }catch(Exception $e){
            Log::error($e);
            return redirect()->back()->with('error', "something went wrong");
        }
    }
}
