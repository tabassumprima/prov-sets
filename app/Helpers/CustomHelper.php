<?php

namespace App\Helpers;

use App\Models\{ChartOfAccount, Status, Level, ProvisionFile, ReProductsTreaty, ProductCode};
use App\Services\{ReportService, OrganizationService, SettingService, ImportDetailService};
use Illuminate\Support\{Collection, Str};
use Illuminate\Support\Facades\{Log, Storage};
use Hashids\Hashids;

class CustomHelper
{
    /**
     *
     * returns encoded string.
     * **/
    static function encode($id, $enc_str_len = 10)
    {
        $hashids = new Hashids(env('HASHIDS_SALT'), $enc_str_len);
        return $hashids->encode($id);
    }

    /**
     *
     * returns decoded id.
     * **/
    static function decode($encodedString, $enc_str_len = 10)
    {
        $hashids = new Hashids(env('HASHIDS_SALT'), $enc_str_len);
        return $hashids->decode($encodedString)[0] ?? '';
    }

    /**
     *
     *  returns collection of glcodes using chart of account level
     */
    static function fetchGlCodesWithChartOfAccount($levels = [93])
    {
        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();
        $parents = ChartOfAccount::scoped(['organization_id' => $organization_id])->whereIn('level_id', $levels)->get();
        $glcodes = new Collection();
        foreach ($parents as $parent) {
            $childofCOA[$parent->level_id] = $parent->descendants()->where('type', 'file')->get()->pluck('gl_code_id')->toArray();
            $glcodes = $glcodes->concat(collect($childofCOA)->last());
        }
        $glcodes = $glcodes->unique();

        return ['gl_codes' => $glcodes, 'mapping' => $childofCOA];
    }

    /**
     *
     *  returns collection of glcodes using chart of account level
     */
    static function fetchGlCodes($codes)
    {
        $organizationService = new OrganizationService();
        $organization_id     = $organizationService->getAuthOrganizationId();

        $levels  = Level::whereIn('code', $codes)->get()->pluck('id');
        $parents = ChartOfAccount::scoped(['organization_id' => $organization_id])->whereIn('level_id', $levels)->get();
        $glcodes = new Collection();
        foreach ($parents as $parent) {
            $childofCOA[$parent->level->code] = $parent->descendants()->where('type', 'file')->get()->pluck('gl_code_id')->toArray();
            $glcodes = $glcodes->concat(collect($childofCOA)->last());
        }
        $glcodes = $glcodes->unique();

        return ['gl_codes' => $glcodes, 'mapping' => $childofCOA];
    }

    static function log($message, $type = "info")
    {
        if (config('app.debug'))
            Log::{Str::title($type)}($message);
    }

    static function fetchOrganizationStorage($organization_id, $file_type, $import_id =  null, $summary_id = null)
    {
        /**
         * Adding additional query to get tenant_id. This is a temporary solution.
         * We will be passing tenand_id instead of organization_id in future.
         */
        $organizationService = new OrganizationService;
        $tenant_id = $organizationService->getTenantId($organization_id);
        $replacements = [
            'tenant_id' => $tenant_id,
            'import_id' => $import_id,
            'summary_id' => $summary_id
        ];
        $fileType = config("constant.s3_paths.{$file_type}");
        $path = Str::replaceArray('?', $replacements, $fileType);
        return $path;
    }

    static function fetchAdminStorage($file_type)
    {
        $fileType = config("constant.s3_paths.{$file_type}");
        $path = str_replace('?', 0, $fileType);
        return $path;
    }

    static function generateUniqueName($file)
    {
        $originalName    = $file->getClientOriginalName();
        $name            = explode('.', $originalName);
        $fileName        = head($name) . '_' . time() . '.' . last($name);
        return $fileName;
    }

    static function saveReport($sample, $organizationService, $filters, $type, $collection = null, $is_update = true)
    {
        $reportService   = new ReportService;
        $organization_id = $organizationService->getAuthOrganizationId();

        $report = [
            'organization_id'   => $organization_id,
            'type'              => $type,
            'result'            => json_encode($sample),
            'filters'           => json_encode($filters),
            'collection'        => $collection ? json_encode($collection) : null,
            'is_updated'        => $is_update
        ];
        $reportService->updateOrCreate($report);
    }

    static function downloadInvalidData()
    {
        $organizationService = new OrganizationService();
        $organization_id     = $organizationService->getAuthOrganizationId();

        $fileName = "errors.csv";
        $storage  = Self::fetchOrganizationStorage($organization_id, "organization_path");
        $path     = $storage . $fileName;

        return Storage::disk('private')->download($path);
    }

    static function fetchStatus($slug, $model = 'default')
    {
        return Status::where(['slug' => $slug, 'model' => $model])->first()->id;
    }

    static function fetchStatusesByModelSlug($models = [], $slugs = [])
    {
        return Status::whereIn('model', $models)->whereIn('slug', $slugs)->get();
    }

    static function fetchSessionProvision($request)
    {
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();

        $importDetailService = new ImportDetailService;
        $importDetail = $importDetailService->fetchLatestProvisionStatus($organization_id);

        self::updateProvisionSession($request, $importDetail);
    }

    static function updateProvisionSession($request, $importDetail)
    {
        if ($importDetail && in_array($importDetail->status->slug, ['started', 'running']) && in_array($importDetail->type, ['import', 'provision', 'opening', 'posting'])) {
            $request->session()->put('active_provision', true);
            $request->session()->put('type', $importDetail->type);
        } else {
            $request->session()->forget(['active_provision', 'type', 'valuation_date']);
        }
    }

