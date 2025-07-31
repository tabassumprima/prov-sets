<?php

namespace App\Services;

use App\Models\User;
use App\Helpers\{CustomHelper};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Password, Auth, Hash};
use App\Notifications\Admin\WelcomeUser;
use Exception;

class UserService {

    public function create($request)
    {
        $roleService = new RoleService();
        $request->mergeIfMissing(['password' => $this->autoGeneratePassword(),
         'user_role' => $roleService->getAdminRoleName()]);

        $user = User::create($request->all());
        $user->assignRole($request->user_role);
        $user->generate2faSecret();
        return $user;
    }

    public function update($request, $id)
    {
        $user = $this->fetch($id);

        if ($request->has('password') && !empty($request->password)) {
            $user->update($request->all());
        } else {
            $user->update($request->except('password'));
        }

        if (!$user->hasRole('admin')) {
            $user->syncRoles($request->user_role);
        }

        return $user;
    }

    public function delete($id)
    {
        $user = $this->fetch($id);
        $user->delete();
        return $user;
    }

    public function updateStatus($request, $id)
    {
        $user = $this->fetch($id);
        $user->is_active = $request->get("value");
        $user->save();
        return $user;
    }

    /**
     *
     * returns all the users except the logged-in admin himself
     * **/
    function getAllUsersWithRoleAdmin()
    {
        return User::whereHas('roles', function($query){
            $query->where('name', 'admin');
        })->orderBy('updated_at', 'desc')->get();
    }
    /**
     *
     * returns all the users except the logged-in admin himself
     * **/

    function getAllUsersExceptAdmin()
    {
        return User::where('id', '!=', Auth::id())->orderBy('created_at', 'desc')->get();
    }

    //fetch unassigned user excepet users with role admin
    function getAllUnassignedUserExceptRoleAdmin()
    {

        return User::doesntHave('organization')->whereHas('roles', function($query){
            $query->where('name', '!=', 'admin');
        })->get();

    }

    //fetch unassigned and organization user except users with role admin
    function getUnassignedAndOrganizationUserExceptRoleAdmin($organization_id)
    {
        return User::whereHas('organization', function($query) use ($organization_id) {
            $query->where('organizations.id', CustomHelper::decode($organization_id));
        })->orDoesntHave('organization')->whereHas('roles', function($query){
            $query->where('name', '!=', 'admin');
        })->get();
    }

    /**
     *
     * returns user by id
     * **/
    function fetch($id)
    {
        return User::findOrFail(CustomHelper::decode($id));
    }

    public function fetchWithRelations($id,$relations = array())
    {
        return User::with($relations)->findOrFail(CustomHelper::decode($id));
    }

    public function updatePassword($request, $id)
    {
        $user = $this->fetch($id);
        if(!Hash::check($request->current_password, $user->password))
            throw new Exception('Old password is incorrect');
        $user->fill($request->all())->save();
        return $user;
    }

    public function generate2fa($id)
    {
        $user = $this->fetch($id);
        $user->generate2faSecret();
        return $user;
    }

    public function impersonate($user)
    {
        $user = $this->fetch($user);
        auth()->user()->impersonate($user);
    }

    public function sendWelcomeMail($id)
    {
        $user = User::find($id);
        $user->notify(new WelcomeUser());
        return $user;
    }

    public function resetMail($id)
    {
        $user = User::find($id);
        Password::sendResetLink(['email' => $user->email]);
        return $user;
    }

    public function autoGeneratePassword($length = 10) : string
    {
        $password = Str::random($length);
        return $password;
    }

    public function fetchAuthOrganizationId()
    {
        return Auth()->user()->organization_id;
    }

    public function isActive($email)
    {
        $user = User::where('email', $email)->first();
        return $user ? $user->is_active : false;
    }
    public function emailExists($email)
    {
        return User::where('email', $email)->exists();
    }

}
