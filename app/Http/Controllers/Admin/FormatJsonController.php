<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\JsonReport\Request;
use App\Services\FormatJsonService;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\{DB, Storage, Log};
use Exception;


class FormatJsonController extends Controller
{
    private $service, $routerHelper, $router;
    public function __construct()
    {
        $this->router       = 'report-format.index';
        $this->service      = new FormatJsonService();
        $this->routerHelper = new RouterHelper();
    }

    public function index()
    {
        $formatJsonReports = $this->service->fetchAll();
        return view('admin.json_format.index', compact('formatJsonReports'));
    }

    public function create()
    {
        $organizationService = new OrganizationService();
        $organizations = $organizationService->fetchAll();
        return view('admin.json_format.create', compact("organizations"));
    }

    public function store(Request $request)
    {
        $error   = false;
        $message = 'You have successfully added new report format json.';
        DB::beginTransaction();
        try {
            $this->service->create($request);
            DB::commit();
        } catch (Exception $e) {
            Log::error($e);
            DB::rollBack();
            $error   = true;
            $message = $e->getMessage();
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    public function destroy($id)
    {
        $error   = false;
        $message = 'You have successfully deleted.';
        try {
            $this->service->delete($id);
        } catch (Exception $e) {
            Log::error($e);
            $error   = true;
            $message = $e->getMessage();
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    // Download Files
    public function getReportFormatFile($id)
    {
        $error    = false;
        $message  = 'File download';
        try {
            return $this->service->downloadFile($id);
        } catch (Exception $e) {
            $error   = true;
            $message = $e->getMessage();
            Log::error($e);
        }

        return $this->routerHelper->redirectBack($error, $message);
    }
}
