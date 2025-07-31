<?php

namespace App\Http\Controllers\User;

use App\Helpers\{CustomHelper, RouterHelper};
use App\Http\Requests\DiscountRate\Request;
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Controllers\Controller;
use App\Services\{DiscountRateService,DiscountRateFileService};
use App\Traits\CheckPermission;
use Illuminate\Support\Str;
use Exception;

class DiscountRateFileController extends Controller
{
    use CheckPermission;
    
    private $discountRateService, $router, $routerHelper;
    public function __construct(DiscountRateService $discountRateService,DiscountRateFileService $discountRateFileService)
    {
        $this->router = 'discount_rate_files.index';
        $this->discountRateService = $discountRateService;
        $this->discountRateFileService = $discountRateFileService;
        $this->routerHelper = new RouterHelper;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($discount_rate_id)
    {
        $this->authorizePermission('view-discount-rate-file');
        $discount_rate = $this->discountRateService->fetchWithRelations($discount_rate_id, ['files']);
        return view("user.discount_rates.files.index", compact("discount_rate"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($discounts_rate_id, Request $request)
    {
        $this->authorizePermission('create-discount-rate-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully added new discount rate file.';
        try {
            DB::beginTransaction();
            $this->discountRateService->createFile($discounts_rate_id, $request);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();

            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
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
    public function edit($discount_rate_id, $file_id)
    {
        $this->authorizePermission('update-discount-rate-file');
        $record = $this->discountRateFileService->fetch($file_id);
        return view("user.discount_rates.files.edit", compact("record"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($discount_rate_id, Request $request, $file_id)
    {
        $this->authorizePermission('update-discount-rate-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully update file.';
        try {
            DB::beginTransaction();
            $this->discountRateFileService->update($request, $file_id);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
        return redirect()->route('discount-rates.files.index', ['discount_rate' => $discount_rate_id])->with(['success' => $message]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($discount_rate_id, $file_id)
    {
        $this->authorizePermission('delete-discount-rate-file');
        $error        = false;
        $fileDownload = false;
        $message      = 'You have successfully delete file.';
        try {
            DB::beginTransaction();
            $this->discountRateFileService->delete($file_id);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $message = $e->getMessage();
            $error   = true;return $this->routerHelper->redirectBack($error, $message);
            if(Str::contains($e->getMessage(), ['There', 'were']))
                $fileDownload = true;
        }

        if ($error)
            return redirect()->back()->with(['error' => $message, 'file' => $fileDownload]);
        return $this->routerHelper->redirectBack($error, $message);
    }

    public function getFile($id)
    {   
        $this->authorizePermission('download-discount-rate-file');
        return CustomHelper::downloadFiles($id,'discount_rates');
    }
    
    public function getErrorFile()
    {   
        return CustomHelper::downloadInvalidData();
    }

    public function fetchFile($file)
    {
        return CustomHelper::getFileData($file,'discount_rates');
    }
}
