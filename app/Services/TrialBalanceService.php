<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use Illuminate\Support\Str;
use App\Services\JournalEntryService;
use Carbon\Carbon;
use Illuminate\Http\Response;


class TrialBalanceService extends JournalEntryService
{

    public function __construct()
    {
        parent::__construct();
    }

    public function queryBuilder($request)
    {
        $glcodeService = new GlCodeService;
        $accountingService = new AccountingYearService;
        $accounting_year_id = $request->input('accounting_year_id');
        // dd($accounting_year_id);
        $portfolio_id = $request->input('portfolio_id');
        $business_type_id = $request->input('business_type_id');
        $branch_id = $request->input('branch_id');
        $journal_entries_before_range = null;
        $settingService     = new SettingService();
        $headOffice_id      = $settingService->getOption('headoffice_portfolio_id');
        // fetch accounting range i.e "2022-01-01 to 2022-01-01"
        $accounting_year_range = $accountingService->getYearRange($accounting_year_id);

        //fetch all gl_codes id
        $glcodes_id = $glcodeService->fetchAll()->pluck('id');

        if($request->input('date_range'))
            $accounting_year_range = $request->input('date_range');

        // accounting year dates are fetched from db
        $accounting_year_start_date = $accountingService->get("start_date");

        // range dates are selected by user
        list($range_start_date, $range_end_date) = explode(' to ', $accounting_year_range);
        $accounting_year_start_date = Carbon::parse($accounting_year_start_date);
        $range_start_date = Carbon::parse($range_start_date);
        $range_end_date = Carbon::parse($range_end_date);

        if(!$accounting_year_start_date->eq($range_start_date))
        {
            $date_before_range_start = $range_start_date->subDay();
            $journal_entries_before_range = $glcodeService->fetchGlCodeSum($glcodes_id,  $accounting_year_id, $accounting_year_start_date->toDateString(), $date_before_range_start->toDateString(), $business_type_id, $portfolio_id, $branch_id, $headOffice_id );
        }

        $opening_balance = $glcodeService->fetchGlCodeOpening($glcodes_id, $accounting_year_id, $headOffice_id, $branch_id, $business_type_id, $portfolio_id);
        $journal_entries_in_range = $glcodeService->fetchGLCodeWithCreditDebit($glcodes_id, $accounting_year_id, $range_start_date->toDateString(), $range_end_date->toDateString(), $business_type_id, $portfolio_id, $branch_id, $headOffice_id);
        $journal_entries_in_range = CustomHelper::mergeMissingGl($journal_entries_in_range, $opening_balance, $journal_entries_before_range);
        $mergedCollection = $journal_entries_in_range->map(function ($item) use ($opening_balance, $journal_entries_before_range) {

            $matchingItem = $opening_balance->where('id', $item['id'])->first();
            if($journal_entries_before_range)
                $before_range = $journal_entries_before_range->where('id', $item['id'])->first();

            $transaction = $item['credit'] + $item['debit'];
            $opening = isset($matchingItem['opening']) ? $matchingItem['opening'] :0;
            $before_range = isset($before_range) ? $before_range['transaction']: 0;
            return  [
                'code'          => $item['code'],
                'description'   => $item['description'],
                'opening'       => $opening + $before_range ,
                'credit'        => $item['credit'] ?? 0,
                'debit'         => $item['debit'] ?? 0,
                'closing'       => $transaction + $opening + $before_range
            ];
        });

        $filters = [
            'period'    => $accounting_year_id,
            'portfolio' => $portfolio_id,
            'business'  => $business_type_id,
            'branch'    => $branch_id,
            'date_range'=> $accounting_year_range
        ];
        CustomHelper::saveReport($mergedCollection, new OrganizationService, $filters, "trial-balance");
        return $mergedCollection;

    }

    public function sum($data)
    {
        $arr = [];
        $credit = 0;
        $debit = 0;
        foreach ($data as $item) {
            $arr[$item->journal_id]['glCode'] = $item->glCode->code;
            $arr[$item->journal_id]['description'] = $item->journal->system_narration1;
            if ($item->transaction_type == 'credit') {

                $credit += $item->transaction_amount;
                $arr[$item->journal_id]['credit'] = $credit;
                $debit = 0;
            } else if ($item->transaction_type == 'debit') {

                $debit += $item->transaction_amount;
                $arr[$item->journal_id]['debit'] = $debit;
                $credit = 0;
            }
        }
       return $arr;
    }