    static function collectionToCsv($collection)
    {
        $csv_content = $collection->map(function ($row) {
            return implode(',', array_map(function ($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row));
        })->implode("\n");
        return $csv_content;
    }

    static function attachHeader($organziation_name, $business_type, $period, $report_type = null)
    {
        $template_string = "Company Name, ?,,,, \n? - ? Business,,,, \nFor the period, ?,,,,\n\n\n";
        $str  = Str::replaceArray('?', [$organziation_name, $report_type, $business_type, $period], $template_string);
        return $str;
    }


    static function download($filename, $content)
    {
        $headers = array(
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        );
        $data = [
            'content' => $content,
            'filename' => $filename
        ];
        // Create a response with the CSV content and headers
        return response($data, 200, $headers);
    }

    // Get File
    static function fetchFile($id)
    {
        return ProvisionFile::find(CustomHelper::decode($id));
    }

    // Download Files
    static function downloadFiles($id, $type)
    {
        $getFile = CustomHelper::fetchFile($id);

        $organizationService = new OrganizationService();

        $organizationId = $organizationService->getAuthOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_files.' . $type);

        $path = $filePath . $getFile->path;

        $file = Storage::disk('s3')->download($path);

        return $file;
    }

    static function mergeMissingGl($main_entries, ...$other_entries)
    {
        foreach ($other_entries as $other_entry) {
            if ($other_entry) {

                $differences = $other_entry->filter(function ($otherEntryData) use ($main_entries) {
                    // Check if $main_entries does not contain an item with the same 'key1' and 'key2'
                    return !$main_entries->contains(function ($mainEntryData) use ($otherEntryData) {
                        return $mainEntryData->id === $otherEntryData->id && $mainEntryData->shortcode === $otherEntryData->shortcode;
                    });
                });                // $difference = $other_entry-
                $main_entries = $main_entries->concat($differences);
            }
        }
        return $main_entries;
    }

    static function isActiveOrExpired($id, $model)
    {
        return $model->where('id', $id)->whereHas('status', function ($query) {
            $query->whereIn('slug', ['started', 'expired']);
        })->whereHas('organization', function ($query) {
            $query->where('isBoarding', false);
        })->exists();
    }

    // Get File Data
    static function getFileData($path, $type)
    {
        $organizationService = new OrganizationService();

        $organizationId = $organizationService->getAuthOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_files.' . $type);

        $path = $filePath . $path;

        $file = Storage::disk('s3')->get($path);

        return $file;
    }

    static function checkFileExistence($filePath, $fileName, $id = null)
    {
        $fileExists = Storage::disk('s3')->exists($filePath . $fileName);
        return [
            'id' => $id,
            'status' => $fileExists ? 1 : 0,
            'message' => $fileExists ? 'File Found' : 'File Not Found',
            'file_name' => $fileName,
        ];
    }

    // Download Dashboard Files
    static function dashboardFiles($fileName)
    {
        $organizationService = new OrganizationService();

        $organizationId = $organizationService->getTenantOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dashboard');

        $path = $filePath . $fileName;

        $file = Storage::disk('s3')->download($path);

        return $file;
    }


    static function FetchFileData($path, $type)
    {
        $organizationService = new OrganizationService();

        $organizationId = $organizationService->getTenantOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_files.' . $type);

        $path = $filePath . $path;

        $file = Storage::disk('s3')->get($path);

        return $file;
    }

    // Get Lambda Commands
    static function lambdaCommands()
    {
        $organizationService = new OrganizationService();

        $organizationId      = $organizationService->getTenantOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'provision_rules.' . 'lambda_commands');

        $fullPath = $filePath . 'rule.json';

        $file     = Storage::disk('s3')->get($fullPath);

        $jsonDecode = json_decode($file);

        return $jsonDecode;
    }

    static function has_any_relations($model, array $relations): bool
    {
        foreach ($relations as $relation) {
            if (method_exists($model, $relation) && $model->$relation()->exists()) {
                return true;
            }
        }
        return false;
    }


    static function fetchManagementExpenseLevel()
    {
        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();

        $settingsService = new SettingService();
        $settings = $settingsService->fetchByOrganizationId($organization_id);

        if ($settings) {
            $options = json_decode($settings->options, true);
            return $options['management_expense_level_id'] ?? null;
        }
        return null;
    }

    public static function getValuebyKey($key, $array)
    {
        if (isset($array[$key])) {
            return $array[$key];
        }
        return null;
    }

    public static function getKeybyValue($value, $array)
    {
        $key = array_search($value, $array);
        if ($key !== false) {
            return $key;
        }
        return null;
    }

    static function fetchSessionImport($request)
    {
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();

        $importDetailService = new ImportDetailService;
        $importDetail = $importDetailService->fetchRunningImportStatus($organization_id);

        self::updateImportSession($request, $importDetail);

        return $importDetail;
    }

    static function clearSession($request){
        $request->session()->forget(['active_provision', 'type', 'valuation_date']);
    }

    static function updateImportSession($request, $importDetail)
    {
        if ($importDetail)
        {
            $request->session()->put('active_provision', true);
            $request->session()->put('type', 'import');
        }
        else
        {
            $request->session()->forget(['active_provision', 'type', 'valuation_date']);
        }
    }

    public static function hasMissingRelations()
    {
        return ProductCode::whereDoesntHave('groupProducts')->exists() ||
               ReProductsTreaty::whereDoesntHave('groupTreaties')->exists() ||
               ProductCode::whereDoesntHave('groupFacultative')->exists();
    }
}
