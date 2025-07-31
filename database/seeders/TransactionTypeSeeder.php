<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class TransactionTypeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $transactionType = config('constant.default_transaction_type');
        foreach ($transactionType as $type) {
            $type = explode('|', head($type));
            $data[] = array(
                'organization_id'   => 1,
                'type'              => $type[0],
                'description'       => $type[1],
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('transaction_types')->insert($data);
    }
}
