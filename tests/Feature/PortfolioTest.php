<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Organization;
use App\Models\Plan;
use App\Models\Portfolio;
use App\Models\Role;
use App\Models\User;
use App\Services\SubscriptionService;
use Database\Factories\PortfolioFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lab404\Impersonate\Impersonate;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    // use RefreshDatabase;
    // protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_add_new_portfolio(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $org = Organization::factory()->create();
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan'=> $subscription_plan->id
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($org->id, $data);

        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($org->id)]));
        $response->assertSee($org->name);

        $role = Role::factory()->create(['organization_id' => $org->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);
        $this->assertDatabaseHas('roles',[
            'name' => $role->name,
            'organization_id' => $org->id,
        ]);

        $response = $this->get(route('users.index'));
        $response->assertSee('User Information');

        $user = User::factory()->create([
            'organization_id'=>$org->id,
        ]);
        $user->assignRole($role->id);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        $manager = app('impersonate');
        $manager->take($admin,$user);
        $response = $this->get(route('user.dashboard'));
        $response->assertSee('Portfolio Criteria');

        $data = [
            "name" => "Compliance",
            "shortcode" => "Com",
            "type" => "insurance"
        ];
        $response = $this->post(route('portfolios.store'),$data);
        $this->assertDatabaseHas('portfolios',[
            "name" => "Compliance",
            "shortcode" => "Com",
            "organization_id" => $org->id,
            "type" => "insurance"
        ]);
    }

    public function test_delete_portfolio(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $org = Organization::factory()->create();
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan'=> $subscription_plan->id
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($org->id, $data);

        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($org->id)]));
        $response->assertSee($org->name);

        $role = Role::factory()->create(['organization_id' => $org->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);
        $this->assertDatabaseHas('roles',[
            'name' => $role->name,
            'organization_id' => $org->id,
        ]);

        $response = $this->get(route('users.index'));
        $response->assertSee('User Information');

        $user = User::factory()->create([
            'organization_id'=>$org->id,
        ]);
        $user->assignRole($role->id);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        $manager = app('impersonate');
        $manager->take($admin,$user);
        $response = $this->get(route('user.dashboard'));
        $response->assertSee('Portfolio Criteria');

        $portfolio = Portfolio::factory()->create(['organization_id'=>$org->id]);
        $response = $this->delete(route('portfolios.destroy', [CustomHelper::encode($portfolio->id)]));
        $this->assertDatabaseMissing('portfolios',['id'=>$portfolio->id]);
    }

    public function test_edit_portfolio(){
        $admin = User::where('email','admin@admin.com')->first();
        $this->actingAs($admin);

        $org = Organization::factory()->create();
        $subscription_plan = Plan::where('name','Basic')->first();
        $data = [
            'subscription_plan'=> $subscription_plan->id
        ];
        $subscriptionService = new SubscriptionService();
        $subscriptionService->addSubscription($org->id, $data);

        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($org->id)]));
        $response->assertSee($org->name);

        $role = Role::factory()->create(['organization_id' => $org->id]);
        $permissions = config('permission.permissions');
        $role->syncPermissions($permissions);
        $this->assertDatabaseHas('roles',[
            'name' => $role->name,
            'organization_id' => $org->id,
        ]);

        $response = $this->get(route('users.index'));
        $response->assertSee('User Information');

        $user = User::factory()->create([
            'organization_id'=>$org->id,
        ]);
        $user->assignRole($role->id);
        $response = $this->get(route('users.impersonate', ['user' => CustomHelper::encode($user->id)]));
        $manager = app('impersonate');
        $manager->take($admin,$user);
        $response = $this->get(route('user.dashboard'));
        $response->assertSee('Portfolio Criteria');

        $portfolio = Portfolio::factory()->create(['organization_id'=>$org->id]);
        $response = $this->put(route('portfolios.update', [CustomHelper::encode($portfolio->id)]),[
            "name" => "Operational",
            "shortcode" => "Com",
            "type" => "insurance",
        ]);
        $this->assertDatabaseHas('portfolios',[
            "name" => "Operational",
            "shortcode" => "Com",
            "type" => "insurance",
        ]);
    }
}
