<?php

namespace Database\Seeders;

use App\Models\EndorsementType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Rap2hpoutre\FastExcel\FastExcel;

class EndorsementTypeSeeder extends Seeder
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
        $path = public_path('/seeders/endorsement_types.csv');
        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id'       => 1,
                'type'                  => $line['endo_type'],
                'description'           => $line['endo_desc'],
                'description_takaful'   => $line['endo_desc_takaful'],
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            );
        });
        EndorsementType::insert($this->data);
    }
}
