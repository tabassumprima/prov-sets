<?php

namespace Tests\Feature;

use App\Helpers\CustomHelper;
use App\Http\Requests\ClaimPattern\Request;
use App\Models\AccountingYear;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OrganizationTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseTransactions;
    protected bool $seed = true;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_new_organization()
    {
        $image = new UploadedFile(
            public_path('app-assets/images/logo/logo.png'),
            'logo.png',
            'logo/png',
            null,
            true
        );
    
        $explode = explode(".", $image->getClientOriginalName());
        $data = file_get_contents($image);

        $base64 = 'data:image/'.last($explode) . ';base64,' . base64_encode($data);
        $user  = User::where('email', 'admin@admin.com')->first();
       
        $organiation_data = [
            'name'=>'test2',
            'type'=>"Life",
            'sales_tax_number'=>56487589,
            'ntn_number'=>87456333,
            'country_id'=>2,
            'currency_id'=>2,
            'subscription_plan' =>1,
            'shortcode'=>'Org24',
            'logo'=>$image,
            'isBoarding'=>false,
            'address'=>'Karachi',
            'database_config_id'=>1,
            'dateformat'=>null,
            'financial_year'=>'january-December',
            'agent_config'=>null,
        ];
        $this->actingAs($user);
        $this->post(route('organizations.store'), $organiation_data);
        $this->assertDatabaseHas('organizations', ['logo' => $base64]);
    }

    public function testColumnIsNullForSpecificRow()
    {
        Organization::where(
            'logo',null
        )->first();

        $record = Organization::first();
        $this->assertNull($record->logo);

    }
}

