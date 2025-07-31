<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\{Log, Storage};
use Carbon\Carbon;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Services\{JournalEntryService, TrialBalanceService};
use Illuminate\Support\Facades\File;
use App\Helpers\CustomHelper;
use App\Models\GlCode;
use App\Models\JournalEntry;
use App\Services\TrialBalanceService as ServicesTrialBalanceService;
use Exception;
use Rap2hpoutre\FastExcel\FastExcel;
use Rap2hpoutre\FastExcel\SheetCollection;


class DisclosureService extends JournalEntryService
{
    public function __construct()
    {
        parent::__construct();
    }

    public function updateExcelSheet($request)
    {
        $glcodeService = new GlCodeService;
        $accountingService = new AccountingYearService;
        $organizationService = new OrganizationService;
        $businessTypeService = new BusinessTypeService;
        $settingService = new SettingService();

        $headOffice_id = $settingService->getOption('headoffice_portfolio_id');
        $portfolioService = new PortfolioService();
        $portfolio_shortcode = $portfolioService->fetch($headOffice_id)->shortcode;
        $organization_id = CustomHelper::encode($organizationService->getAuthOrganizationId());
        $organization = $organizationService->fetch($organization_id);
        $organization_name = $organization->name;

        $business_type_id = $request->input('business_type_id');
        $business_type = $businessTypeService->fetch($business_type_id)->description;

        $current_accounting_year_id = $request->input('accounting_year_id');
        $accounting_year_range = $accountingService->fetch($current_accounting_year_id);
        $prev_accounting_year = Carbon::parse($accounting_year_range->start_date)->subYear()->toDateString();
        $prev_accountingYearId = $accountingService->getIdByStartDate($prev_accounting_year);

        $years = [$current_accounting_year_id, $prev_accountingYearId];

        $glcodes_id = $glcodeService->fetchAll()->pluck('id');
        $opening_balance = $this->fetchopeningbalance($glcodes_id, $current_accounting_year_id, $business_type_id, $years, $portfolio_shortcode);
        $journal_entries_in_range = $this->fetchjournalentries($glcodes_id, $current_accounting_year_id, $business_type_id, $years, $portfolio_shortcode);

        $grouped_opening_balances = $opening_balance->groupBy('accounting_year_id');
        $grouped_journal_entries = $journal_entries_in_range->groupBy('accounting_year_id');
        $disclosurefileName = $fileName = "disclosure-pack_" . $accountingService->fetch($current_accounting_year_id)->year . ".zip";
        $sheets = [];
        foreach ($years as $accounting_year_id) {
            $accounting_year_range = $accountingService->getYearRange($accounting_year_id);

            $opening_balances = $grouped_opening_balances->get($accounting_year_id, collect());
            $journal_entries = $grouped_journal_entries->get($accounting_year_id, collect());
            $journal_entries = CustomHelper::mergeMissingGl($journal_entries, $opening_balances);

            $mergedCollection = $journal_entries->map(function ($item) use ($opening_balances) {
                $matchingItem = $opening_balances->where('id', $item['id'])->where('shortcode', $item['shortcode'])->first();
                $transaction = $item['credit'] + $item['debit'];
                $opening = isset($matchingItem['opening']) ? $matchingItem['opening'] : 0;
                return [
                    'Code' => $item['code'],
                    'Description' => $item['description'],
                    'Portfolio' => $item['shortcode'],
                    'Opening Balance' => $opening,
                    'Debit' => $item['debit'] ?? 0,
                    'Credit' => $item['credit'] ?? 0,
                    'Closing Balance' => $transaction + $opening
                ];
            });

            $headers = [
                [],
                [$organization_name],
                ["Disclosure Pack - $business_type Business"],
                ["For the period: $accounting_year_range"],
                [],
                ['Code', 'Description', 'Portfolio', 'Opening Balance', 'Debit', 'Credit', 'Closing Balance'],
            ];

            // Append actual data to the sheet
            foreach ($mergedCollection as $dataRow) {
                $headers[] = $dataRow;
            }
            if($accounting_year_id === $current_accounting_year_id)
                $sheets["Current Year"] = $headers;
            else if($accounting_year_id === $prev_accountingYearId)
                $sheets["Previous Year"] = $headers;
        }

        $sheetCollection = new SheetCollection($sheets);

        $tenant_id = $organizationService->getTenantId(CustomHelper::decode($organization_id));
        $path = str_replace('?', $tenant_id, "files/tenant_id=?/disclosure");
        Storage::disk('private')->makeDirectory($path);
        $tempExcelPath = $path . "/inputsheets.xlsx";
        (new FastExcel($sheetCollection))->export(Storage::disk('private')->path($tempExcelPath));

        $fileName = 'disclosure.xlsx';
        $s3ExcelFilePath = CustomHelper::fetchOrganizationStorage(CustomHelper::decode($organization_id), 'disclosure');
        $sourceFilePath = $s3ExcelFilePath . $fileName;

        if (!Storage::disk('s3')->exists($sourceFilePath)) {
            $this->initDisclosureFile(CustomHelper::decode($organization_id));
        }

        $templateFile = Storage::disk('s3')->get($sourceFilePath);
        $tempfilepath = $path . "/disclosure.xlsx";
        Storage::disk('private')->put($tempfilepath, $templateFile);

        $zipFilePath = Storage::disk("private")->path($path."/DisclosuresPack.zip");
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFile(Storage::disk('private')->path($tempExcelPath), basename($tempExcelPath));
            $zip->addFile(Storage::disk('private')->path($tempfilepath),  basename($tempfilepath));
            $zip->close();
        }

