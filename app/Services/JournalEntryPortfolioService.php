<?php

namespace App\Services;

use App\Helpers\CalculationHelper;
use App\Helpers\CustomHelper;
use App\Models\{AccountingYear, ChartOfAccount, GlCode, OpeningBalance, DocumentPortfolio, Sample};
use App\Models\{GroupCodePortfolio, JournalEntry, JournalMapping};
use App\Models\Report;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\{Collection, Str};
use Illuminate\Support\Facades\File;
use App\Services\ReportService;
use DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class JournalEntryPortfolioService
{
    protected $model;
    protected $dashboardCollection;

    protected $level_1, $level_2, $label_collections;
    public function __construct()
    {
        $this->model = new DocumentPortfolio();
        $this->dashboardCollection = collect([]);
        $this->label_collections = collect([]);
    }

    public function fetchDocumentReferenceByPortfolioIds($request)
    {
        // Fetch From Inputs
        $portfolio = $request->input('portfolio');
        $accounting_year = $request->input('accounting_year');
        $date_range = $request->input('date_range');
        $type = $request->input('type');
        $records_opening = new Collection(); //Initializing opening
        $accounting_service = new AccountingYearService;
        $settingService = new SettingService();
        $headOffice_id = $settingService->getOption('headoffice_portfolio_id');
        if ($type == 'BS' || $type == 'BREAKUP') {
            $start_date = Carbon::parse($date_range);
            $start_date = $start_date->toDateTimeString();
            $date = Carbon::parse($date_range)->toDateTimeString();
            $end_date = null;
            $start_date_comp = null;
            $end_date_comp = null;
        } else {
            $date = null;
            $dates = explode("to ", $date_range);
            $start_date = Carbon::parse($dates[0]);
            $start_date = $start_date->toDateTimeString();
            $end_date = Carbon::parse($dates[1])->toDateTimeString();
            $start_date_comp = Carbon::parse($dates[0])->subYear()->toDateTimeString();
            $end_date_comp = Carbon::parse($dates[1])->subYear()->toDateTimeString();
        }
        $accounting_year_range = $accounting_service->getYearRange($accounting_year);
        $accounting_year_start_date = Carbon::parse($accounting_service->get("start_date"));

        //Get detail data for comparision year to query year in journal entries
        $comparision_data = $this->getComparisonData($accounting_year);
        $compare_date_id = $comparision_data['compare_date_id'];
        $acc = $comparision_data['accounting_year'];
        $compare_date = $comparision_data['compare_date'];

        //Custom array to map year later
        $custom_year = [$accounting_year => $acc, $compare_date_id => $compare_date];


        // query_items will contain chart of account level id
        $businessService = new BusinessTypeService;
        $business_id = $businessService->getId('C');

        $organizationService = new OrganizationService;
        $reportJson = new FormatJsonService;
        $reportJson = $reportJson->fetchLatestReportFile($type);

        if ($reportJson == null) {
            abort(400, 'Please upload ' . $type  . ' file to proceed.');
        }

        $rulePath = CustomHelper::fetchOrganizationStorage($organizationService->getAuthOrganizationId(), 'report_type.' . strtolower($type));
        $path = Storage::disk('s3')->get($rulePath . "/" . $reportJson->file_name);
        $json = json_decode($path, true);
        // Verify glcodes
        $this->glLevelVerify($json['query_items']);

        //This function will return array (gl_codes, mapping)
        // glcodes['mapping'] will consist gl_code_id group by level
        $glcodes = CustomHelper::fetchGlCodes($json['query_items']);

        //fetch gl_codes_id. We will use to filter out journal entries
        $glcode = $glcodes['gl_codes'];
        // // Main query
        if (!$accounting_year_start_date->eq(Carbon::parse($start_date))) {
            $accounting_year_start_date_opening = $accounting_year_start_date->toDateTimeString();
            $compare_year_start_date_opening = $accounting_year_start_date->copy()->subYear()->toDateTimeString();
            $date_before_range_start = Carbon::parse($start_date)->subDay();
            $temp_date = $date_before_range_start->copy();
            $compare_date_before_range_start = $temp_date->subYear()->toDateTimeString();
            $date_before_range_start = $date_before_range_start->toDateTimeString();

            $records_opening = JournalEntry::selectRaw('sum(transaction_amount) as sum, gl_code_id as gl_code, accounting_year_id as accounting_year')
                ->leftJoin('journal_mappings', "journal_mappings.journal_entries_id", '=', 'journal_entries.id')
                ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                    $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                        $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                    });
                })
                ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                        $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                            $q->where(function ($query) use ($portfolio) {
                                $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                            });
                        }, function ($q) use ($portfolio) {
                            $q->where('portfolio_id', $portfolio);
                        });
                    });
                })
                ->where(function ($query) use ($accounting_year_start_date_opening, $compare_year_start_date_opening, $date_before_range_start, $compare_date_before_range_start) {
                    $query->whereBetween('system_date', [$accounting_year_start_date_opening, $date_before_range_start])
                        ->orWhereBetween('system_date', [$compare_year_start_date_opening, $compare_date_before_range_start]);
                })
                ->whereIn('gl_code_id', $glcode)->where(['journal_entries.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('accounting_year', 'gl_code')->get();
        }

        $records = JournalEntry::selectRaw('sum(transaction_amount) as sum, gl_code_id as gl_code, accounting_year_id as accounting_year')
            ->leftJoin('journal_mappings', "journal_mappings.journal_entries_id", '=', 'journal_entries.id')
            ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                    $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                });
            })
            ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                        $q->where(function ($query) use ($portfolio) {
                            $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                        });
                    }, function ($q) use ($portfolio) {
                        $q->where('portfolio_id', $portfolio);
                    });
                });
            })->when($type == 'BS' || $type == 'BREAKUP', function ($q, $qu) use ($date, $accounting_year_start_date) {
                $q->where(function ($query) use ($date, $accounting_year_start_date) {
                    $query->whereBetween('system_date', [$accounting_year_start_date, $date]);
                });
            }, function ($q) use ($start_date, $end_date, $start_date_comp, $end_date_comp) {
                $q->where(function ($query) use ($start_date, $end_date, $start_date_comp, $end_date_comp) {
                    $query->whereBetween('system_date', [$start_date, $end_date])
                        ->orWhereBetween('system_date', [$start_date_comp, $end_date_comp]);
                });
            })->whereIn('gl_code_id', $glcode)->where(['journal_entries.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('accounting_year', 'gl_code')->get();
        // Opening query if opening_balance is true otherwise opening balance will be empty array []
        $opening = OpeningBalance::selectRaw('sum(balance) as sum_opening, gl_code_id as gl_code, accounting_year_id as accounting_year')
            ->leftJoin('opening_balance_mappings', "opening_balance_mappings.opening_balance_id", '=', 'opening_balances.id')
            ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                    $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                });
            })
            ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                        $q->where(function ($query) use ($portfolio) {
                            $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                        });
                    }, function ($q) use ($portfolio) {
                        $q->where('portfolio_id', $portfolio);
                    });
                });
            })
            ->whereIn('gl_code_id', $glcode)->where(['opening_balances.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('accounting_year', 'gl_code')->get();
        $sample = $this->recordProcessing($records, $glcodes, $json, $accounting_year, $compare_date_id, $custom_year, $opening, $records_opening);


        $filters = [
            'period' => $accounting_year,
            'portfolio' => $portfolio,
            'date_range' => $date_range
        ];
        CustomHelper::saveReport($sample, $organizationService, $filters, $type);

        return $sample;
    }

    public function recordProcessing($records, $glcodes, $json, $accounting_year, $compare_date_id, $custom_year, $opening, $records_opening)
    {

        $sample = $data = [];
        $new_glcode = new Collection();
        $maps = collect($glcodes['mapping']);
        foreach ($json['line_items'] as $item) {
            try {
                if ($item['type'] == 'file') {
                    foreach ($maps as $key => $map) {
                        if (in_array($key, $item['level'])) {
                            $data[$key] = $map;
                            $new_glcode = $new_glcode->concat(collect($data[$key]));
                        }
                    }

                    $record = $records->whereIn('gl_code', $new_glcode)->groupBy('accounting_year');
                    $record_opening = $records_opening->whereIn('gl_code', $new_glcode)->groupBy('accounting_year');
                    $opening_records = !empty($opening) ? $opening->whereIn('gl_code', $new_glcode)->groupBy('accounting_year') : $opening;
                    if (count($record) == 1) {
                        if ($record->keys()->first() == $accounting_year)
                            $record->prepend(collect([]), $compare_date_id);
                        else
                            $record->put($accounting_year, collect([]));
                    }

                    if (count($record_opening) == 1) {
                        if ($record_opening->keys()->first() == $accounting_year)
                            $record_opening->prepend(collect([]), $compare_date_id);
                        else
                            $record_opening->put($accounting_year, collect([]));
                    }

                    if (count($opening_records) == 1) {
                        if ($opening_records->keys()->first() == $accounting_year)
                            $opening_records->prepend(collect([]), $compare_date_id);
                        else
                            $opening_records->put($accounting_year, collect([]));
                    }
                    // if $opening is empty array add years id to avoid errors
                    if (count($opening_records) == 0) {
                        $opening_records->put($compare_date_id, collect([]));
                        $opening_records->put($accounting_year, collect([]));
                    }

                    if (count($record_opening) == 0) {
                        $record_opening->put($compare_date_id, collect([]));
                        $record_opening->put($accounting_year, collect([]));
                    }

                    $has_journal_records = $record->count() > 0;
                    $has_opening_records = isset($item['opening_balance']) && $item['opening_balance'] == true && $opening_records->some->isNotEmpty(); // if any year's opening has data
                        
                    if (!$has_journal_records && !$has_opening_records) {
                        // No journal or opening records, return all 0s
                        $accounting_year_sum = collect($custom_year)->mapWithKeys(function ($i, $key) {
                            return [
                                $i => 0
                            ];
                        });
                    } else {
                        // At least one of journal or opening has data
                        $accounting_year_sum = collect($custom_year)->mapWithKeys(function ($i, $key) use ($record, $item, $opening_records, $record_opening, $custom_year) {
                            $record_data = $record[$key] ?? collect([]);
                            $record_sum = $record_data->sum('sum') ?? 0;

                            $record_opening_sum = isset($item['opening_balance']) && $item['opening_balance'] == true ? $record_opening[$key]->sum('sum_opening') : 0; //before range
                            $opening_sum = isset($item['opening_balance']) && $item['opening_balance'] == true ? $opening_records[$key]->sum('sum_opening') + $record_opening_sum : 0; //opening sum
                            if (isset($item['balance_type'])) {
                                $value = $this->assignValue($record_sum, $opening_sum, $item['balance_type']);
                            } else
                                $value = $record_sum + $opening_sum;// opening + before_range + range
                            if (isset($item['show_negative']) && $item['show_negative']) {
                                $value = $value <= 0 ? $value : 0;
                            } else if (isset($item['show_negative']) && $item['show_negative'] == false)
                                $value = $value >= 0 ? $value : 0;
                            return [
                                $i => isset($item['sign_reversal']) && $item['sign_reversal'] ? -$value : $value
                            ];
                        });
                    }

                    $sample[$item['slug']]['values'] = $accounting_year_sum;
                    $sample[$item['slug']]['description'] = $item['description'];
                    $sample[$item['slug']]['type'] = $item['type'];
                    $sample[$item['slug']]['class'] = isset($item['style']) ? $item['style'] : "";

                    //20212021 record key does not exists in some glcode
                    if (count($sample[$item['slug']]['values']) == 1) {
                        //manually adding key
                        $sample[$item['slug']]['values'][0] = 0;
                    }
                } elseif ($item['type'] == 'agg') {
                    $express = explode(" ", $item['expression']);

                    $first_key = $sample[$express[0]]['values']->keys()->first();
                    $second_key = $sample[$express[0]]['values']->keys()->last();


                    $sample[$item['slug']]['values'][$first_key] = (int) $sample[$express[0]]['values']->first();
                    $sample[$item['slug']]['values'][$second_key] = (int) $sample[$express[0]]['values']->last();

                    for ($i = 1; $i < count($express); $i++) {
                        if ($express[$i] == '+') {
                            $sample[$item['slug']]['values'][$first_key] += (int) $sample[$express[$i + 1]]['values']->first();
                            $sample[$item['slug']]['values'][$second_key] += (int) $sample[$express[$i + 1]]['values']->last();
                        } elseif ($express[$i] == '-') {
                            $sample[$item['slug']]['values'][$first_key] -= (int) $sample[$express[$i + 1]]['values']->first();
                            $sample[$item['slug']]['values'][$second_key] -= (int) $sample[$express[$i + 1]]['values']->last();
                        }
                    }
                    $value = collect($sample[$item['slug']]['values'])->mapWithKeys(function ($i, $key) use ($sample, $item) {
                        return [
                            $key => isset($item['sign_reversal']) && $item['sign_reversal'] ? - ($i) : $i
                        ];
                    });

                    $sample[$item['slug']]['values'] = $value;
                    $sample[$item['slug']]['description'] = $item['description'];
                    $sample[$item['slug']]['type'] = $item['type'];
                    $sample[$item['slug']]['class'] = $item['style'];
                } else if ($item['type'] == 'break') {
                    $sample[$item['slug']]['type'] = $item['type'];
                } else if ($item['type'] == 'heading') {
                    $sample[$item['slug']]['values'] = "";
                    $sample[$item['slug']]['description'] = $item['description'];
                    $sample[$item['slug']]['type'] = $item['type'];
                    $sample[$item['slug']]['class'] = isset($item['style']) ? $item['style'] : "";
                }
            } catch (Exception $e) {
                throw new Exception($e);
            }
            $new_glcode = collect();
        }
        return $sample;
    }

    public function sumGlCodes($collection, $level, $property)
    {
        return $collection->journalEntries()->withWhereHas('glCode.chartOfAccount', function ($query) use ($level, $property) {
            $query->where($level, $property);
        })->get();
    }

    public function assignValue($current_item, $opening_sum, $balance_type)
    {
        if ($balance_type == 'opening')
            return $opening_sum;
        elseif ($balance_type == 'transaction')
            return $current_item;
        elseif ($balance_type == 'closing')
            return $current_item + $opening_sum;
        else
            throw new Exception("Balance type incorrect");
    }

    #
    #   verify provided glcodes if exists in database
    #
    public function glLevelVerify($gllevel)
    {
        $chartOfAccountService = new ChartOfAccountService();
        $chartOfAccountService->verifyGlLevels($gllevel);
    }

    ####
    # Params: $accounting_year_id
    # return: array with (accounting_year, comparision_date, comparision_id)
    # desc: This function will take current year id and return array with
    # (accounting_year, comparision_date, comparision_id)
    ##
    public function getComparisonData($accounting_year_id)
    {

        // Fetch year i.e 20212021
        $acc = AccountingYear::where('id', $accounting_year_id)->first()->year;

        // Need comparision year i.e 20202020, so we will split current year by 4 digit -1 from both splitted data
        // and concat again i.e 20202020
        $compare_date = substr($acc, 4) - 1 . substr($acc, -4) - 1;

        //fetch id for comparision date
        $compare_date_id = AccountingYear::where('year', $compare_date)->first()?->id;

        //Throw error if comparision date id not found
        if (!$compare_date_id)
            throw new Exception('record for ' . $compare_date . ' does not exist');

        return [
            'accounting_year' => $acc,
            'compare_date' => $compare_date,
            'compare_date_id' => $compare_date_id
        ];
    }

    public function generateCSV($request)
    {
        // Fetch From Inputs
        $portfolio = $request->input('portfolio');
        $accounting_year = $request->input('accounting_year');
        $date_range = $request->input('date_range');
        $type = $request->input('type');
        $records_opening = new Collection(); //Initializing opening
        $accounting_service = new AccountingYearService;
        $settingService = new SettingService();
        $headOffice_id = $settingService->getOption('headoffice_portfolio_id');
        $portfolioService = new PortfolioService();
        $portfolio_name = $portfolioService->fetch($headOffice_id)->name;

        if ($type == 'BS' || $type == 'BREAKUP') {
            $start_date = Carbon::parse($date_range);
            $start_date = $start_date->toDateTimeString();
            $date = Carbon::parse($date_range)->toDateTimeString();
            $end_date = null;
            $start_date_comp = null;
            $end_date_comp = null;
        } else {
            $date = null;
            $dates = explode("to ", $date_range);
            $accounting_year_range = $accounting_service->getYearRange($accounting_year);
            $accounting_year_start_date = Carbon::parse($accounting_service->get("start_date"));
            $start_date = Carbon::parse($dates[0]);
            $start_date = $start_date->toDateTimeString();
            $end_date = Carbon::parse($dates[1])->toDateTimeString();
            $start_date_comp = Carbon::parse($dates[0])->subYear()->toDateTimeString();
            $end_date_comp = Carbon::parse($dates[1])->subYear()->toDateTimeString();
        }

        $accounting_year_range = $accounting_service->getYearRange($accounting_year);
        $accounting_year_start_date = Carbon::parse($accounting_service->get("start_date"));
        $organizationService = new OrganizationService;

        $organization_id = CustomHelper::encode($organizationService->getAuthOrganizationId());
        $organization = $organizationService->fetch($organization_id);
        $organization_name = $organization->name;
        $organization_shortcode = $organization->shortcode;


        //Get detail data for comparision year to query year in journal entries
        $comparision_data = $this->getComparisonData($accounting_year);
        $compare_date_id = $comparision_data['compare_date_id'];
        $acc = $comparision_data['accounting_year'];
        $compare_date = $comparision_data['compare_date'];

        //Custom array to map year later
        $custom_year = [$accounting_year => $acc, $compare_date_id => $compare_date];


        // query_items will contain chart of account level id
        $businessService = new BusinessTypeService;
        $business_id = $businessService->getId('C');
        $business_type = $businessService->fetch($business_id)->description;


        $reportJson = new FormatJsonService;
        $reportJson = $reportJson->fetchLatestReportFile($type);

        if ($reportJson == null) {
            abort(400, 'Please upload PNL file to proceed.');
        }

        $rulePath = CustomHelper::fetchOrganizationStorage($organizationService->getAuthOrganizationId(), 'report_type.' . strtolower($type));
        $path = Storage::disk('s3')->get($rulePath . "/" . $reportJson->file_name);
        $json = json_decode($path, true);
        // Verify glcodes
        $this->glLevelVerify($json['query_items']);

        //This function will return array (gl_codes, mapping)
        // glcodes['mapping'] will consist gl_code_id group by level
        $glcodes = CustomHelper::fetchGlCodes($json['query_items']);

        //fetch gl_codes_id. We will use to filter out journal entries
        $glcode = $glcodes['gl_codes'];
        // // Main query
        if (!$accounting_year_start_date->eq(Carbon::parse($start_date))) {
            $accounting_year_start_date_opening = $accounting_year_start_date->toDateTimeString();
            $compare_year_start_date_opening = $accounting_year_start_date->copy()->subYear()->toDateTimeString();

            $date_before_range_start = Carbon::parse($start_date)->subDay();
            $temp_date = $date_before_range_start->copy();
            $compare_date_before_range_start = $temp_date->subYear()->toDateTimeString();
            $date_before_range_start = $date_before_range_start->toDateTimeString();

            $records_opening = JournalEntry::selectRaw('sum(transaction_amount) as sum, gl_code_id as gl_code, coalesce(portfolios.name,?) as portfolio', [$portfolio_name])
                ->leftJoin('journal_mappings', "journal_mappings.journal_entries_id", '=', 'journal_entries.id')
                ->leftJoin('portfolios', "portfolios.id", "=", "journal_mappings.portfolio_id")
                ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                    $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                        $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                    });
                })
                ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                        $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                            $q->where(function ($query) use ($portfolio) {
                                $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                            });
                        }, function ($q) use ($portfolio) {
                            $q->where('portfolio_id', $portfolio);
                        });
                    });
                })
                ->where(function ($query) use ($accounting_year_start_date_opening, $compare_year_start_date_opening, $date_before_range_start, $compare_date_before_range_start) {
                    $query->whereBetween('system_date', [$accounting_year_start_date_opening, $date_before_range_start])
                        ->orWhereBetween('system_date', [$compare_year_start_date_opening, $compare_date_before_range_start]);
                })
                ->whereIn('gl_code_id', $glcode)->where(['journal_entries.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('gl_code', 'portfolios.name')->get();
        }
        // Main query
        $records = JournalEntry::selectRaw('sum(transaction_amount) as sum, gl_code_id as gl_code, coalesce(portfolios.name,?) as portfolio', [$portfolio_name])
            ->leftJoin('journal_mappings', "journal_mappings.journal_entries_id", '=', 'journal_entries.id')
            ->leftJoin('portfolios', "portfolios.id", "=", "journal_mappings.portfolio_id")
            ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                    $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                });
            })
            ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                        $q->where(function ($query) use ($portfolio) {
                            $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                        });
                    }, function ($q) use ($portfolio) {
                        $q->where('portfolio_id', $portfolio);
                    });
                });
            })
            ->when($type == 'BS' || $type == 'BREAKUP', function ($q, $qu) use ($date, $accounting_year_start_date) {
                $q->where(function ($query) use ($date, $accounting_year_start_date) {
                    $query->whereBetween('system_date', [$accounting_year_start_date, $date]);
                });
            }, function ($q) use ($start_date, $end_date, $start_date_comp, $end_date_comp) {
                $q->where(function ($query) use ($start_date, $end_date, $start_date_comp, $end_date_comp) {
                    $query->whereBetween('system_date', [$start_date, $end_date])
                        ->orWhereBetween('system_date', [$start_date_comp, $end_date_comp]);
                });
            })
            ->whereIn('gl_code_id', $glcode)->where(['journal_entries.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('gl_code', 'portfolios.name')->get();
        // Opening query if opening_balance is true otherwise opening balance will be empty array []
        $opening = OpeningBalance::selectRaw('sum(balance) as sum_opening, gl_code_id as gl_code, coalesce(portfolios.name,?) as portfolio', [$portfolio_name])
            ->leftJoin('opening_balance_mappings', "opening_balance_mappings.opening_balance_id", '=', 'opening_balances.id')
            ->leftJoin('portfolios', "portfolios.id", "=", "opening_balance_mappings.portfolio_id")
            ->when($accounting_year, function ($q, $accounting_year) use ($custom_year) {
                $q->when($accounting_year != 'ALL', function ($q) use ($custom_year) {
                    $q->whereIn('accounting_year_id', collect($custom_year)->keys());
                });
            })
            ->when($portfolio, function ($q) use ($portfolio, $headOffice_id) {
                $q->when($portfolio != 'ALL', function ($q) use ($portfolio, $headOffice_id) {
                    $q->when($portfolio == $headOffice_id, function ($q) use ($portfolio) {
                        $q->where(function ($query) use ($portfolio) {
                            $query->where('portfolio_id', $portfolio)->orWhereNull('portfolio_id');
                        });

                    }, function ($q) use ($portfolio) {
                        $q->where('portfolio_id', $portfolio);

                    });
                });
            })
            ->whereIn('gl_code_id', $glcode)->where(['opening_balances.organization_id' => $organizationService->getAuthOrganizationId(), 'business_type_id' => $business_id])->groupBy('portfolios.name', 'gl_code')->get();

        $sample = $this->recordProcessing1($records, $glcodes, $json, $date_range, $organization_name, $business_type, $opening, $records_opening, $type);
        $filename = Str::replaceArray("?", [$organization_shortcode, $accounting_year_range], "? - $type - ?.csv");
        return CustomHelper::download($filename, $sample);
    }



    public function recordProcessing1($records, $glcodes, $json, $date_range, $organization_name, $business_type, $opening, $record_opening, $type)
    {
        $sample = $data = [];
        $new_glcode = new Collection();
        $maps = collect($glcodes['mapping']);
        $portfolios = $records->pluck('portfolio')->unique()->toArray();
        $csvHeader = ['Description'];
        $csvHeader = array_merge($csvHeader, $portfolios);
        $csvHeader = collect($csvHeader)->map(function ($i) {
            return '"' . ($i ?? 'Unallocated') . '"';
        })->toArray();
        $csvContent = CustomHelper::attachHeader($organization_name, $business_type, $date_range, $type);
        $csvContent .= implode(',', $csvHeader) . "\n";
        foreach ($json['line_items'] as $item) {
            try {
                if ($item['type'] == 'file') {
                    foreach ($maps as $key => $map) {
                        if (in_array($key, $item['level'])) {
                            $data[$key] = $map;
                            $new_glcode = $new_glcode->concat(collect($data[$key]));
                        }
                    }
                    $groupedPortfolio = $records->whereIn('gl_code', $new_glcode)->groupBy('portfolio');
                    $groupedPortfolio_opening = $record_opening->whereIn('gl_code', $new_glcode)->groupBy('portfolio');
                    $opening_portfolio = $opening->whereIn('gl_code', $new_glcode)->groupBy('portfolio');

                    $final_collection = collect($portfolios)->map(function ($i, $key) use ($groupedPortfolio, $item, $opening_portfolio, $groupedPortfolio_opening) {
                        $opening = 0;
                        $before_range = 0;
                        if (isset($item['opening_balance']) && $item['opening_balance'] == true) {
                            $before_range = $groupedPortfolio_opening->get($i) ? $groupedPortfolio_opening->get($i)->sum('sum') : 0;
                            $opening = $opening_portfolio->get($i) ? $opening_portfolio->get($i)->sum('sum_opening') + $before_range : 0;
                        }
                        $total_sum = $groupedPortfolio->get($i) ? $groupedPortfolio->get($i)->sum('sum') : 0;
                        if (isset($item['balance_type'])) {
                            $value = $this->assignValue($total_sum, $opening, $item['balance_type']);
                        } else
                            $value = $total_sum + $opening; // opening + before_range + range

                        if (isset($item['show_negative']) && $item['show_negative'] == true)
                            $value = $value <= 0 ? $value : 0;
                        else if (isset($item['show_negative']) && !$item['show_negative'] == false)
                            $value = $value >= 0 ? $value : 0;

                        return isset($item['sign_reversal']) && $item['sign_reversal'] ? - ($value) : $value;
                    });

                    $sample[$item['slug']]['values'] = $final_collection;
                    $final_collection_1 = $final_collection->prepend($item['description']);
                    $csvContent .= implode(',', $final_collection_1->toArray()) . "\n";

                    $sample[$item['slug']]['description'] = $item['description'];
                } elseif ($item['type'] == 'agg') {
                    $express = explode(" ", $item['expression']);
                    for ($i = 1; $i <= count($portfolios); $i++) {
                        $sample[$item['slug']]['values'][$i] = $sample[$express[0]]['values'][$i];
                        for ($j = 1; $j < count($express); $j++) {
                            if ($express[$j] == '+') {
                                $sample[$item['slug']]['values'][$i] += (int) $sample[$express[$j + 1]]['values'][$i];
                            } elseif ($express[$j] == '-') {
                                $sample[$item['slug']]['values'][$i] -= (int) $sample[$express[$j + 1]]['values'][$i];
                            }
                        }
                    }
                    $sample[$item['slug']]['values'] = collect($sample[$item['slug']]['values'])->map(function ($i, $k) use ($item) {
                        return isset($item['sign_reversal']) && $item['sign_reversal'] ? - ($i) : $i;
                    });
                    $sample[$item['slug']]['values'] = collect($sample[$item['slug']]['values'])->prepend($item['description']);
                    $csvContent .= implode(',', $sample[$item['slug']]['values']->toArray()) . "\n";
                } else if ($item['type'] == 'heading') {

                    $collection[] = $item['description'];
                    for ($i = 1; $i <= count($portfolios); $i++) {
                        $collection[$i] = "";
                    }
                    $csvContent .= implode(',', $collection) . "\n";
                    $collection = [];
                }
            } catch (Exception $e) {
                throw new Exception($e);
            }
            $new_glcode = collect();
        }
        return $csvContent;
    }
    // Google Map
    public function googleMap($data)
    {
        $count = count($data['labels']['labels']);
        $collect = [];
        for ($i = 0; $i < $count; $i++) {
            $collect[$i]['name'] = $data['labels']['labels'][$i];
            $collect[$i]['density'] = $data['lossRatio']['data'][$i];
        }
        return $this->getCitiesCoordinates('properties.NAME_3', $collect);
    }

    // Get Cities Data
    public function getCitiesCoordinates($groupBy, $cities)
    {
        // Get Json File
        $jsonData = $this->getJsonFileData('cities-data.json');
        // Read the jsonData of the file
        $data = json_decode($jsonData, true);

        $collection = collect($data['features']);

        $groupedData = $collection->groupBy($groupBy);
        $names = collect([]);
        // Get the coordinates for each city in the list
        $coordinatesByCity = collect($cities)->map(function ($item, $key) use ($groupedData, &$names) {
            $variable = $groupedData->get($item['name'], collect())->toArray();
            for ($i = 0; $i < count($variable); $i++) {
                $names->push($item['name']);
                $variable[$i]['properties']['density'] = $item['density'];
            }
            return $variable;
        })->flatten(1);

        $name = $names->unique()->values();
        $keys = $groupedData->keys();
        $diff = $keys->diff($name);

        $remainingCities = $diff->map(function ($i, $k) use ($groupedData) {
            return $groupedData[$i];
        })->flatten(1);

        $collection = $coordinatesByCity->concat($remainingCities);
        return $collection;
    }

    // FlattenDlCodes
    public function flattenDLCodes($dlCodes, $skipKey)
    {
        return collect($dlCodes)->reduce(function ($carry, $category, $key) use ($skipKey) {
            if ($skipKey === $key) {
                return $carry; // Skip the specified key
            }
            return array_merge($carry, array_values($category));
        }, []);
    }

    // Dashboard Summary
    public function dashboardSummary($request)
    {
        $results = [];
        $filters = [
            'account
            ing_year_id' => $request->accounting_year_id,
            'portfolio_id' => $request->portfolio_id,
            'branch_id' => $request->branch_id,
            'business_type_id' => $request->business_type_id,
        ];
        $accounting_year = CustomHelper::decode($request->accounting_year_id);
        $branch = $request->branch_id == 'All' ? $request->branch_id : CustomHelper::decode($request->branch_id);
        $business_type = CustomHelper::decode($filters['business_type_id']);
        $portfolio = $request->portfolio_id == '' ? '' : collect($request->portfolio_id)->map(function ($i, $k) {
            return CustomHelper::decode($i);
        });

        // Get Json File
        $jsonData = $this->getJsonFileData('chart-data.json');
        $dlCodes = json_decode($jsonData, true);

        $output = $this->flattenDLCodes($dlCodes, 'config');
        $glcodes = CustomHelper::fetchGlCodes($output);

        $reportService = new ReportService();
        $report = $reportService->fetchByType(['result', 'filters', 'collection', 'is_updated'], 'dashboard');
        // when collection is already exists
        if (!$report || !$report->is_updated) {
            $finalResult = $this->queryBuilder($glcodes, $filters);
            $filterData = $finalResult->where('accounting_year_id', $accounting_year)->where('business_type_id', $business_type);
            $results = $this->dashboardArrayMapping($dlCodes, $filterData, $glcodes);
        } else {
            $finalResult = collect(json_decode($report->collection));
            $filtered_collection = $finalResult->when($portfolio != '', function ($q) use ($portfolio) {
                return $q->whereIn('portfolio_id', $portfolio);
            })->when($branch != 'All', function ($q) use ($branch) {
                return $q->where('branch_id', $branch);
            })->when($business_type, function ($q) use ($business_type) {
                return $q->where('business_type_id', $business_type);
            })->when($accounting_year, function ($q) use ($accounting_year) {
                return $q->where('accounting_year_id', $accounting_year);
            });

            $results = $this->dashboardArrayMapping($dlCodes, $filtered_collection, $glcodes);
        }
        $organizationService = new OrganizationService;
        CustomHelper::saveReport($results, $organizationService, $filters, 'dashboard', $finalResult);

        return $results;
    }
    public function fetchAllDlIds($data, &$dl_ids = [])
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                if (array_key_exists('dl_ids', $value)) {
                    $dl_ids = array_merge($dl_ids, $value['dl_ids']);
                }
                $this->fetchAllDlIds($value, $dl_ids);
            }
        }
        return collect($dl_ids);
    }

    public function decodeFilterAndReMerge($request)
    {
        $filters = [
            'accounting_year_id' => $request->accounting_year_id,
            'portfolio_id' => $request->portfolio_id,
            'branch_id' => $request->branch_id,
            'business_type_id' => $request->business_type_id,
        ];
        $accounting_year = CustomHelper::decode($request->accounting_year_id);
        $branch = $request->branch_id == 'All' ? $request->branch_id : CustomHelper::decode($request->branch_id);
        $business_type = CustomHelper::decode($filters['business_type_id']);
        $portfolio = $request->portfolio_id == '' ? '' : collect($request->portfolio_id)->map(function ($i, $k) {
            return CustomHelper::decode($i);
        });
        $request->merge([
            'accounting_year_id' => $accounting_year,
            'branch_id' => $branch,
            'business_type_id' => $business_type,
            'portfolio_id' => $portfolio
        ]);
        return [$request, $filters];
    }

    // Dashboard Summary for v2
    public function dashboardSummaryV2($orginal_request)
    {
        $results = array();
        [$request, $filters] = $this->decodeFilterAndReMerge($orginal_request);
        $is_update = $request->input('is_update', true);
        // dd($request->all());
        // Get Json File and decode it
        $jsonData = $this->getJsonFileData('new_graph.json');
        $dlCodes  = collect(json_decode($jsonData, true));
        $allDlIds = $this->fetchAllDlIds($dlCodes)->unique();
        $glcodes  = CustomHelper::fetchGlCodes($allDlIds);    // Remove duplicate values

        $reportService = new ReportService();
        $report        = $reportService->fetchByType(['result', 'filters', 'collection', 'is_updated'], 'dashboard-v2');
        // when collection is already exists
        if (!$report || !$report->is_updated || $is_update == 'false') {
            $mapping_collection = collect($glcodes['mapping']);

            // Query the database to get the data without filters
            $finalResult = $this->queryBuilderV2($glcodes);

            $mergedCollection = $mapping_collection->flatMap(function ($item, $key) use ($finalResult) {
                return $finalResult->whereIn('gl_code_id', $item)->map(function ($i) use ($key) {
                    $copy = clone $i;
                    $copy->dl_key = $key;
                    return $copy;
                });
            });

            // Group the data by dl_key, business_type_id, branch_id, accounting_year_id, month, year
            $grouped = $mergedCollection->groupBy(function ($item) {
                return $item->dl_key . '|||' . $item->business_type_id . '|||' . $item->branch_id . '|||' . $item->accounting_year_id . '|||' . $item->month . '|||' . $item->myyear . '|||' . $item->portfolio . '|||' . $item->portfolio_id;
            });

            // Sum the total_amount for each group
            $summed = $grouped->map(function ($group, $key) {
                $sum = $group->sum('total_amount');
                [$dl_key, $business_type_id, $branch_id, $accounting_year_id, $month, $myyear, $portfolio, $portfolio_id] = explode('|||', $key);
                return [
                    'dl_key' => $dl_key,
                    'business_type_id' => $business_type_id,
                    'branch_id' => $branch_id,
                    'accounting_year_id' => $accounting_year_id,
                    'month' => $month,
                    'myyear' => $myyear,
                    'portfolio' => $portfolio,
                    'portfolio_id' => $portfolio_id,
                    'total_amount' => $sum,
                ];
            })->values();

            $summed = $this->convertMonthToQuarter($summed); // Convert month to quarter
            // till here all data is grouped by dl_key, business_type_id, branch_id, accounting_year_id, month, year now we need to map this data to the json data
            $results = $this->jsonDataMapping($dlCodes, $summed, $request);
        } else {
            $collection     = $report->collection;
            $summed         = collect(json_decode($collection, true));
            $results        = $this->jsonDataMapping($dlCodes, $summed, $request);
        }

        $organizationService = new OrganizationService();
        CustomHelper::saveReport($results, $organizationService, $filters, 'dashboard-v2', $summed);
        return $results;
    }

    public function convertMonthToQuarter($collection)
    {
        return $collection->map(function ($item) {
            $quarter = (int) ceil($item['month'] / 3);
            return $item + [
                'quarter' => 'Q' . $quarter,
                'year' => $item['myyear'],
            ];
        });
    }
    public function groupFlatten($collection, $groupBy)
    {
        return $collection->groupBy(function ($item) use ($groupBy) {
            $key = '';
            foreach ($groupBy as $index => $value) {
                $lastKey = array_key_last($groupBy);
                if ($index === $lastKey) {
                    // Handle the last item case
                    $key .= $item[$value];
                } else {
                    // Handle the non-last item case
                    $key .= $item[$value] . "-";
                }

            }
            return $key;
        })->map(function ($group) {
            return $group->sum('total_amount');
        });
    }

    public function jsonDataMapping(&$jsonData, $data, $request)
    {
        // loop through the json data
        return collect($jsonData)->map(function ($value, $key) use ($data, $request) {
            if (is_array($value)) {
                if (isset($value['mainconfig'])) {
                    $value = $this->updateLabels($value, $data, $request);
                    return $this->jsonDataMapping($value, $data, $request);
                }
            }
            if (is_array($value)) {
                if (isset($value['config'])) {
                    // Update item and return the modified item
                    $value = $this->updateItem($value, $data, $request);
                    $hide = isset($value['config']['hidden']) ? $value['config']['hidden'] : false;
                    if ($hide) {
                        return null;
                    }
                    // update category that is saved in address
                    return $value;
                }

                // Recursively process nested arrays
                return $this->jsonDataMapping($value, $data, $request);
            }

            // Return unmodified value if not an array
            return $value;
        })->filter(function ($value) {
            return $value !== null;
        })->toArray();
    }

    protected function fetchDataByDlIds($data, $dlIds, $request, $exclude_filter = null)
    {
        $collection = $data->whereIn('dl_key', $dlIds);

        $filters = collect(['business_type_id', 'accounting_year_id', 'portfolio_id', 'branch_id']);

        foreach ($filters as $filter) {
            if ($filter == "portfolio_id" && $filter != $exclude_filter) {
                $hasNotEmpty = collect($request->get($filter))->contains(function ($value, $key) {
                    return $value != "";
                });

                // $hasNotEmpty means that "All" portfolio is selected, so ignore the filter
                $collection = $collection->when($hasNotEmpty, function ($q) use ($filter, $request) {
                    return $q->whereIn($filter, $request->get($filter));
                });
            } elseif ($filter != $exclude_filter && $request->get($filter) != 'All') {
                $collection = $collection->where($filter, $request->get($filter));
            }
        }
        return $collection;
    }

    /*
     * Compute the sum of the fetched data
     * is function mei hum saare aggregation waley kaam karenge
     */
    protected function computeSum($fetchedData, $config)
    {
        // dd($fetchedData);
        if ($config->has('group_by')) {
            $grouped = $this->groupFlatten($fetchedData, $config['group_by']);
            if (isset($config['sort']) && $config['sort'] == "keys_desc") {
                $grouped = $grouped->sortKeysDesc();
                // Take only 8 items for accounting year graphs
                $grouped = $grouped->take(8);

            }  elseif (isset($config['sort']) && $config['sort'] == "keys_asc") {
                $grouped = $grouped->sortKeys();
                // Take only 8 items for accounting year graphs
                $grouped = $grouped->take(8);
            }

            else {
                $grouped = $grouped->sort();
            }
            $this->label_collections[$this->level_1] = $grouped->keys();
            $values = $grouped->values();
            if (isset($config['negative']) && $config['negative']) {
                $values = $values->map(function ($value) {
                    return $value * -1;
                });
            }

            return [$values, $grouped->keys()];
        } else if ($config['type'] == 'calculation') {

            $methodName = CalculationHelper::$methods[$config['calculation']];

            // find if slug exists in the collection else you the value provided in the config
            $slug1 = $this->dashboardCollection->get($config['slug1'], $config['slug1']);
            $slug2 = $this->dashboardCollection->get($config['slug2'], $config['slug2']);
            if (method_exists(CalculationHelper::class, $methodName)) {
                $result = call_user_func([CalculationHelper::class, $methodName], $slug1, $slug2);
                return [$result, null]; // Outputs: Called with Hello, World!
            } else {
                Log::error("Method does not exist.");
                return null;
            }
        } else {
            $value = $fetchedData->sum('total_amount');
            if (isset($config['negative']) && $config['negative']) {
                $value = $value * -1;
            }
            return [$value, null];
        }
    }

    protected function updateLabels($item, $data, $request)
    {
        if (isset($item['mainconfig']) && is_array($item['mainconfig'])) {
            $config = collect($item['mainconfig']);

            $dls = $config['dl_isds'];
            foreach ($dls as $key => $dl) {
                $dls = collect($dl)->values();
                $fetchedData = $this->fetchDataByDlIds($data, $dls, $request, $config['exclude_filter']);
                [$sum, $labels] = $this->computeSum($fetchedData, $config);
                $this->dashboardCollection->put($key, $sum);
            };
            $item[$config['label_key']] = $labels;
            // For written premium structure
            if (isset($config['data_key']))
                $item[$config['data_key']] = $sum;
        }
        return $item;
    }
    protected function updateItem($item, $data, $request)
    {
        if (isset($item['slug'])) {
            $config = collect($item['config']);
            $property = $config['property'];

            if (isset($config['calculation']))
                [$sum, $labels] = $this->computeSum(null, $config);
            elseif ($this->dashboardCollection->has($item['slug'])) {
                $sum = $this->dashboardCollection->get($item['slug']);
                if (isset($config['negative']) && $config['negative']) {
                    $sum = $sum->map(function ($value) {
                        return $value * -1;
                    });
                }
            } else {
                $dls = $config['dl_ids'];
                $dls = collect($dls)->values();
                $fetchedData = $this->fetchDataByDlIds($data, $dls, $request);
                [$sum, $labels] = $this->computeSum($fetchedData, $config);
            }

            $item[$property] = $sum;

            $this->dashboardCollection->put($item['slug'], $sum);
        }

        return $item;
    }

    function findDlKeyForGlCodeId($dlCollections, $gl_code_id)
    {
        foreach ($dlCollections as $key => $values) {
            if (in_array($gl_code_id, $values)) {
                return $key;
            }
        }
        return null;
    }

    // Query bulder v2
    public function queryBuilderV2($glcodes)
    {
        $data = DB::table('journal_entries as je')
            ->select(
                'b.level2_desc as branch',
                'b.id as branch_id',
                'je.gl_code_id',
                'je.accounting_year_id',
                'je.business_type_id',
                'p.name as portfolio',
                'p.id as portfolio_id',
                'je.transaction_amount as amount',
                DB::raw('extract(month from je.system_date) as month'),
                DB::raw('extract(year from je.system_date) as myyear')
            )
            ->leftJoin('journal_mappings as jm', 'je.id', '=', 'jm.journal_entries_id')
            ->leftJoin('branches as b', 'b.id', '=', 'je.branch_id')
            ->leftJoin('portfolios as p', 'p.id', '=', 'jm.portfolio_id')
            ->whereIn('je.gl_code_id', $glcodes['gl_codes'])
            ->orderByDesc('je.system_date');

        $finalResult = DB::table(DB::raw("({$data->toSql()}) as data"))
            ->mergeBindings($data)
            ->select(
                'portfolio_id',
                'branch',
                'portfolio',
                'month',
                'myyear',
                'gl_code_id',
                'business_type_id',
                'accounting_year_id',
                'branch_id',
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('portfolio_id', 'branch', 'portfolio', 'month', 'myyear', 'gl_code_id', 'business_type_id', 'accounting_year_id', 'branch_id')
            ->get();
        return collect($finalResult);
    }

    // Query Builder
    public function queryBuilder($glcodes, $filters)
    {
        $accounting_year = CustomHelper::decode($filters['accounting_year_id']);
        $portfolio = $filters['portfolio_id'] == '' ? '' : collect($filters['portfolio_id'])->map(function ($i, $k) {
            return CustomHelper::decode($i);
        });
        $branch = CustomHelper::decode($filters['branch_id']);
        $business_type = CustomHelper::decode($filters['business_type_id']);
        $data = DB::table('journal_entries as je')
            ->select(
                'b.level2_desc as branch',
                'b.id as branch_id',
                'je.gl_code_id',
                'je.accounting_year_id',
                'je.business_type_id',
                'p.name as portfolio',
                'p.id as portfolio_id',
                'je.transaction_amount as amount',
                DB::raw('extract(month from je.system_date) as month'),
                DB::raw('extract(year from je.system_date) as myyear')
            )
            ->leftJoin('journal_mappings as jm', 'je.id', '=', 'jm.journal_entries_id')

            ->leftJoin('branches as b', 'b.id', '=', 'je.branch_id')
            ->leftJoin('portfolios as p', 'p.id', '=', 'jm.portfolio_id')
            ->whereIn('je.gl_code_id', $glcodes['gl_codes'])
            ->when($portfolio, function ($q) use ($portfolio) {
                $q->when($portfolio != '', function ($q) use ($portfolio) {
                    $q->whereIn('portfolio_id', $portfolio);
                });
            })
            ->when($branch, function ($q) use ($branch) {
                $q->when($branch != 'All', function ($q) use ($branch) {
                    $q->where('branch_id', $branch);
                });
            })
            ->orderByDesc('je.system_date');

        $finalResult = DB::table(DB::raw("({$data->toSql()}) as data"))
            ->mergeBindings($data)
            ->select(
                'portfolio_id',
                'branch',
                'portfolio',
                'month',
                'myyear',
                'gl_code_id',
                'business_type_id',
                'accounting_year_id',
                'branch_id',
                DB::raw('SUM(amount) as total_amount')
            )
            ->groupBy('portfolio_id', 'branch', 'portfolio', 'month', 'myyear', 'gl_code_id', 'business_type_id', 'accounting_year_id', 'branch_id')
            ->get();
        return $finalResult;
    }

    // Mapping
    public function dashboardArrayMapping($dlCodes, $finalResult, $glcodes)
    {
        $collect = [];
        // Mapping
        if (count($finalResult) > 0 && $finalResult != null) {
            foreach ($dlCodes as $key => $dlCode) {
                if ($key != 'config')
                    $collect[$key] = $this->collectionGrouping($key, $dlCodes[$key], $finalResult, $glcodes, $dlCodes['config'][$key]);
            }
        }
        // Map data
        if ($collect && isset($collect['googleMap'])) {
            $collect = array_merge($collect, ['googleMap' => $this->googleMap($collect['googleMap'])]);
        }
        $json = json_encode($collect);
        return $json;
    }

    public function dashboardArrayMappingV2($dlCodes, $finalResult, $glcodes)
    {
        // Mapping
        $collect = [];
        if (count($finalResult) > 0 && $finalResult != null) {
            foreach ($dlCodes as $key => $dlCode) {
                if ($key != 'config')
                    $collect[$key] = $this->collectionGrouping($key, $dlCodes[$key], $finalResult, $glcodes, $dlCodes['config'][$key]);
            }
        }
        $json = json_encode($collect);
        return $json;
    }

    // Collection Grouping
    public function collectionGrouping($key, $stats, $finalResult, $glcodes, $config)
    {
        $collect = [];
        $collect[$key] = collect($stats)->mapWithKeys(function ($value, $k) use ($finalResult, $glcodes, $config, $stats) {

            $gl_codes = collect($glcodes['mapping'])->get($value);
            if (isset($config['groupBy'])) {
                $data = $finalResult->whereIn('gl_code_id', $gl_codes)->groupBy($config['groupBy'])->mapWithKeys(function ($items, $ke) {
                    $sum = abs($items->sum('total_amount'));
                    $sum2 = round($sum);
                    return [$ke => $sum2];
                });
                if (isset($config['loop'])) {
                    $keys[$k]['name'] = $k;
                    $keys[$k]['data'] = $data->values();
                    $keys['labels']['labels'] = $data->keys()->sort()->values();
                } else {
                    $keys['data'] = $data->values();
                    $keys['labels'] = $data->keys();
                }
                return $keys;
            } else {
                $sumValues = $finalResult->whereIn('gl_code_id', $gl_codes)->sum('total_amount');
                $sum = abs($sumValues);
                return [$k => abs($sum)];
            }
        });

        if (isset($config['agg'])) {
            if (isset($config['loop']) && $config['loop'] == true) {
                $sumArray = $this->aggregatedSeries($config['agg'], $collect[$key], $config['agg_key']);
            } else {
                $sumArray = $this->aggregated($config['agg'], $collect[$key], $config['agg_key']);
            }
            $collect[$key] = $collect[$key]->merge($sumArray);
        }
        return $collect[$key];
    }

    // Aggregated
    public function aggregated($expression, $collection, $key)
    {
        $explode = explode(" ", $expression);
        $sum = $collection[$explode[0]];
        for ($i = 0; $i < count($explode); $i++) {
            if ($explode[$i] == "+") {
                $sum += $collection[$explode[$i + 1]];
            } elseif ($explode[$i] == "-") {
                $sum -= $collection[$explode[$i + 1]];
            } elseif ($explode[$i] == "/") {
                $sum = $sum / $collection[$explode[$i + 1]];
            } elseif ($explode[$i] == "%") {
                if ($collection[$explode[$i + 1]] != 0) {
                    $sum = $sum / ($collection[$explode[$i + 1]]) * 100;
                } else {
                    $sum = 0;
                }
            }
        }
        return collect([$key => abs($sum)]);
    }

    // AggregatedSeries
    public function aggregatedSeries($expression, $collection, $key)
    {
        $explode = explode(" ", $expression);
        $loops = $collection[$explode[0]]['data'];
        $sum = [];
        foreach ($loops as $index => $loop) {
            $sum[$index] = $loop;
            for ($i = 0; $i < count($explode); $i++) {
                if ($explode[$i] == "+") {
                    $sum[$index] += $collection[$explode[$i + 1]]['data'][$index];
                } elseif ($explode[$i] == "-") {
                    $sum[$index] -= $collection[$explode[$i + 1]]['data'][$index];
                } elseif ($explode[$i] == "/") {
                    $sum[$index] = $sum[$index] / $collection[$explode[$i + 1]]['data'][$index];
                } elseif ($explode[$i] == "%") {
                    if (!isset($collection[$explode[$i + 1]]['data'][$index])) {
                        $sum[$index] = (abs($sum[$index])) * 100;
                    } else if (count($collection[$explode[$i + 1]]['data']) > 0) {
                        $sum[$index] = (abs($sum[$index]) / abs($collection[$explode[$i + 1]]['data'][$index])) * 100;
                    } else {
                        $sum[$index] = 0;
                    }
                }
            }
        }

        $data[$key]['name'] = $key;
        $data[$key]['data'] = $sum;

        return $data;
    }

    // Get dashboard Json File for format
    private function getJsonFileData($fileName)
    {
        $organizationService = new OrganizationService();
        $organizationId = $organizationService->getAuthOrganizationId();

        $filePath = CustomHelper::fetchOrganizationStorage($organizationId, 'dashboard');
        $fullName = $filePath . $fileName;
        $jsonData = Storage::disk('s3')->get($fullName);
        return $jsonData;
    }
}
