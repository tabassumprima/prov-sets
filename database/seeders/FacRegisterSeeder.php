<?php

namespace Database\Seeders;

use App\Models\FacRegister;
use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;

class FacRegisterSeeder extends Seeder
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
            return FacRegister::create([
                'id' => $line['branch'],
                'organization_code' => $line['branch_description'],
                'branch' => $line['organization_code'],
                'system_department' => $line['branch_code'],
                'insurance_type' => $line['branch_abb'],
                'document_type' => $line['business_type'],
                'document_serial' => $line['level_1'],
                'fac_year' => $line['level1_desc'],
                'doc_subnumber' => $line['level_2'],
                'policy_document' => $line['level2_desc'],
                'fac_premium' => $line['level2_desc'],
                'fac_commission' => $line['level2_desc'],
                'localforeign_tag' => $line['level2_desc'],
                'issue_date' => $line['level2_desc'],
                'reinsurer' => $line['level2_desc'],
                'comm_date' => $line['level2_desc'],
                'expiry_date' => $line['level2_desc'],
                'ri_document_number' => $line['level2_desc'],
                'system_posting_date' => $line['level2_desc'],
            ]);
        });
    }
}
