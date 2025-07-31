<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\RouterHelper;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\User\UserRequest;
use Illuminate\Support\Facades\{DB, Log};

class AdminUserController extends Controller
{
    private $userService, $router, $routerHelper;

    public function __construct(UserService $userService)
    {
        $this->router = 'admin-users.index';
        $this->userService = $userService;
        $this->routerHelper = new RouterHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = $this->userService->getAllUsersWithRoleAdmin();
        return view('admin.admins.index', compact('users'));
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
    public function store(UserRequest $request)
    {
        $error = false;
        $request->validated();
        $message = trans('admin/user.created', ['NAME' => $request->name]);
        DB::beginTransaction();
        try {
            $this->userService->create($request);
            DB::commit();
        }catch(\Exception $e)
        {
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
        $user = $this->userService->fetch($id);

    return view('admin.admins.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $error = false;
        $request->validated();
        $message = trans('admin/user.updated', ['NAME' => $request->name]);
        DB::beginTransaction();
        try{
            $this->userService->update($request, $id);
            DB::commit();
        }
        catch(\Exception $e)
        {
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
        $message = trans('admin/user.deleted');
        try{
            $this->userService->delete($id);
        }
        catch(\Exception $e)
        {
            $error = true;
            $message = $e->getMessage();
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
         }
         catch(\Exception $e){
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
             $this->userService->impersonate($user);
         }catch(\Exception $e)
         {
             $error = true;
             $message = $e->getMessage();
             Log::error($e);
         }
         if($error)
             return $this->routerHelper->redirectBack($error, $message);
         return $this->routerHelper->redirect('user.dashboard', $error, '');
     }

     public function welcomeMail($id)
     {
         try{
             $this->userService->sendWelcomeMail($id);
         }catch(\Exception $e){
             Log::error($e);
             return response()->json(['message' => $e->getMessage()], 500);
         }
         return response()->json(['message' => 'Welcome Mail Has Been Sent '], 200);
     }

     public function resetMail($id)
     {
         try{
             $this->userService->resetMail($id);
         }catch(\Exception $e){
             Log::error($e);
             return response()->json(['message' => $e->getMessage()], 500);
         }
         return response()->json(['message' => 'Password Reset Mail Has Been Sent '], 200);
     }
}
