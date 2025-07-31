<?php

namespace Database\Seeders;

use App\Models\TransactionType;
use App\Models\VoucherType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class VoucherTypeSeeder extends Seeder
{

    protected $data;


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/voucher_types.csv');

        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id'   => 1,
                'type'              => $line['voucher_type'],
                'description'       => $line['voucher_desc'],
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            );
        });

        VoucherType::insert($this->data);

    }
}
