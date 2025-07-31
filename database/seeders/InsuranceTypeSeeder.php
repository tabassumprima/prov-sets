<?php

namespace Database\Seeders;

use App\Models\InsuranceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InsuranceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $insuranceType = config('constant.default_insurance_type');
        foreach ($insuranceType as $type) {
            $type = explode('|', head($type));
            $data[] = array(
                'organization_id'       => 1,
                'type'                  => $type[0],
                'description'           => $type[1],
                'description_takaful'   => $type[2],
                'updated_at'            => Carbon::now()->toDateTimeString(),
                'created_at'            => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('insurance_types')->insert($data);
    }
}
