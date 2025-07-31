<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array();
        $statuses = config('constant.default_statuses');
        foreach ($statuses as $status) {
            $status = explode('|', head($status));
            $data[] = array(
                'model' => $status[0],
                'title' => $status[1],
                'slug' => $status[2],
                'color' => $status[3],
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('statuses')->insert($data);
    }
}
