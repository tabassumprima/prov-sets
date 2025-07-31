<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $configs = [[
            'name' => 'default',
            'data' => json_encode([
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'ias19',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ]),
            'created_at' => Carbon::now()->toDateTimeString(),
    		'updated_at' => Carbon::now()->toDateTimeString(),
        ],
        [
            'name' => 'test',
            'data' => json_encode([
                'host' => 'localhost',
                'port' => '3306',
                'database' => 'ias19_test',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix' => '',
                'strict' => false,
                'engine' => null,
            ]),
            'created_at' => Carbon::now()->toDateTimeString(),
    		'updated_at' => Carbon::now()->toDateTimeString(),
        ]];
        DB::table('database_configs')->insert($configs);
    }
}
