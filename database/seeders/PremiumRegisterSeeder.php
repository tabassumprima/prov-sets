<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PremiumRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/branch-info.csv');

        (new FastExcel)->configureCsv(',')->import($path,function($line){
            return BranchInfo::create([
                'id' => $line['branch'],
                'description' => $line['branch_description'],
                'organization_id' => 1,
                'branch_code' => $line['branch_code'],
                'branch_abb' => $line['branch_abb'],
                'business_type' => $line['business_type'],
                'level_1' => $line['level_1'],
                'level1_desc' => $line['level1_desc'],
                'level_2' => $line['level_2'],
                'level2_desc' => $line['level2_desc'],
            ]);
        });
    }
}
