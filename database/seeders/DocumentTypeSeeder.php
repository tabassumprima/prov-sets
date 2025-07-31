<?php

namespace Database\Seeders;

use App\Models\DocumentType;
use App\Services\DocumentTypeService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Rap2hpoutre\FastExcel\FastExcel;

class DocumentTypeSeeder extends Seeder
{
    protected $service;
    protected $data;

    public function __construct()
    {
        $this->service = new DocumentTypeService();
        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/document_types.csv');
        (new FastExcel)->configureCsv(',')->import($path, function ($line) {
            $this->data[] = array(
                'organization_id'       => 1,
                'type'                  => $line['document_type'],
                'description'           => $line['document_desc'],
                'description_takaful'   => $line['document_desc_takaful'],
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            );
        });
        DocumentType::insert($this->data);
    }
}
