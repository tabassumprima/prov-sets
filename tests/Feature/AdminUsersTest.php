<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_if_admin_user_created_successfully()
    {
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $data = [
            'name'=>'Albert Einstein',
            'email'=>'Albert@gmail.com',
            'phone'=>'123344359'
        ];

        $response = $this->post(route('admin-users.store'),$data);
        $this->assertDatabaseHas('users',$data);
    }

    public function test_if_admin_user_is_editted_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $admin_user = User::factory()->create();
        $response = $this->put(route('admin-users.update',[CustomHelper::encode($admin_user->id)]),[
            'name'=>'Albert Einstein',
            'email'=>'Albert@gmail.com',
            'phone'=>'123344359'
        ]);
        $this->assertDatabaseHas('users',[
            'name'=>'Albert Einstein',
            'email'=>'Albert@gmail.com',
            'phone'=>'123344359'
        ]);
    }

    public function test_if_the_user_is_deleted_successfully(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $admin_user = User::factory()->create();
        $response = $this->delete(route('admin-users.destroy',[CustomHelper::encode($admin_user->id)]));
        $this->assertDatabaseMissing('users',['id'=>$admin_user->id]);
    }
}
