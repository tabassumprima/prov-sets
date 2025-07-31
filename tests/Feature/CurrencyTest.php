<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Models\Currency;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_displays_total_rows_in_view(){
        $currencies = 'currencies';
        $user = User::where('email', 'admin@admin.com')->first();
        $this->actingAs($user);
        $response = $this->get(route('currencies.index'));
        $totalRows = DB::table($currencies)->get();
        foreach ($totalRows as $row) 
        {
            $response->assertSee($row->name);
        }
        
    }

    public function test_successfully_added_new_currency_in_the_table(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $data = [
            'name' => 'Test' ,
            'symbol' => 'Tss'
        ];
        
        $response = $this->post(route('currencies.store'),$data);
        $this->assertDatabaseHas('currencies',$data);
    }

    public function test_edit_currency()
    {
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);
        $currency = Currency::factory()->create();
        
        $response = $this->put(route('currencies.update', [CustomHelper::encode($currency->id)]),[
            'name'   => "areeb Dollar",
            'symbol' => "$$",
        ]);
        $this->assertDatabaseHas('currencies',[
            'id'    => $currency->id,
            'name'  => 'areeb Dollar',
            'symbol'=> '$$',
        ]);
        
    }

    public function test_delete_an_existing_currency(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $currency = Currency::factory()->create();

        $response = $this->delete(route('currencies.destroy', [CustomHelper::encode($currency->id)]));

        $this->assertDatabaseMissing('currencies',['id'=>$currency->id]);
    }

    public function test_do_not_delete_a_currency_which_is_assigned_to_an_organization(){
        $user = User::where('email','admin@admin.com')->first();
        $this->actingAs($user);

        $currency = Currency::factory()->create();

        $organization = Organization::factory()->create([
            'currency_id' => $currency->id
        ]);

        $response = $this->delete(route('currencies.destroy', [CustomHelper::encode($currency->id)]));
        $this->assertDatabaseHas('currencies',['id'=>$currency->id]);


    }

}
