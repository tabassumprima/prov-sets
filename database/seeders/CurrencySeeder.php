<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $currencies = config('constant.default_currencies');
        foreach ($currencies as $currency) {
            $currency = explode('|', head($currency));
            $data[] = array(
                'name' => $currency[0],
                'symbol' => $currency[1],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('currencies')->insert($data);
    }
}