        Storage::disk('private')->delete([$tempExcelPath, $tempfilepath]);

        return response()->download($zipFilePath, $disclosurefileName, [
            'Content-Disposition' => 'attachment; filename="' . $disclosurefileName . '"',
        ])->deleteFileAfterSend(true);
    }

    public function fetchopeningbalance($glcodes_id, $accounting_year_id, $business_type_id, $years, $portfolio_shortcode)
    {
        return GlCode::selectRaw('sum(balance) as opening, gl_codes.id, gl_codes.code, gl_codes.description, accounting_year_id, coalesce(p.shortcode,?) as shortcode', [$portfolio_shortcode])
            ->leftJoin('opening_balances as ob', 'ob.gl_code_id', '=', 'gl_codes.id')
            ->leftJoin('opening_balance_mappings as obm', 'obm.opening_balance_id', '=', 'ob.id')
            ->leftJoin('portfolios as p', 'obm.portfolio_id', '=', 'p.id')
            ->whereIn('gl_codes.id', $glcodes_id)
            ->when($accounting_year_id, function ($query) use ($years) {
                $query->whereIn('ob.accounting_year_id', $years);
            })
            ->when($business_type_id != 'All', function ($query) use ($business_type_id) {
                $query->where('ob.business_type_id', $business_type_id);
            })->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'shortcode', 'accounting_year_id')->get();
    }

    public function fetchjournalentries($glcodes_id, $accounting_year_id, $business_type_id, $years, $portfolio_shortcode)
    {
        return GlCode::selectRaw('
            SUM(CASE WHEN je.transaction_amount < 0 THEN je.transaction_amount ELSE 0 END) AS credit,
            SUM(CASE WHEN je.transaction_amount >= 0 THEN je.transaction_amount ELSE 0 END) AS debit,
            gl_codes.id, gl_codes.code, gl_codes.description, accounting_year_id, coalesce(p.shortcode,?) as shortcode', [$portfolio_shortcode])
            ->leftJoin('journal_entries as je', 'je.gl_code_id', '=', 'gl_codes.id')
            ->leftJoin('journal_mappings as jm', 'jm.journal_entries_id', '=', 'je.id')
            ->leftJoin('portfolios as p', 'jm.portfolio_id', '=', 'p.id')
            ->whereIn('gl_codes.id', $glcodes_id)
            ->when($accounting_year_id, function ($query) use ($years) {
                $query->whereIn('je.accounting_year_id', $years);
            })
            ->when($business_type_id != 'All', function ($query) use ($business_type_id) {
                $query->where('je.business_type_id', $business_type_id);
            })->groupBy('gl_codes.id', 'gl_codes.code', 'gl_codes.description', 'shortcode', 'accounting_year_id')->get();
    }

    public function initDisclosureFile($organization_id)
    {
        $defaultFile = 'disclosure.xlsx';

        $adminStorage = CustomHelper::fetchAdminStorage('disclosure');
        $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'disclosure');

        $sourceFilePath = $adminStorage . $defaultFile;
        $destinationFilePath = $organizationStorage . $defaultFile;
        Storage::disk('s3')->copy($sourceFilePath, $destinationFilePath);
    }

    public function getDiscloureFile($organization_id)
    {
        $message = 'File download';
        try {
            $defaultFile = 'disclosure.xlsx';

            $adminStorage   = CustomHelper::fetchAdminStorage('disclosure');
            $sourceFilePath = $adminStorage . $defaultFile;

            $file = Storage::disk('s3')->get($sourceFilePath);

            $headers = [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $defaultFile . '"',
            ];
            return response($file, 200, $headers);
        } catch (Exception $e) {
            $message = $e->getMessage();
            Log::error($e);
        }

        return response()->json(['error' => 'An error occurred while updating the Excel file: ' . $message], 500);
    }
}
