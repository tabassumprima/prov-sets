<?php

namespace App\Http\Controllers\Admin;

use App\Services\OrganizationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $organizationService = new OrganizationService();
        $organizations = $organizationService->getPaginateData($request->search);

        //forget remove param from both session and request param
        $request->request->remove('org');
        $request->session()->forget('org');
        return view('admin.tenant.index', compact('organizations'));
    }
}
