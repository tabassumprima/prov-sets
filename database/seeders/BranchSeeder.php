<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Services\BusinessTypeService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Rap2hpoutre\FastExcel\FastExcel;

class BranchSeeder extends Seeder
{

    protected $service;

    protected $data;

    public function __construct()
    {
        $this->service = new BusinessTypeService();
        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/branches.csv');
        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'description'       => $line['branch_description'],
                'number'            => $line['branch_number'],
                'organization_id'   => 1,
                'branch_code'       => $line['branch_code'],
                'branch_abb'        => $line['branch_abb'],
                'business_type_id'  => Cache::remember($line['business_type'], 60, function () use ($line) {
                    return $this->service->getId($line['business_type']);
                }),
                'level_1'           => $line['level_1'],
                'level1_desc'       => $line['level1_desc'],
                'level_2'           => $line['level_2'],
                'level2_desc'       => $line['level2_desc'],
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            );
        });
        Branch::insert($this->data);
    }
}
