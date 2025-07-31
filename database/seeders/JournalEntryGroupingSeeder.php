<?php

namespace Database\Seeders;

use App\Models\DocumentPortfolio;
use App\Models\JournalEntry;
use App\Models\JournalEntryGroup;
use App\Models\JournalEntryGrouping;
use App\Models\JournalEntryPortfolio;
use App\Services\JournalEntryService;
use App\Services\PortfolioService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\FastExcel;

class JournalEntryGroupingSeeder extends Seeder
{

    protected $service, $journal_entry;

    protected $data;

    public function __construct()
    {
        $this->service = new PortfolioService();
        $this->entry = new JournalEntryService();
        $this->data = array();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/journal-entry-grouping-seeder.xlsx');
        // JournalEntryGrouping::truncate();
        (new FastExcel)->import($path, function ($line) {
            $journal_entry_ids = Cache::remember($line['document_reference'], 60, function () use ($line) {
                return $this->entry->getId($line['document_reference']);
            });
            if($journal_entry_ids){

            $this->data[] = array(
                'document_reference_id' => $journal_entry_ids,
                'group_code' => $line['group_code'],
                'portfolio_id' => Cache::remember($line['ins_portfolio'], 60, function () use ($line) {
                    return $this->service->getId($line['ins_portfolio']);
                }),
                'rei_portfolio'=> $line['rei_portfolio']

            );
        }
        });
        foreach (array_chunk($this->data,5000) as $t)
        {
            DocumentPortfolio::insert($t);
        }

    }
}
