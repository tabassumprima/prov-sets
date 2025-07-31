<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Stmt\Foreach_;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_role_is_added_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();

        $permissionService = new PermissionService();
        $permissionService->createNewOrganizationPermissions($organization);
        
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));

        $data= [
            'name'=>'manager',
            'permissions'=>['approve-provision', 'view-insurance-portfolio'],
        ];

        $response = $this->post(route('roles.store'),$data);
        $this->assertDatabaseHas('roles',[
            "name" => 'manager',
            'organization_id' => $organization->id
        ]);

        $arr = $data['permissions'];

        foreach($arr as $i){
            $this->assertDatabaseHas('permissions',[
                "name" => $i,
                'organization_id' => $organization->id
            ]);
        }

        $role = Role::where(['name' =>'manager', 'organization_id' => $organization->id])->first();
        $permissions = Permission::whereIn('name',['approve-provision','view-insurance-portfolio'])->get();

        foreach($permissions as $permission){
            $this->assertDatabaseHas('role_has_permissions',[
                'permission_id'=>$permission->id,
                'role_id'=>$role->id
            ]);
        }

    }

    public function test_if_role_is_editted_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();

        $permissionService = new PermissionService();
        $permissionService->createNewOrganizationPermissions($organization);

        $response = $this->get(route('dashboard.index',['org'=> CustomHelper::encode($organization->id)]));
        $response->assertSee($organization->name);

        $response = $this->get(route('roles.index'));
        $response->assertSee('Roles');

        $role = Role::factory()->create([
            'organization_id' => $organization->id
        ]);
        $response = $this->put(route('roles.update', [CustomHelper::encode($role->id)]),[
            'name'=>'Chairmans',
            'permissions'=>['create-discount-rate','create-claim-pattern']
        ]);
        $this->assertDatabaseHas('roles',['name'=>'Chairmans','organization_id'=>$organization->id]);

        $permsn = ['create-discount-rate','create-claim-pattern'];

        foreach ($permsn as $permission) {
            $this->assertDatabaseHas('permissions',[
                "name" => $permission,
            'organization_id' => $organization->id]);
        }

        $permissions = Permission::whereIn('name',['create-discount-rate','create-claim-pattern'])->get();

        foreach ($permissions as $permission) {
            $this->assertDatabaseHas('role_has_permissions',[
                'role_id'=>$role->id,
                'permission_id'=>$permission->id,
            ]);
        }

     }
}
