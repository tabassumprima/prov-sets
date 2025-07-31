<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CohortSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $cohorts = config('constant.default_cohorts');
        foreach ($cohorts as $cohort) {
            $cohort = explode('|', head($cohort));
            $data[] = array(
                'shortcode' => $cohort[0],
                'name' => $cohort[1],
                'months' => $cohort[2],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('cohorts')->insert($data);
    }
}
