<?php

namespace Database\Seeders;

use App\Models\GlCode;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class GlCodeSeeder extends Seeder
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
        $path = public_path('/seeders/data-version2/GLCodes.csv');

        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id' => 1,
                'code' => $line['PCA_GLACCODE'],
                'description' => $line['PCA_GLACDESC'],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        });
        GlCode::insert($this->data);
    }
}
