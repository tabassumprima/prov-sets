<?php

namespace App\Http\Controllers\Admin;

use App\Services\{PermissionService, RoleService, OrganizationService};
use Illuminate\Support\Facades\{DB, Log};
use App\Http\Requests\Role\RoleRequest;
use App\Http\Controllers\Controller;
use App\Helpers\RouterHelper;
use Illuminate\Support\Str;
use Exception;

class RoleController extends Controller
{
    private $roleService, $router, $routerHelper;
    public function __construct(RoleService $roleService)
    {
        $this->router       = 'roles.index';
        $this->roleService  = $roleService;
        $this->routerHelper = new RouterHelper();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizationService = new OrganizationService();
        $organization  = $organizationService->fetch(request('org'));

        $roles = $this->roleService->getAllRoles();
        return view("admin.roles.index", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organizationService = new OrganizationService();
        $organization  = $organizationService->fetch(request('org'));

        $permissionsService = new PermissionService();
        $permissions        = $permissionsService->getAllPermissions($organization->shortcode);
        return view("admin.roles.create", compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $error   = false;
        $message = trans('admin/role.created', ['NAME' => Str::title($request->name)]);

        try {
            DB::beginTransaction();
            $organizationService = new OrganizationService();
            $organization  = $organizationService->fetch(request('org'));

            $this->roleService->createAndSyncPermissions($request, $organization->id);
            DB::commit();
        } catch (Exception $e) {
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
        $organizationService = new OrganizationService();
        $organization  = $organizationService->fetch(request('org'));

        $permissionsService = new PermissionService();
        $role        = $this->roleService->fetchRoleById($id);
        $permissions =  $permissionsService->getAllPermissions($organization->shortcode);
        // dd($role);
        return view("admin.roles.edit", compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        $error = false;
        $message = trans('admin/role.updated', ['NAME' => Str::title($request->name)]);

        try {
            DB::beginTransaction();
            $this->roleService->update($request, $id);
            DB::commit();
        } catch (Exception $e) {
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
        $message = trans('admin/role.deleted');
        try {
            $this->roleService->delete($id);
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
