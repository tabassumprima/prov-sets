<?php

namespace Database\Seeders;

use App\Models\AccountingYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class AccountingYearSeeder extends Seeder
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
        $path = public_path('/seeders/accounting_years.csv');

        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'year' => $line['accounting_year'],
                'status' => $line['accountingyr_status'],
                'start' => $line['accountingyr_start'],
                'end' => $line['accountingyr_end'],
                'organization_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        });

        AccountingYear::insert($this->data);
    }
}