    public function generateCSV($request)
    {
        $glcodeService = new GlCodeService;
        $accountingService = new AccountingYearService;
        $organizationService = new OrganizationService;
        $businessTypeService = new BusinessTypeService;
        $settingService     = new SettingService();
        $headOffice_id      = $settingService->getOption('headoffice_portfolio_id');
        $portfolioService   = new PortfolioService();
        $portfolio_shortcode           = $portfolioService->fetch($headOffice_id)->shortcode;
        $organization_id = CustomHelper::encode($organizationService->getAuthOrganizationId());
        $organization = $organizationService->fetch($organization_id);
        $organization_name = $organization->name;
        $organization_shortcode = $organization->shortcode;
        $accounting_year_id = $request->input('accounting_year_id');
        $portfolio_id = $request->input('portfolio_id');
        $business_type_id = $request->input('business_type_id');
        $branch_id = $request->input('branch_id');
        $journal_entries_before_range = null;
        $business_type = $businessTypeService->fetch($business_type_id)->description;

        // fetch accounting range i.e "2022-01-01 to 2022-01-01"
        $accounting_year_range = $accountingService->getYearRange($accounting_year_id);

        //fetch all gl_codes id
        $glcodes_id = $glcodeService->fetchAll()->pluck('id');

        if($request->input('date_range'))
            $accounting_year_range = $request->input('date_range');

        // accounting year dates are fetched from db
        $accounting_year_start_date = $accountingService->get("start_date");

        // range dates are selected by user
        list($range_start_date, $range_end_date) = explode(' to ', $accounting_year_range);
        $accounting_year_start_date = Carbon::parse($accounting_year_start_date);
        $range_start_date = Carbon::parse($range_start_date);
        $range_end_date = Carbon::parse($range_end_date);
        if(!$accounting_year_start_date->eq($range_start_date))
        {
            $date_before_range_start = $range_start_date->subDay();
            $journal_entries_before_range = $glcodeService->fetchGlCodeSumPortfolios($glcodes_id,  $accounting_year_id, $accounting_year_start_date->toDateString(), $date_before_range_start->toDateString(), $business_type_id, $portfolio_id, $branch_id, $headOffice_id, $portfolio_shortcode);
        }

        $opening_balance = $glcodeService->fetchGlCodeWithPortfoliosOpening($glcodes_id, $accounting_year_id,$branch_id, $headOffice_id, $business_type_id, $portfolio_id, $portfolio_shortcode);
        $journal_entries_in_range = $glcodeService->fetchGLCodeWithCreditDebitPortfolios($glcodes_id, $accounting_year_id, $range_start_date->toDateString(), $range_end_date->toDateString(), $business_type_id, $portfolio_id, $branch_id, $headOffice_id, $portfolio_shortcode);
        $journal_entries_in_range = CustomHelper::mergeMissingGl($journal_entries_in_range, $opening_balance, $journal_entries_before_range);
        $mergedCollection = $journal_entries_in_range->map(function ($item) use ($opening_balance, $journal_entries_before_range) {

            $matchingItem = $opening_balance->where('id', $item['id'])->where('shortcode', $item['shortcode'])->first();

            if($journal_entries_before_range)
                $before_range = $journal_entries_before_range->where('id', $item['id'])->where('shortcode', $item['shortcode']);

            $transaction = $item['credit'] + $item['debit'];
            $opening = isset($matchingItem['opening']) ? $matchingItem['opening'] :0;
            $before_range = isset($before_range['transation']) ? $before_range['transaction']: 0;
            return  [
                'code'          => $item['code'],
                'description'   => $item['description'],   
                'portfolio'     => $item['shortcode'],
                'portfolio name' => $item['portfolio_name'],
                'account type' => $item['account_type'],
                'opening'       => $opening + $before_range ,
                'debit'         => $item['debit'] ?? 0,
                'credit'        => $item['credit'] ?? 0,
                'closing'       => $transaction + $opening + $before_range
            ];
        });
        $csv_content = $this->attachHeader($organization_name, $business_type, $accounting_year_range);
        $columnHeaders = collect($mergedCollection->first())->keys()->all();
        $columnHeaders = collect($columnHeaders)->map(function($value) {
            return Str::title($value);
        });

        $csv_content .= implode(',', $columnHeaders->toArray())."\n";

        $csv_content .= CustomHelper::collectionToCsv($mergedCollection);
        $filename = Str::replaceArray("?", [$organization_shortcode, $accounting_year_range], "? - Trial Balance - ?.csv");
        return CustomHelper::download($filename,$csv_content);
    }

    public function attachHeader($organziation_name, $business_type, $period)
    {
        $template_string = "Company Name, ?,,,, \nTRIAL BALANCE - ? Business,,,, \nFor the period, ?,,,,\n\n\n";
        $str  = Str::replaceArray('?', [$organziation_name, $business_type, $period], $template_string);
        return $str;
    }

    public function download($filename, $content)
    {
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename='.$filename,
        );
        // Create a response with the CSV content and headers
        return new Response($content, 200, $headers);
    }
}
