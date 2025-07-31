<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\Role;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class UserTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_failed_to_enter_in_user_section_without_selecting_organization(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('users.index'));
        $value = session('org');
        $this->assertEmpty($value);
        $this->assertEquals('Please select organization first!',session('error'));
    }

    public function test_to_show_all_users_information(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();
        $org_id = CustomHelper::encode($organization->id);
        $response = $this->get(route('dashboard.index',['org'=>$org_id]));
        $response->assertSee($organization->name);
        $value = session('org');
        $this->assertEquals($org_id,$value);
    }
    public function test_if_user_is_created_successfully(){ 
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);
        $organization = Organization::factory()->create();


        $subscription = Plan::where('name','Basic')->first();
        $data = [
            "subscription_plan" => $subscription->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($organization->id)]));
        $response = $this->get(route('users.index'));
        $response->assertSee('Add New User');

        $role = Role::factory()->create([
            'organization_id'=>$organization->id
        ]);

        $data=[
            "name" => "Sumbul Yasmeen",
            "phone" => "03132964508",
            "email" => "sumbul@gmail.com",
            "user_role" => $role->id
        ];

        $response = $this->post(route('users.store'),$data);
        $this->assertDatabaseHas('users',[
            "name" => "Sumbul Yasmeen",
            "phone" => "03132964508",
            "email" => "sumbul@gmail.com",
        ]);

    }
    
    public function test_if_user_is_editted_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();
        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($organization->id)]));
        $response = $this->get(route('users.index'));
        $response->assertSee('Add New User');

        $role = Role::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $data = [
            "name" => "Areeb",
            "email" => "kingmax@user.com",
            "phone" => "542365246",
            "is_active" => "1",
            "user_role" => $role->id,
            "password" => "Sumbul24",
            "password_confirmation" => "Sumbul24",

        ];
        $response = $this->put(route('users.update', [CustomHelper::encode($user->id)]),$data);
        $value = Hash::make('Sumbul24');
        $data2 = [
            "name" => "Areeb",
            "email" => "kingmax@user.com",
            "phone" => "542365246",
            "is_active" => true,
            "organization_id"=> $organization->id,
        ];

        $this->assertDatabaseHas('users',$data2);
    }

    public function test_delete_user(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);
        
        $organization = Organization::factory()->create();
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));
        $response = $this->get(route('users.index'));

        $users = User::factory()->create();
        $reponse = $this->delete(route('users.destroy',[CustomHelper::encode($users->id)]));
        $this->assertDatabaseMissing('users',['name'=>$users->id]);
    }

    public function test_User_is_login_and_redirect_to_dashboard(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);
        
        $manager = app('impersonate');
        $manager->findUserById($user->id);

        $organization = Organization::factory()->create();
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));
        $response = $this->get(route('users.index'));

        $user1 = User::factory()->create([
            'organization_id'=>$organization->id,
        ]);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user1->id)]));
        $manager->isImpersonating();
    
        $this->assertEquals(true, $manager->isImpersonating() );

    }

    public function test_grant_all_permissions_to_user(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $organization = Organization::factory()->create();
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            "subscription_plan" => $subscription_plan->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));
        $response->assertSee($organization->name);

        $role = Role::factory()->create([
            'organization_id'=> $organization->id,
        ]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $this->assertDatabaseHas('roles',[
            'name'=> $role->name,
            'organization_id' => $organization->id
        ]);

        $user = User::factory()->create([
            'organization_id' => $organization->id,
        ]);

        $user->assignRole($role->id);
        $response = $this->get(route('users.index'));
        $response->assertSee('User Information');
        
        $manager = app('impersonate');
        $manager->take($admin, $user);
        $manager->isImpersonating();
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        $response = $this->get(route('user.dashboard'));
        $this->assertEquals('true',$manager->isImpersonating());
        $response->assertSee('Portfolios','Portfolio Criteria','Groups');  //writing all sections name to check all permissions allowed or not.
        }
}