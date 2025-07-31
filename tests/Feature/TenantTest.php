<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TenantTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_check_if_all_organizations_is_showing_at_home_page(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();

        $response = $this->get(route('tenant.index'),['name'=>$organization->name]);
        $response->assertSee('organizations',['name'=>$organization->name]);
    }

    public function test_check_if_session_is_on_and_the_expected_id_of_session_is_true_or_not(){
        $user =User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();

        $response = $this->get(route('dashboard.index', ['org' => CustomHelper::encode($organization->id)]));
        $response->assertSee($organization->name);

        $org_id = CustomHelper::encode($organization->id);

        $value = session('org');
        $this->assertEquals($org_id,$value);
    }

    public function test_Users_Section_Block_Without_Organization_Selection(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $response = $this->get(route('users.index'));
        $value = session('org');
        $this->assertEmpty($value);
        $response->assertStatus(302);
        $this->assertEquals('Please select organization first!',session('error'));
    }

    public function test_users_section_open_after_selecting_organization(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $organization = Organization::factory()->create();
        $org_id = CustomHelper::encode($organization->id);
        $response = $this->get(route('dashboard.index',['org'=>$org_id]));
        $response->assertSee($organization->name);

        $response = $this->get(route('users.index'));
        $response->assertSee('User Information');

    }
}
