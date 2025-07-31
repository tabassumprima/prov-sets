<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusinessTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $businessType = config('constant.default_branch_type');
        foreach ($businessType as $type) {
            $type = explode('|', head($type));
            $data[] = array(
                'type' => $type[0],
                'description' => $type[1],
                'organization_id'  => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('business_types')->insert($data);
    }
}
