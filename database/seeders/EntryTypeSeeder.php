<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EntryTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entryType = config('constant.default_entry_type');
        foreach ($entryType as $type) {
            $type = explode('|', head($type));
            $data[] = array(
                'type'              => $type[0],
                'description'       => $type[1],
                'organization_id'   => 1,
                'created_at'        => Carbon::now()->toDateTimeString(),
                'updated_at'        => Carbon::now()->toDateTimeString(),
            );
        }
        DB::table('entry_types')->insert($data);
    }
}
