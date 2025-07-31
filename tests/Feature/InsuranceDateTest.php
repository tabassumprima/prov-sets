<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Criteria;
use App\Models\Organization;
use App\Models\Permission;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Services\PermissionService;
use App\Services\SubscriptionService;
use Database\Seeders\StatusSeeder;
use Database\Seeders\UserSeeder;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class InsuranceDateTest extends TestCase
{
    use  RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_allow_admin_to_create_criteria_on_previous_date_with_onbording()
    {

        $admin_user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($admin_user);

        $organization = Organization::factory()->create([
            'isBoarding' => true
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan' => $subscription_plan->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);


        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin_user , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        //date
        $date = '1997-08-11';

        $faker = Factory::create();
        $name = $faker->unique()->text(10);

        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => 'insurance',
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('criterias', $data);
    }

    public function test_dont_allow_user_to_create_criteria_on_previous_date_with_onboarding()
    {

        $faker = Factory::create();
        $admin = app('impersonate');

        $organization = Organization::factory()->create([
            'isBoarding' => true
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $organization_user = User::factory()->for($organization)->create();
        $admin_user = User::where('email', 'admin@admin.com')->first();

        //date
        $date = '1997-08-11';

        $name = $faker->unique()->text(10);
        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => $faker->randomElement(['insurance', 're-insurance']),
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post('/criterias', $data);

        $this->assertDatabaseMissing('criterias', ["name" => $name]);
    }

    //Don't allow user to create criteria on previous date
    public function test_dont_allow_user_to_create_criteria_on_previous_date()
    {
        // $this->seed();
        $faker = Factory::create();
        $admin = app('impersonate');

        //Create Organization
        $organization = Organization::factory()->create([
            'isBoarding' => false
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        //Create a user and assign to organization
        $organization_user = User::factory()->for($organization)->create();

        $this->actingAs($organization_user);

        //date
        $date = '1997-08-11';

        $name = $faker->unique()->text(10);
        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => $faker->randomElement(['insurance', 're-insurance']),
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post('/criterias', $data);

        $this->assertDatabaseMissing('criterias', ["name" => $name]);
    }

    public function test_dont_allow_admin_to_create_criteria_on_previous_date_without_onboarding()
    {
        // $this->seed();
        $faker = Factory::create();
        $admin = app('impersonate');

        //Create Organization
        $organization = Organization::factory()->create([
            'isBoarding' => false
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        //Create a user and assign to organization
        $organization_user = User::factory()->for($organization)->create();

         //Admin Instance
         $admin_user = User::where('email', 'admin@admin.com')->first();

        $admin->take($admin_user, $organization_user);

        //date
        $date = '1997-08-11';

        $name = $faker->unique()->text(10);
        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => $faker->randomElement(['insurance', 're-insurance']),
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post('/criterias', $data);

        $this->assertDatabaseMissing('criterias', ["name" => $name]);
    }


    public function test_allow_admin_to_create_criteria_on_next_date()
    {
        $admin_user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($admin_user);

        //Create Organization
        $organization = Organization::factory()->create([
            'isBoarding' => false
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan' => $subscription_plan->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin_user , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        //date
        $date = Carbon::tomorrow();

        $faker = Factory::create();
        $name = $faker->unique()->text(10);

        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => 'insurance',
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseMissing('criterias', $data);
    }

    public function test_ending_date_null_on_new_criteria()
    {
        $admin_user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($admin_user);

        $organization = Organization::factory()->create([
            'isBoarding' => true
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan' => $subscription_plan->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin_user , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        //date
        $date = '1997-08-11';

        $faker = Factory::create();
        $name = $faker->unique()->text(10);

        $data =[
            "name" => $name ,
            "description" => "test",
            "applicable_to" => 'insurance',
            "start_date" => $date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'), $data);
        $response->assertRedirect();
        $this->assertDatabaseHas('criterias', $data);
        $criteria = Criteria::where('name', $name)->first();
        $criteria_ending_date = $criteria->end_date;
        $this->assertEquals(null, $criteria_ending_date);
    }

    public function test_ending_date_one_day_minus_on_previous_criteria()
    {
        $admin_user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($admin_user);

        //Create Organization
        $organization = Organization::factory()->create([
            'isBoarding' => true
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan' => $subscription_plan->id,
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index',['org'=>CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin_user , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));

        //date
        $first_criteria_start = Carbon::tomorrow();
        $faker = Factory::create();
        $first_criteria_name = $faker->unique()->text(10);
        $type = $faker->randomElement(['insurance', 're-insurance']);
        $data =[
            "name" => $first_criteria_name ,
            "description" => "test",
            "applicable_to" => $type,
            "start_date" => $first_criteria_start,
            "status_id" => 21,
        ];
        $response = $this->post('/criterias', $data);

        $second_criteria_start = Carbon::tomorrow()->addMonth();
        $second_criteria_name = $faker->unique()->text(10);
        $data =[
            "name" => $second_criteria_name ,
            "description" => "test",
            "applicable_to" => $type,
            "start_date" => $second_criteria_start,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'), $data);

        $criteria = Criteria::where('name', $first_criteria_name)->first();
        $criteria_ending_date = $criteria->end_date;

        $this->assertEquals(Carbon::parse($second_criteria_start)->subDay(), $criteria_ending_date);

    }

    public function test_allow_admin_to_create_criteria_on_previous_date_with_onBoarding_but_dont_allow_second_critera_previous_than_first_date_without_onBoarding(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $organization = Organization::factory()->create([
            'isBoarding' => true,
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = ['subscription_plan' => $subscription_plan->id ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));

        $first_criteria_start_date = '2010-03-01';
        $faker = Factory::create();
        $first_criteria_name = $faker->unique()->text(10);
        $type = $faker->randomElement(['insurance', 're-insurance']);
        $data =[
            "name" => $first_criteria_name ,
            "description" => "the combined value of the projects in the portfolio",
            "applicable_to" => $type,
            "start_date" => $first_criteria_start_date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'),$data);
        $this->assertDatabaseHas('criterias',$data);

        $organization->isBoarding = 'false';
        $organization->save();

        $second_criteria_start_date = '2009-03-01';
        $second_criteria_name = $faker->unique()->text(10);
        $type1 = $faker->randomElement(['insurance', 're-insurance']);
        $data1 =[
            "name" => $second_criteria_name ,
            "description" => "the combined value of the projects in the portfolio",
            "applicable_to" => $type,
            "start_date" => $second_criteria_start_date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'),$data1);
        $this->assertDatabaseMissing('criterias',$data1);
    }

    public function test_allow_admin_to_create_criteria_on_previous_date_with_onBoarding_but_dont_allow_second_critera_after_date_of_the_first_date_without_onBoarding(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $organization = Organization::factory()->create([
            'isBoarding' => true,
        ]);
        $permission_service = new PermissionService();
        $permission_service->createNewOrganizationPermissions($organization);
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = ['subscription_plan' => $subscription_plan->id ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($organization->id, $data);
        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($organization->id)]));

        $role = Role::factory()->create(['organization_id'=>$organization->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);

        $user = User::factory()->create(['organization_id'=>$organization->id]);
        $user->assignRole($role->id);
        $manager = app('impersonate');
        $manager->take($admin , $user);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));

        $first_criteria_start_date = '2010-03-01';
        $faker = Factory::create();
        $first_criteria_name = $faker->unique()->text(10);
        $type = $faker->randomElement(['insurance', 're-insurance']);
        $data =[
            "name" => $first_criteria_name ,
            "description" => "the combined value of the projects in the portfolio",
            "applicable_to" => $type,
            "start_date" => $first_criteria_start_date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'),$data);
        $this->assertDatabaseHas('criterias',$data);

        $organization->isBoarding = 'false';
        $organization->save();
        $second_criteria_start_date = '2011-03-01';
        $second_criteria_name = $faker->unique()->text(10);
        $type1 = $faker->randomElement(['insurance', 're-insurance']);
        $data1 =[
            "name" => $second_criteria_name ,
            "description" => "the combined value of the projects in the portfolio",
            "applicable_to" => $type1,
            "start_date" => $second_criteria_start_date,
            "status_id" => 21,
        ];
        $response = $this->post(route('criterias.store'),$data1);
        $this->assertDatabaseMissing('criterias',$data1);
    }
}

