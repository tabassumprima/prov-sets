<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRequest;
use Illuminate\Support\Facades\{Log, DB};
use App\Services\{OrganizationService, UserService, RoleService};
use App\Traits\CheckPermission;

class UserController extends Controller
{
    use CheckPermission;
    private $userService, $router, $routerHelper, $organizationService;

    public function __construct(UserService $userService)
    {
        $this->router       = 'users.index';
        $this->userService  = $userService;
        $this->routerHelper = new RouterHelper();
        $this->organizationService = new OrganizationService();
    }

    public function index()
    {
        $this->authorizePermission('manage user');
        $organization = $this->organizationService->fetch(request()->query('org'));

        $roleService = new RoleService();
        $roles       = $roleService->getAllRoleNamesExceptAdmin();

        $users = $this->userService->getAllUsersExceptAdmin();
        return view('admin.users.index', compact('users', 'roles'));
    }


    public function store(UserRequest $request)
    {
        $error   = false;
        $message = trans('admin/user.created', ['NAME' => $request->name]);
        $request->validated();
        DB::beginTransaction();
        try {
            $organizationService = new OrganizationService();
            $organization        = $organizationService->fetch($request->org);
            if ($organization->checkSubscriptionUsage('max-user') === true)
                $this->userService->create($request);
            else{
                $message = null;
                $error   = false;
            }

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


    public function edit($id)
    {
        $user = $this->userService->fetch($id);

        $roleService = new RoleService();
        $roles = $roleService->getAllRoleNamesExceptAdmin();
        $user_role = $roleService->fetchUserRoleName($user);

        return view('admin.users.edit', compact('user', 'roles', 'user_role'));
    }

    public function update(UserRequest $request, $id)
    {
        $error = false;
        $request = new Request($request->validated());
        $message = trans('admin/user.updated', ['NAME' => $request->name]);
        DB::beginTransaction();
        try {
            $this->userService->update($request, $id);
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

    public function destroy($id)
    {
        DB::beginTransaction();
        $error = false;
        $message = trans('admin/user.deleted');
        try {
            $this->userService->delete($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $error = true;
            $message = 'User cannot be deleted because they have linked records.';
            Log::error($e);
        }

        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect($this->router, $error, $message);
    }

    //status update of user
    public function statusUpdate(Request $request, $id)
    {
        $response =  $this->userService->updateStatus($request, $id);
        return response()->json(['info' => $response], 200);
    }

    public function generate2fa($id)
    {
        $error = false;
        $message = trans('admin/user.keyGenerated');
        try {
            $this->userService->generate2fa($id);
            session()->flash('success', $message);
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            session()->flash('error', $message);
        return response()->json();
    }

    public function impersonate($user)
    {
        $error = false;
        try {
            $userService = $this->userService;
            $userService->impersonate($user);
            $data = $userService->fetch($user);
            $data->addToCalendar('Impersonate', 'Impersonate', 'success');
        } catch (\Exception $e) {
            $error = true;
            $message = $e->getMessage();
            Log::error($e);
        }
        if ($error)
            return $this->routerHelper->redirectBack($error, $message);
        return $this->routerHelper->redirect('user.dashboard', $error, '');
    }

    public function welcomeMail($id)
    {
        try {
            $this->userService->sendWelcomeMail($id);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Welcome Mail Has Been Sent '], 200);
    }

    public function resetMail($id)
    {
        try {
            $this->userService->resetMail($id);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['message' => $e->getMessage()], 500);
        }
        return response()->json(['message' => 'Password Reset Mail Has Been Sent '], 200);
    }
}
