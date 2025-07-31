<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MeasurementModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $measurement_models = config('constant.default_measurement_models');
        foreach ($measurement_models as $measurement_model) {
            $measurement_model = explode('|', head($measurement_model));
            $data[] = array(
                'shortcode' => $measurement_model[0],
                'name' => $measurement_model[1],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('measurement_models')->insert($data);
    }
}
