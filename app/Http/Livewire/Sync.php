<?php

namespace App\Http\Livewire;

use App\Jobs\ImportDetail as ImportJob;
use App\Models\ImportDetail;
use App\Services\ImportDetailService;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Sync extends Component
{
    public ImportDetail $import;
    public $type = "sync";
    public $lastSync;

    protected $rules = [
        'type' => 'required'
    ];


    public function save(){


        $this->validate();
        $importdetail = new ImportDetailService();
        $values = [
            'type'          => 'provision',
            'status'        => 'Not-Started',
            'identifier'    => 'provision',
        ];
        // dd($values);
        // $create = $importdetail->create($values);
        $json = '{
            "general": {
                "organization_id": 4,
                "provision_setting_id": 1,
                "valuation_date": "2021-12-31",
                "bucket_name": "delta-staging",
                "url" : "mysql+pymysql://dbuser:P4ssw0rd@delta-backend-dev-rdsinstance-3cykkk1loyxi.cvzgesn1kpqy.me-south-1.rds.amazonaws.com:3306/dbtest",
                "created_by": 1,
                "import_detail_id": 3,
                "unallocated_portfolio_id": 2,
                "entry_type_id": 2
            },
            "gross_ibnr": {
                "key": "organization_id=4/files/provisions/ibnr_assumptions/",
                "period": 5,
                "lambda_output_params": {
                    "gross_ibnr": {
                        "gl_code_id": 103,
                        "system_narration1": "Gross IBNR reserve as at ",
                        "voucher_number": "00000005"
                    }
                }
            },
            "unearned_reserve": {
                "update_expiry_dates": "True",
                "lambda_output_params":{
                    "unearned_premium":{
                        "gl_code_id": 104,
                        "system_narration1": "Gross unearned premium reserve as at ",
                        "voucher_number": "00000005"
                    },
                    "unearned_admin":{
                       "gl_code_id": 105,
                        "system_narration1": "Gross unearned admin reserve as at ",
                        "voucher_number": "00000006"
                    },
                    "deferred_commission":{
                        "gl_code_id": 106,
                        "system_narration1": "Gross deferred commission reserve as at ",
                        "voucher_number": "00000007"
                    },
                    "loss_component":{
                        "gl_code_id": 107,
                        "system_narration1": "Gross deferred commission reserve as at ",
                        "voucher_number": "00000007"
                    }
                    }

                    }

                }
            ';
        $this->processJson($json);
        $payload = [
            "unearned-premium" => [
                "payload" => $json,
                "command" => "gross_ibnr",
                "identifier" => 1
            ],
            "unearned-premium-2" => [
                "payload" => $json,
                "command" => "unearned_reserve",
                "identifier" => 2
            ],
            "delta-fail" => [
                "payload" => $json,
                "command" => "temp",
                "identifier" => 3
            ],
            "delta-success" => [
                "payload" => $json,
                "command" => "post_entries",
                "identifier" => 4
            ]

        ];
        $payload = json_encode($payload, true);
        ImportJob::dispatch($payload);

        // $this->import->save();
    }
    public function processJson($json)
    {
        $json_decode = json_decode($json, true);
        dd($json_decode);
    }

    public function render()
    {
        return view('livewire.sync');
    }
}
