<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class SystemDepartmentSeeder extends Seeder
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
        $path = public_path('/seeders/system_departments.csv');
        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id' => 1,
                'code' => $line['system_department'],
                'description' => $line['department_desc'],
                'description_abb' => $line['department_abb'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        });

        DB::table('system_departments')->insert($this->data);
    }
}
