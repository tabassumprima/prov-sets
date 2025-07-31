<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Models\{ChartOfAccount, GlCode, DocumentPortfolio, Sample};
use App\Models\{GroupCodePortfolio, JournalEntry};
use Exception;
use Illuminate\Support\Collection;

class BalanceTrialService
{


    public function updateRecords($request)
    {
        $business_type     = $request->input('business_type');
        // $path = public_path('/seeders/balancesheet-format.json');

        //Testing query
        $path = public_path('/seeders/test.json');
        $json = json_decode(file_get_contents($path), true);

        $glcodes = CustomHelper::fetchGlCodesWithChartOfAccount($json['query_items']);
        //glcodes['mapping'] include mapping of chart of account and glcode to avoid queries
        $glcode = GlCode::whereIn('id', $glcodes['gl_codes'])->get()->pluck('id');
        $records = JournalEntry::selectRaw('sum(transaction_amount) as sum, glcode.code as gl_code, accounting_year.year as accounting_year')
        ->leftJoin('accounting_years as accounting_year', "accounting_year.id", "=", "journal_entries.accounting_year_id" )
        ->leftJoin('gl_codes as glcode', "glcode.id", "=", "journal_entries.gl_code_id")
            ->when($business_type, function ($q, $business_type) {
                $q->whereIn('business_type', $business_type);
            })
            ->whereIn('gl_code_id', $glcode)->groupBy('accounting_year', 'gl_code')->get();
        $sample = $data = [];
        $new_glcode = new Collection();
        $maps = collect($glcodes['mapping']);
        foreach ($json['line_items'] as $item) {
            try {
                if ($item['type'] == 'file') {
                    foreach ($maps as $key => $map) {
                        if (in_array($key, $item['level'])) {
                            $data[$key] = GlCode::whereIn('id', $map)->get()->pluck('code')->toArray();
                            $new_glcode = $new_glcode->concat(collect($data)->last());
                        }
                    }
                    $sample[$item['slug']]['values'] = $records->whereIn('gl_code', $new_glcode)->pluck('sum', 'accounting_year');
                    $sample[$item['slug']]['description'] = $item['description'];
                    $sample[$item['slug']]['type'] = $item['type'];
                    $sample[$item['slug']]['class'] = isset($item['style']) ? $item['style'] : "";
                    //20212021 record key does not exists in some glcode
                    if (count($sample[$item['slug']]['values']) == 1) {
                        //manually adding key
                        $sample[$item['slug']]['values'][0] = 0;
                    }
                    //   if($item['slug'] == 'SC'){
                    //     dd($sample);
                    //   }
                } elseif ($item['type'] == 'agg') {
                    $express = explode(" ", $item['expression']);
                    // dd($sample[$express[0]]);
                    $first_key =  $sample[$express[0]]['values']->keys()->first();
                    $second_key =  $sample[$express[0]]['values']->keys()->last();

                    $sample[$item['slug']]['values'][$first_key] = (int)$sample[$express[0]]['values']->first();
                    $sample[$item['slug']]['values'][$second_key] = (int)$sample[$express[0]]['values']->last();
                    // if($item['slug'] == "TE"){
                    //     dd($second_key);
                    // }


                    for ($i = 1; $i < count($express); $i++) {
                        if ($express[$i] == '+') {
                            $sample[$item['slug']]['values'][$first_key] += (int)$sample[$express[$i + 1]]['values']->first();
                            $sample[$item['slug']]['values'][$second_key] += (int)$sample[$express[$i + 1]]['values']->last();
                        } elseif ($express[$i] == '-') {
                            $sample[$item['slug']]['values'][$first_key] -= (int)$sample[$express[$i + 1]]['values']->first();
                            $sample[$item['slug']]['values'][$second_key] -= (int)$sample[$express[$i + 1]]['values']->last();
                        }
                    }
                    $sample[$item['slug']]['description'] =  $item['description'];
                    $sample[$item['slug']]['type'] =  $item['type'];
                    $sample[$item['slug']]['class'] =  $item['style'];
                } else if ($item['type'] == 'break') {
                    $sample[$item['slug']]['type'] = $item['type'];
                }
            } catch (Exception $e) {
                throw new Exception("Bad Json");
            }
            $new_glcode = collect();
        }
        return $sample;
    }
}
