<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data   = array();
        $data[] = array(
            'name'              => 'Super Admin',
            'email'             => config('constant.email'),
            'organization_id'   => null,
            'password'          => Hash::make(config('constant.password')),
            'email_verified_at' => Carbon::now()->toDateTimeString(),
            'created_at'        => Carbon::now()->toDateTimeString(),
            'updated_at'        => Carbon::now()->toDateTimeString(),
            'is_active'         => true,
            'verification_type' => '2fa',
            'google2fa_secret'  => config('constant.google_2fa')
        );
        DB::table('users')->insert($data);
    }
}
