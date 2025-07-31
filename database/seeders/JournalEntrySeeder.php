<?php

namespace Database\Seeders;

use App\Models\{DocumentReference, JournalEntry, Journal};
use App\Services\{AccountingYearService, BranchInfoService, BusinessTypeService, EntryTypeService, GlCodeService, JournalService, ProfitCenterService, TransactionTypeService, VoucherTypeService};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class JournalEntrySeeder extends Seeder
{

    protected $branchInfoService, $businessTypeService, $accountingYearService, $voucherTypeService, $profitCenterService, $transactionTypeService, $entryTypeService, $glCodeService,
    $journalService;
    protected $data, $reference, $journalEntry, $i;

    public function __construct()
    {
        $this->branchInfoService = new BranchInfoService();
        $this->businessTypeService = new BusinessTypeService();
        $this->accountingYearService = new AccountingYearService();
        $this->voucherTypeService = new VoucherTypeService();
        $this->profitCenterService = new ProfitCenterService();
        $this->transactionTypeService = new TransactionTypeService();
        $this->entryTypeService = new EntryTypeService();
        $this->journalService = new JournalService();
        $this->glCodeService = new GlCodeService();

        $this->data = array();
        $this->reference = array();
        $this->journalEntry = array();
        $this->i =  0 ;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = public_path('/seeders/journal-entries-new.csv');
        (new FastExcel)->configureCsv(',')->import($path,function($line) {
            Log::info($this->i);
            $this->i++;
            // $profit_center_id = $this->profitCenterService->getId($line['profit_center_code']);
            $journal = Journal::where('voucher_number', $line['voucher_no'])->first();
            if(!$journal){
                $this->data[] = array(
                    'organization_id'       => 1,

                    'branch_info_id'        => Cache::remember($line['branch'], 60, function () use ($line) {
                                                    return $this->branchInfoService->getId($line['branch']);
                                                }),

                    'voucher_type_id'       => Cache::remember($line['voucher_type'], 60, function () use ($line) {
                                                    return $this->voucherTypeService->getId($line['voucher_type']);
                                                }),

                    'accounting_year_id'    => Cache::remember($line['accounting_year'], 60, function () use ($line) {
                                                    return $this->accountingYearService->getId($line['accounting_year']);
                                                }),

                    'system_department_id'  => $line['system_department'],
                    'transaction_type_id'   => Cache::remember($line['transaction_type'], 60, function () use ($line) {
                                                    return $this->transactionTypeService->getId($line['transaction_type']);
                                                }),


                    'business_type_id'      =>  Cache::remember($line['business_type'], 60, function () use ($line) {
                                                    return $this->businessTypeService->getId($line['business_type']);
                                                }),
                    'entry_type_id'         =>Cache::remember($line['entry_type'], 60, function () use ($line) {
                                                    return $this->entryTypeService->getId($line['entry_type']);
                                                }),
                    'system_narration1'     => $line['system_narration1'],
                    'voucher_number'        => $line['voucher_no'],
                    'system_date'           => $line['system_date'],
                                            );
            };
            // $journal = Journal::updateOrCreate(['voucher_number' => $line['voucher_no']],[

            $documentReference = DocumentReference::where('reference', $line['document_reference'])->first();
            if(!$documentReference){
                $this->reference[] = array(
                    'reference' => $line['document_reference']
                );
            };
            // if(DocumentReference)
            // $documentReference = DocumentReference::updateOrCreate([
            //     'reference' => $line['document_reference']
            // ],[
            //     'reference' => $line['document_reference']
            // ]);
            // $this->journalEntry[] = array(
            //     'journal_id'            =>  $this->journalService->getId($line['voucher_no']),

            //     'voucher_serial'        => $line['voucher_serial'],

            //     'gl_code_id'            =>  Cache::remember($line['gl_code'], 60, function () use ($line) {
            //                                     return $this->glCodeService->getId($line['gl_code']);
            //                                 }),
            //     'document_reference_id'    => $documentReference->id,
            //     'transaction_amount'    => Str::remove('-',$line['transcation_amount']) ,
            //     'transaction_type'      => ($line['transcation_amount'] > 0) ? 'credit' : 'debit',
            // );
            // return JournalEntry::create([
            //     'journal_id'            =>  $journal->is,

            //     'voucher_serial'        => $line['voucher_serial'],

            //     'gl_code_id'            =>  Cache::remember($line['gl_code'], 60, function () use ($line) {
            //                                     return $this->glCodeService->getId($line['gl_code']);
            //                                 }),
            //     'document_reference_id'    => $documentReference->id,
            //     'transaction_amount'    => Str::remove('-',$line['transcation_amount']) ,
            //     'transaction_type'      => ($line['transcation_amount'] > 0) ? 'credit' : 'debit',
            // ]);

        });
        Cache::rememberForever($this->data,function ($d) use ($path) {

            return $this->data;
        });

        // Log::info($this->data);
        foreach (array_chunk($this->data,5000) as $t)
        {
            Journal::insert($t);
        }
        foreach (array_chunk($this->reference,5000) as $t)
        {
            DocumentReference::insert($t);
        }
        // foreach (array_chunk($this->journalEntry,10000) as $t)
        // {
        //     JournalEntry::insert($t);
        // }
    }
}
