<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProfitCenter;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class ProfitCenterSeeder extends Seeder
{

    protected $data;

    public function __construct()
    {
        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/profit_centers.csv');

        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'code' => $line['profit_center_code'],
                'organization_id' => 1,
                'description' => $line['profit_center_desc'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        });
        ProfitCenter::insert($this->data);
    }
}
