<?php

namespace Database\Seeders;

use App\Models\ProductCode;
use App\Services\BusinessTypeService;
use App\Services\SystemDepartmentService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductCodeSeeder extends Seeder
{
    protected  $systemDepartmentService, $businessTypeService;
    protected $data;

    public function __construct()
    {
        $this->systemDepartmentService = new SystemDepartmentService();
        $this->businessTypeService = new BusinessTypeService();
        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/product_codes.csv');

        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id'       => 1,
                'code'                  => $line['product_code'],
                'system_department_id'  => Cache::remember($line['system_department_id'],60,function() use ($line){
                    return $this->systemDepartmentService->getId($line['system_department_id']);
                }),
                'description'           => $line['product_desc'],
                'short_code'            => $line['product_short_code'],
                'business_type_id'      => Cache::remember($line['business_type_id'],60,function() use ($line){
                    return $this->businessTypeService->getId($line['business_type_id']);
                }),
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            );
        });
        ProductCode::insert($this->data);
    }
}
