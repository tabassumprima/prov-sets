<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\DocumentReference;
use App\Models\JournalEntry;
use App\Models\JournalMapping;
use App\Services\{JournalService,ReportService};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JournalEntryService{

    protected $model;

    public function __construct()
    {
        $this->model = new JournalEntry();
    }

    public function create($request, $import_detail_id)
    {
        // $this->verifyTransaction($request);

        $this->mergeRequest($request);
        extract($request->toArray());
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();
        $entryService = new EntryTypeService;
        $entryType = $entryService->fetchByType('delta');
        $entry_id = $entryType->id;
        // $organization = $organizationService->fetch(CustomHelper::encode($organization_id));
        $voucher_number = collect(DB::select($this->query($entry_id,$organization_id)))->first()->voucher_number;
        foreach($entries as $entry){
            $journal_id = $this->model->create([
                'branch_id'             => $branch_info_id,
                'voucher_type_id'       => $voucher_type_id,
                'accounting_year_id'    => $accounting_year_id,
                'system_narration1'     => $system_narration1,
                'business_type_id'      => $business_type_id,
                'system_date'           => $system_date,
                'organization_id'       => $organization_id,
                'system_department_id'  => $entry["system_department_id"],
                'entry_type_id'         => $entry_id,
                'voucher_number'        => $voucher_number,
                'unique_transaction'    => $this->generateUniqueTransaction($entryType, $voucher_number),
                'created_by'            => Auth::user()->id,
                "gl_code_id"            => $entry["gl_code_id"],
                "policy_number"         => $entry["policy_reference"],
                "voucher_serial"        => $entry["voucher_serial"],
                "transaction_amount"    => $entry["transaction_amount"],
                "transaction_type"      => $entry["transaction_type"],
                "import_detail_id"      => $import_detail_id
            ]);

            JournalMapping::create([
                'organization_id'           => $organization_id,
                'group_code_id'             => isset($entry["group_code_id"]) ? $entry["group_code_id"] : null,
                'treaty_group_code_id'      => isset($entry["treaty_group_code_id"]) ? $entry["treaty_group_code_id"] : null,
                'fac_group_code_id'         => isset($entry["fac_group_code_id"]) ? $entry["fac_group_code_id"] : null,
                'portfolio_id'               => $entry["portfolio_id"],
                'journal_entries_id'        => $journal_id->id,
                'import_detail_id'          => $import_detail_id
            ]);
        }
        // Invalidate all reports
        ReportService::invalidate();
        return;
    }


    public function delete($id)
    {
        $journalEntry = $this->fetch($id);
        $journalEntry->delete();
        return $journalEntry;
    }

    //Merge Extra fields to request (voucher_number, voucher_serial, transaction_amount)
    public function mergeRequest($request)
    {
        $entries = $request->entries;
        for ($i=0; $i < count($entries); $i++) {
            $entries[$i]['voucher_serial'] = $i+1;
            $entries[$i]['transaction_amount'] = $entries[$i]['debit'] ?? $entries[$i]['credit'];
            $entries[$i]['transaction_type'] = $entries[$i]['debit'] ? 'debit' : 'credit';
        };
        $request->merge(['entries' => $entries]);
    }

    //Check credit and debit is balanced or not
    public function verifyTransaction($request)
    {
        $entries  = $request->entries;
        $debit = 0;
        $credit = 0;

        for ($i = 0; $i < count($entries); $i++) {
            $debit += $entries[$i]['debit'];
            $credit += $entries[$i]['credit'];
        }
        $total = $debit - $credit;
        if ($total != 0) {
            throw new \Exception("Transaction is not balanced");
        }
    }

    public function fetchAll()
    {
        return $this->model->whereNull('approved_by')->with('glCode', 'transactionType')->paginate(15);
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function approve($id)
    {
        $journalEntry = $this->fetch($id);
        $journalEntry->approved_by = Auth::user()->id;
        $journalEntry->system_posting_date = now();
        $journalEntry->save();
    }

    public function generateVoucherNumber($organization)
    {
        return str_pad(rand(0, pow(10, 6)-1), 6, '0', STR_PAD_LEFT);
    }

    public function generateUniqueTransaction($entry, $voucher_number)
    {
        $str = "? - Manual Posting - ? ";
        $str = Str::replaceArray("?", [$entry->description,$voucher_number], $str);
        return $str;
    }

    public function fetchTransactionTypeId($type)
    {
        $transactionTypeService = new TransactionTypeService();
        return $transactionTypeService->getId($type);
    }

    public function fetchEntryTypeId($entryType, $type)
    {
        $entryTypeService = new EntryTypeService();
        return $entryTypeService->getId($entryType, $type);
    }


    public function fetchOrganizationId($systemDepartmentId)
    {
        $systemDepartmentService = new SystemDepartmentService();
        return $systemDepartmentService->fetchColumns('organization_id', $systemDepartmentId)->organization_id;
    }

    public function getSystemDeparmentId($profit_center_id)
    {
        $profitCenterService = new ProfitCenterService();
        $code = $profitCenterService->fetchColumns('code', $profit_center_id)->code;

        return substr($code, -2);
    }

    public function fetchOrCreateDocumentReferenceId($document_reference)
    {
        $document_reference_service = new DocumentReferenceService;
        return $document_reference_service->fetchOrCreate($document_reference);
    }

    public function getId($reference)
    {
        $query = DocumentReference::updateOrCreate(['reference' => $reference],
        [
            'reference' => $reference,
        ]);
        return $query->id;
    }


    public function fetchLedgerEntries($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id, $start = 0, $length = 10)
    {
        return $this->model
        ->selectRaw('
            (CASE WHEN transaction_amount < 0 THEN transaction_amount ELSE 0 END) AS credit,
            (CASE WHEN transaction_amount >= 0 THEN transaction_amount ELSE 0 END) AS debit,
            system_date, voucher_number, system_narration1, portfolios.name ,portfolios.shortcode'
        )
        ->leftJoin('journal_mappings', 'journal_entries.id', '=', 'journal_mappings.journal_entries_id') // Replace with actual column names
        ->leftJoin('portfolios', 'journal_mappings.portfolio_id', '=', 'portfolios.id') // Replace with actual column names
        ->when($portfolio_id != 'All', function ($query) use ($portfolio_id, $headOffice_id) {
                $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('journal_entries.business_type_id', $business_type_id); // Replace with actual column name
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('journal_entries.branch_id', $branch_id); // Replace with actual column name
        })
        ->where('journal_entries.accounting_year_id', $accounting_year) // Replace with actual column name
        ->where('journal_entries.gl_code_id', $glcode_id) // Replace with actual column name
        ->whereBetween('journal_entries.system_date', [$start_date, $end_date]) // Replace with actual column name
        ->paginate($length);
    }

    public function fetchLedgerEntriesWithoutPaginate($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id, $start = 0, $length = 10)
    {
        return $this->model
        ->selectRaw('(CASE WHEN transaction_amount < 0 THEN transaction_amount ELSE 0 END) AS credit,
        (CASE WHEN transaction_amount >= 0 THEN transaction_amount ELSE 0 END) AS debit,
            system_date, voucher_number, system_narration1,  COALESCE(portfolios.name) as portfolio_name  , portfolios.shortcode, transaction_amount, gl_codes.account_type'
        )
        ->leftJoin('journal_mappings', 'journal_entries.id', '=', 'journal_mappings.journal_entries_id') // Replace with actual column names
        ->leftJoin('portfolios', 'journal_mappings.portfolio_id', '=', 'portfolios.id') // Replace with actual column names
        ->leftJoin('gl_codes', 'journal_entries.gl_code_id', '=', 'gl_codes.id')
        ->when($portfolio_id != 'All', function ($query) use ($portfolio_id, $headOffice_id) {
                $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('journal_entries.business_type_id', $business_type_id); // Replace with actual column name
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('journal_entries.branch_id', $branch_id); // Replace with actual column name
        })
        ->where('journal_entries.accounting_year_id', $accounting_year) // Replace with actual column name
        ->where('journal_entries.gl_code_id', $glcode_id) // Replace with actual column name
        ->whereBetween('journal_entries.system_date', [$start_date, $end_date]) // Replace with actual column name
        ->get();
    }

    public function fetchLedgerEntriesClosing($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $start = 0, $length = 10)
    {
        return $this->model
        ->join('journal_mappings', 'journal_entries.id', '=', 'journal_mappings.journal_entries_id') // Replace with actual column names
        ->join('portfolios', 'journal_mappings.portfolio_id', '=', 'portfolios.id') // Replace with actual column names
        ->when($portfolio_id != 'All', function ($query) use ($portfolio_id) {
            $query->where('portfolios.id', $portfolio_id);
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('journal_entries.business_type_id', $business_type_id); // Replace with actual column name
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('journal_entries.branch_id', $branch_id); // Replace with actual column name
        })
        ->where('journal_entries.accounting_year_id', $accounting_year) // Replace with actual column name
        ->where('journal_entries.gl_code_id', $glcode_id) // Replace with actual column name
        ->whereBetween('journal_entries.system_date', [$start_date, $end_date]) // Replace with actual column name
        ->sum('transaction_amount');
    }

    public function fetchLedgerEntriesPageSum($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id, $start = 0, $length = 10)
    {
        return $this->model
        ->leftJoin('journal_mappings', 'journal_entries.id', '=', 'journal_mappings.journal_entries_id') // Replace with actual column names
        ->leftJoin('portfolios', 'journal_mappings.portfolio_id', '=', 'portfolios.id') // Replace with actual column names
        ->when($portfolio_id != 'All', function ($query) use ($portfolio_id, $headOffice_id) {
                $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
        })
        ->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('journal_entries.business_type_id', $business_type_id); // Replace with actual column name
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('journal_entries.branch_id', $branch_id); // Replace with actual column name
        })
        ->where('journal_entries.accounting_year_id', $accounting_year) // Replace with actual column name
        ->where('journal_entries.gl_code_id', $glcode_id) // Replace with actual column name
        ->whereBetween('journal_entries.system_date', [$start_date, $end_date]) // Replace with actual column name
        ->skip($start)->take($length)->get()->sum('transaction_amount');
    }

    public function fetchLedgerEntriesCount($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id = null)
    {
        return $this->model->when($portfolio_id != 'All', function($query) use ($business_type_id, $headOffice_id, $portfolio_id) {
            $query->with(['journalMappings'=>function($query) use($portfolio_id, $headOffice_id) {
                $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                    $q->where(function ($query) use ($portfolio_id){
                        $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                    });

                }, function($q) use ($portfolio_id){
                    $q->where('portfolio_id', $portfolio_id);

                });
        }]);
        })->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('business_type_id', $business_type_id);
        })->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })
        ->where('accounting_year_id', $accounting_year)
        ->where('gl_code_id', $glcode_id)
        ->whereBetween('system_date',[$start_date,$end_date])->count();
    }

    public function fetchLedgerEntriesSum($glcode_id,  $accounting_year, $start_date, $end_date, $business_type_id = "All", $portfolio_id = 'All', $branch_id = null, $headOffice_id = null)
    {
        return $this->model->when($portfolio_id != 'All', function($query) use ($business_type_id, $headOffice_id, $portfolio_id) {
            $query->with(['journalMappings'=>function($query) use($portfolio_id, $headOffice_id) {
                    $query->when($portfolio_id == $headOffice_id , function($q) use ($portfolio_id){
                        $q->where(function ($query) use ($portfolio_id){
                            $query->where('portfolio_id', $portfolio_id)->orWhereNull('portfolio_id');
                        });

                    }, function($q) use ($portfolio_id){
                        $q->where('portfolio_id', $portfolio_id);

                    });
            }]);
        })->when($business_type_id != 'All', function($query) use ($business_type_id) {
            $query->where('business_type_id', $business_type_id);
        })
        ->when($branch_id != 'All', function($query) use ($branch_id) {
            $query->where('branch_id', $branch_id);
        })
        ->where('accounting_year_id', $accounting_year)
        ->where('gl_code_id', $glcode_id)
        ->whereBetween('system_date',[$start_date,$end_date])->sum('transaction_amount');
    }

    public function fetchCriteriaAndGroupsByDate($date)
    {
        $criteriaService = new CriteriaService;

    }

    private function query($entry_id, $organization_id)
    {
        $query = "SELECT COALESCE(lpad((cast(max(voucher_number) as integer) + 1)::TEXT, 8,'0'), '00000001') as voucher_number
         FROM journal_entries je
         WHERE organization_id = ?
        AND entry_type_id = ?";

        return Str::replaceArray('?', [$organization_id, $entry_id], $query);
    }
}
