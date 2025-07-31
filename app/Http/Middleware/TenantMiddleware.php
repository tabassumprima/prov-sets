<?php

namespace App\Http\Middleware;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use App\Services\OrganizationService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Closure;
use Exception;

class TenantMiddleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle(Request $request, Closure $next)
    {  
        try {
            if ($organizationParam = request('org'))
                $request->session()->put('org', $organizationParam);
            else
                $organizationParam = $request->session()->get('org');

            if ($organizationParam) {
                $organizationService = new OrganizationService();
                $organization = $organizationService->fetch($organizationParam);
                if ($organization) {
                    $request->query->set('org', $organizationParam);
                    $request->query->set('org_name', $organization->name);

                    //Find Model name and path
                    $action = $request->route()->getAction();
                    $controller = class_basename($action['controller']);
                    $model_name = 'App\\Models\\' . Str::before($controller, 'Controller');

                    // check if model exist
                    if (class_exists($model_name)) {
                        $model = new $model_name;
                        $table = $model->getTable();
                        if (Schema::hasColumn($table, 'organization_id')) {
                            $model::addGlobalScope('organization_id', function (Builder $builder) use ($organization) {
                                $builder->where('organization_id', $organization->id);
                            });
                            $request->merge(['organization_id' => $organization->id]);
                        }
                    }
                    return $next($request);
                }
            }

            $request->session()->forget('org');
            return redirect()->route('tenant.index')->with(['error' => 'Please select organization first!']);
        }
        catch(Exception $e){
            $request->session()->forget('org');
            return redirect()->route('tenant.index')->with(['error' => 'Please select organization first!']);
        }
    }
}
