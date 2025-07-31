<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $countries = config('constant.default_countries');
        foreach ($countries as $country) {
            $country = explode('|', head($country));
            $data[] = array(
                'name' => $country[0],
                'code' => $country[1],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('countries')->insert($data);
    }
}
