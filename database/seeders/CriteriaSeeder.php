<?php

namespace Database\Seeders;

use App\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $criteria = [
            [
                'name'          => 'Marvin Thornton',
                'description'   => 'Dummy description',
                'applicable_to' => 're-insurance',
                'organization_id'    => 1,
                'start_date'    => '2022-09-20',
                'status_id'        => 2
            ]
        ];
        Criteria::insert($criteria);


    }
}
