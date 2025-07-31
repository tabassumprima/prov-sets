<?php

namespace App\Http\Middleware;

use App\Services\ImportDetailService;
use App\Services\OrganizationService;
use Closure;
use Illuminate\Http\Request;

class ProvisionAlert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $import_detail_service = new ImportDetailService;

        if ($import_detail_service->fetchUnapprovedImport(['default'], ['provision'])->count() > 0){
            $request->session()->forget('active_provision');
            $request->session()->put('provision_alert', value: 'Provision');

        }
        else if ($import_detail_service->fetchUnapprovedImport(['default'], ['import'])->count() > 0){
            $request->session()->forget('active_provision');
            $request->session()->put('provision_alert', value: 'Import');

        } 
        else if ($import_detail_service->fetchUnapprovedImport(['posting'], ['delta'])->count() > 0) {
                $request->session()->forget('active_provision');
                $request->session()->put('provision_alert', value: 'Posting');
    
        }
        else if ($import_detail_service->fetchUnapprovedImport(['default'], ['import'], ['running', 'rollback-inprogress'])->count() > 0){
            $request->session()->forget('provision_alert');
            $request->session()->put('active_provision', value: 'import');
        }
        else{
        // dd("ss");
            $request->session()->forget('provision_alert');
            // $request->session()->forget('active_provision');

        }
        return $next($request);
    }
}
