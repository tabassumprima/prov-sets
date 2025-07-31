<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Services\JournalEntryService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GeneralLedgerService extends JournalEntryService
{

    protected $request, $query;
    private $glCodeService;
    private $journalEntryService;
    private $accountingYearService;
    private $openingBalanceService;
    private $organizationService;
    private $businessTypeService;

    public function __construct($request = null)
    {
        parent::__construct();
        $this->glCodeService = new GlCodeService();
        $this->journalEntryService = new JournalEntryService();
        $this->accountingYearService = new AccountingYearService();
        $this->openingBalanceService = new OpeningBalanceService();
        $this->organizationService = new OrganizationService();
        $this->businessTypeService = new BusinessTypeService();
    }
    public function appendLedgerEntry($balance, $description)
    {
        return [[
            'date'          => null,
            'voucher'       => null,
            'description'   => $description,
            'portfolio name' => null,
            'portfolio'     => null,
            'account type'  => null,
            'debit'         => null,
            'credit'        => null,
            'balance'       => $balance
        ]];
    }

    public function queryBuilder($request, $start, $length)
    {
        // Extract form data from the request
        $formData = $request->input('formData');
        $page = $request->input('page');
        parse_str($formData, $formArray);

        // Process ledger entries and prepare final data
        $result = $this->processLedgerEntries($formArray, $start, $length, $page);
        if (isset($result['error'])) {
            return $result;
        }
        $merged = $result['data'];
        $count = $result['total'];
        return ["data" => $merged, 'total' => $count];
    }

    public function generateCSV($request)
    {
        // Process ledger entries and prepare final data
        return $this->processLedgerEntries1($request);
    }


    private function processLedgerEntries($formArray, $start, $length, $page)
    {
        $settingService     = new SettingService();
        $headOffice_id      = $settingService->getOption('headoffice_portfolio_id');
        $portfolioService   = new PortfolioService();
        $portfolio_shortcode           = $portfolioService->fetch($headOffice_id)->shortcode;

        $journal_entries_before_range = null;
        // Extract necessary parameters
        list($accounting_year_id, $portfolio_id, $business_type_id, $branch_id, $glcode_id) = $this->extractParameters($formArray);

        // Fetch accounting year range
        $accounting_year_range = $this->accountingYearService->getYearRange($accounting_year_id);

        if ($formArray['date_range'])
            $accounting_year_range = $formArray['date_range'];

        // Handle date range
        list($range_start_date, $range_end_date) = $this->handleDateRange($accounting_year_range);
        // accounting year dates are fetched from db
        $accounting_year_start_date = $this->accountingYearService->get("start_date");
        $accounting_year_start_date = Carbon::parse($accounting_year_start_date);

        if (!$accounting_year_start_date->eq($range_start_date)) {
            $date_before_range_start = $range_start_date->copy();
            $date_before_range_start = $date_before_range_start->subDay();
            $journal_entries_before_range = $this->journalEntryService->fetchLedgerEntriesSum($glcode_id,  $accounting_year_id, $accounting_year_start_date->toDateString(), $date_before_range_start->toDateString(), $business_type_id, $portfolio_id, $branch_id, $headOffice_id);
        }


        // Fetch and validate journal entries count
        $count_journal_entries = $this->getJournalEntriesCount($glcode_id, $accounting_year_id, $range_start_date, $range_end_date, $business_type_id, $portfolio_id, $branch_id, $headOffice_id);

        // Check if displaying more than 10,000 entries is possible
        if ($count_journal_entries > 10000) {
            return ["message" => 'Displaying more than 10,000 entries is not possible. Please download csv.', "error" => 500, "count" => $count_journal_entries];
        }

        // Fetch opening balance
        $opening_balance = $this->getOpeningBalance($glcode_id, $accounting_year_id, $business_type_id, $portfolio_id, $branch_id, $headOffice_id) + $journal_entries_before_range;
        // $closing = $this->prepareClosingBalance($glcode_id, $accounting_year_id, $range_start_date, $range_end_date, $business_type_id, $portfolio_id, $branch_id, $opening_balance);


        if ($page > 1)
            $opening_balance += $this->getSumOfEntriesBeforePage($glcode_id, $accounting_year_id, $range_start_date, $range_end_date, $business_type_id, $portfolio_id, $branch_id, $headOffice_id, $length, $page);

        // Fetch ledger entries in the specified range
        $journal_entries_in_range = $this->getLedgerEntriesInRange($glcode_id, $accounting_year_id, $range_start_date, $range_end_date, $business_type_id, $portfolio_id, $branch_id, $headOffice_id, $start, $length);
        $journal_entries_in_range = CustomHelper::mergeMissingGl($journal_entries_in_range, $journal_entries_before_range);
    
        // Process and prepare opening, entries, and closing
        $opening = null;
        if($page == 1){
            $opening = $this->appendLedgerEntry($opening_balance, 'Opening Balance');
        }


        $entries = $journal_entries_in_range->map(function ($item) use (&$opening_balance) {


            $transaction = $item['debit'] + $item['credit'];
            $opening_balance = $opening_balance + $transaction;
            return  [
                'date'          => $item['system_date'],
                'voucher'       => $item['voucher_number'],
                'description'   => $item['system_narration1'],
                'portfolio'     => $item['shortcode'],
                'debit'         => $item['debit'] ?? 0,
                'credit'        => $item['credit'] ?? 0,
                'balance'       => $opening_balance
            ];
        });

        $merged = collect($opening)->concat($entries);
        if ($this->isLastPage($count_journal_entries, $length, $page)) {
            if ($entries->count() > 0){
                $closing = $this->prepareClosingBalance($entries);
                $merged = $merged->concat($closing);
            }
        }
        return [
            'data' => $merged,
            'total' => $count_journal_entries,
        ];
    }

    private function extractParameters($formArray)
    {
        $accounting_year_id = $formArray['accounting_year_id'];
        $portfolio_id = $formArray['portfolio_id'];
        $business_type_id = $formArray['business_type_id'];
        $branch_id = $formArray['branch_id'];
        $glcode_id = $formArray['gl_code_id'];

        return [$accounting_year_id, $portfolio_id, $business_type_id, $branch_id, $glcode_id];
    }

    private function getOpeningBalance($glcodeId, $accountingYearId, $businessTypeId, $portfolioId, $branchId, $headOffice_id)
    {
        return $this->openingBalanceService->fetchLedgerOpeningSum($glcodeId, $accountingYearId, $businessTypeId, $portfolioId, $branchId, $headOffice_id);
    }

    private function getJournalEntriesCount($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id)
    {
        return $this->journalEntryService->fetchLedgerEntriesCount($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id);
    }

    private function handleDateRange($accountingYearRange)
    {

        // If a custom date range is provided, extract start and end dates
        list($range_start_date, $range_end_date) = explode(' to ', $accountingYearRange);
        $range_start_date = Carbon::parse($range_start_date);
        $range_end_date = Carbon::parse($range_end_date);


        return [$range_start_date, $range_end_date];
    }

    private function getLedgerEntriesInRange($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id, $start, $length)
    {
        // Assuming you have a method in JournalEntryService to fetch ledger entries within a specific range
        return $this->journalEntryService->fetchLedgerEntries($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id, $start, $length);
    }

    private function getLedgerEntriesInRangeWithoutPaginate($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id)
    {
        // Assuming you have a method in JournalEntryService to fetch ledger entries within a specific range
        return $this->journalEntryService->fetchLedgerEntriesWithoutPaginate($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id);
    }

    private function prepareClosingBalance($entries)
    {
        $balance = $entries->last()['balance'];
        return $this->appendLedgerEntry($balance, 'Closing Balance');
    }

    private function isLastPage($totalEntries, $length, $page)
    {
        // Assuming you have logic to determine if the current page is the last page
        $lastPage = ceil($totalEntries / $length);
        return ($page >= $lastPage);
    }
    private function getSumOfEntriesBeforePage($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id, $length, $page)
    {
        // Calculate the start and end indices for the entries before page 5
        $start = 0;  // Assuming the first page starts at 0
        $end = ($page -1) * $length ;  // Assuming each page has $length entries and we want entries before page 5

        // Fetch the ledger entries for the specified range
        return $this->journalEntryService->fetchLedgerEntriesPageSum($glcodeId, $accountingYearId, $startDate, $endDate, $businessTypeId, $portfolioId, $branchId, $headOffice_id, $start, $end);
    }

    private function processLedgerEntries1($formArray)
    {
        $settingService     = new SettingService();
        $headOffice_id      = $settingService->getOption('headoffice_portfolio_id');
        $portfolioService   = new PortfolioService();
        $portfolio_shortcode           = $portfolioService->fetch($headOffice_id)->shortcode;
        $organization_id = CustomHelper::encode($this->organizationService->getAuthOrganizationId());
        $organization = $this->organizationService->fetch($organization_id);
        $organization_name = $organization->name;
        $organization_shortcode = $organization->shortcode;
        $journal_entries_before_range = null;
        // Extract necessary parameters
        $accounting_year_id = $formArray->accounting_year_id;
        $portfolio_id = $formArray->portfolio_id;
        $business_type_id = $formArray->business_type_id;
        $branch_id = $formArray->branch_id;
        $glcode_id = $formArray->gl_code_id;
        $business_type = $this->businessTypeService->fetch($business_type_id)->description;
        $glcode = $this->glCodeService->fetch($glcode_id);


        // Fetch accounting year range
        $accounting_year_range = $this->accountingYearService->getYearRange($accounting_year_id);

        if ($formArray['date_range'])
            $accounting_year_range = $formArray['date_range'];

        // Handle date range
        list($range_start_date, $range_end_date) = $this->handleDateRange($accounting_year_range);
        // accounting year dates are fetched from db
        $accounting_year_start_date = $this->accountingYearService->get("start_date");
        $accounting_year_start_date = Carbon::parse($accounting_year_start_date);

        if (!$accounting_year_start_date->eq($range_start_date)) {
            $date_before_range_start = $range_start_date->copy();
            $date_before_range_start = $date_before_range_start->subDay();
            $journal_entries_before_range = $this->journalEntryService->fetchLedgerEntriesSum($glcode_id,  $accounting_year_id, $accounting_year_start_date->toDateString(), $date_before_range_start->toDateString(), $business_type_id, $portfolio_id, $branch_id);
        }


        // Fetch opening balance
        $opening_balance = $this->getOpeningBalance($glcode_id, $accounting_year_id, $business_type_id, $portfolio_id, $branch_id, $headOffice_id) + $journal_entries_before_range;
       
        // Fetch ledger entries in the specified range
        $journal_entries_in_range = $this->getLedgerEntriesInRangeWithoutPaginate($glcode_id, $accounting_year_id, $range_start_date, $range_end_date, $business_type_id, $portfolio_id, $branch_id, $headOffice_id);
        $journal_entries_in_range = CustomHelper::mergeMissingGl($journal_entries_in_range,  $journal_entries_before_range);
        
        // Process and prepare opening, entries, and closing
        $opening = $this->appendLedgerEntry($opening_balance, 'Opening Balance');
        $entries = $journal_entries_in_range->map(function ($item) use (&$opening_balance) {


            $transaction = $item['transaction_amount'];
            $opening_balance = $opening_balance + $transaction;
            return  [
                'date'          => $item['system_date'],
                'voucher'       => $item['voucher_number'],
                'description'   => $item['system_narration1'],
                'portfolio name' => $item['portfolio_name'],
                'portfolio'     => $item['shortcode'],
                'account type' => $item['account_type'],
                'debit'         => $item['debit'] ?? 0,
                'credit'        => $item['credit'] ?? 0,
                'balance'       => $opening_balance
            ];
        });

        $merged = collect($opening)->concat($entries);
        $closing = $this->prepareClosingBalance($merged);
        $merged = $merged->concat($closing);
        $csv_content = $this->attachHeader($organization_name, $business_type, $accounting_year_range, $glcode);
        $columnHeaders = collect($merged->first())->keys()->all();
        $columnHeaders = collect($columnHeaders)->map(function($value) {
            return Str::title($value);
        });

        $csv_content .= implode(',', $columnHeaders->toArray())."\n";

        $csv_content .= CustomHelper::collectionToCsv($merged);
        $filename = Str::replaceArray("?", [$organization_shortcode, $glcode->code, $accounting_year_range], "?-GL-?-?.csv");
        return CustomHelper::download($filename,$csv_content);
    }

    public function attachHeader($organziation_name, $business_type, $period, $glcode)
    {
        $template_string = "Company Name, ?,,,, \nGENERAL LEDGER - ? Business,? ?,,, \nFor the period, ?,,,,\n\n\n";
        $str  = Str::replaceArray('?', [$organziation_name, $business_type, $glcode->code, $glcode->description, $period], $template_string);
        return $str;
    }

    public function download($filename, $content)
    {
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        );
        // Create a response with the CSV content and headers
        return response($content, 200, $headers);
    }
}
