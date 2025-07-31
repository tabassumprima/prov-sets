<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Country;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CountryTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_check_if_new_country_is_added_successfully(){
 
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $data = [
            'name'=>'South Korea',
            'code'=>'KR',
            'timeZone'=>'areeb|suumbul'
        ];

        $reponse = $this->post(route('countries.store'),$data);
        $this->assertDatabaseHas('countries',[
            'name'=>'South Korea',
            'code'=>'KR',
            'zone'=> 'areeb',
            'offset' => 'suumbul'
        ]);
    }

    public function test_if_a_country_is_deleted_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $country  = Country::factory()->create();
        $response = $this->delete(route('countries.destroy',[CustomHelper::encode($country->id)]));
        $this->assertDatabaseMissing('countries',['id'=>$country->id]);
    }

    public function test_edit_a_country(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $country = Country::factory()->create();
        $response = $this->put(route('countries.update',[CustomHelper::encode($country->id)]),[
            'name'    => 'North Korea',
            'code'    => 'KN',
            'timeZone'=> 'Pacific/Wallis|+12:00', 
        ]);
        $this->assertDatabaseHas('countries',[
            'id'    => $country->id,
            'name'  => 'North Korea',
            'code'  => 'KN',
            'zone'  => 'Pacific/Wallis',
            'offset'=> '+12:00',
        ]);

    }

    public function test_do_not_delete_a_country_which_is_assigned_to_an_organization(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $country = Country::factory()->create();
        $organization = Organization::factory()->create([
            'country_id'=>$country->id
        ]);
        $response = $this->delete(route('countries.destroy',[CustomHelper::encode($country->id)]));
        $this->assertDatabaseHas('countries',['id'=>$country->id]); 
    }
}
