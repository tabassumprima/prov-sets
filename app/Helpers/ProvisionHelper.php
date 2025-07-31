<?php

namespace App\Helpers;

use App\Services\EntryTypeService;
use App\Services\LambdaFunctionService;
use App\Services\OrganizationService;
use App\Services\ProvisionSettingService;
use App\Services\SettingService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProvisionHelper
{
    private $payload = [];
    private $bucket_name;
    private $valuation_date, $import_detail_id, $organization_id, $organizatonService, $provisionSettingService, $entryTypeService, $config, $settingService, $options, $tenant_id;


    public function __construct($valuation_date, $import_detail_id, $tenant_id, $organization_id = null)
    {
        $this->organizatonService = new OrganizationService;
        $this->provisionSettingService = new ProvisionSettingService;
        $this->entryTypeService = new EntryTypeService;
        $this->settingService = new SettingService;
        $this->bucket_name = config('constant.aws_bucket');

        $this->valuation_date = $valuation_date;
        $this->import_detail_id = $import_detail_id;
        $this->options = $this->settingService->getOptions();
        $this->organization_id = $organization_id ?? $this->organization_id;
        $this->tenant_id    = $tenant_id;
    }


    public function invokeProvision()
    {
        // $this->config = $this->generateGeneral();


        $lambdaService = new LambdaFunctionService;
        $lambdas = $lambdaService->fetchAllActive([$this->options['fail_lambda_id'], $this->options['post_entry_lambda_id'], $this->options['opening_balance_lambda_id']]);



        foreach($lambdas as $key => $lambda){
            $this->payload['data'][$key]['sub_command'] = $lambda->id;
            $this->appendProvision($key, 'data');
            $this->appendTenantId($this->tenant_id, $key, 'data');
            $this->appendCommand('provision', $key, 'data');
            $this->payload['data'][$key]['payload'] = [];
        }
        $this->appendGroupCodes();
        $this->appendTableDelete();
        $this->appendSuccess();
        $this->appendFail();
        return $this->payload;

    }

    public function appendPostEntry($lambda_id, $key)
    {
        return $this->payload[$key]['success'] = $lambda_id;
    }

    public function appendCommand($command, $key, $secondary_key)
    {
        return $this->payload[$secondary_key][$key]['command'] = $command;
    }

    public function appendFailed($lambda_id, $key)
    {
        return $this->payload[$key]['failed'] = $lambda_id;
    }

    public function appendTenantId($tenant_id, $key, $secondary_key)
    {
        return $this->payload[$secondary_key][$key]['tenant_id'] = $tenant_id;
    }
    public function appendDB($host, $user, $password, $name, $key, $secondary_key, $port = '5432', $table_prefix = '')
    {
        return $this->payload[$secondary_key][$key]['db'] = [
            'host'  => $host,
            'user'  => $user,
            'password'  => $password,
            'name'  => $name,
            'port'  => $port,
            'table_prefix'  => $table_prefix
        ];
    }

    public function appendGroupCodes()
    {
        return $this->payload['group_codes'] = [
            "command" => "group_codes",
            "sub_command" => "group_codes",
            "tenant_id" => $this->appendTenantId($this->tenant_id, null, 'delta-success'),
            "payload" => [],
            "group_codes" => [
                "organization_id" => $this->organization_id,
                "import_detail_id" => $this->import_detail_id,
                "transition_date" => $this->options['transition_date'], # change this key after merge
                "tables" => [
                    "journal_mappings",
                    "treaty_group_mappings",
                    "treaty_group_codes",
                    "fac_group_mappings",
                    "fac_group_codes",
                    "policy_group_mappings",
                    "group_codes"
                ],
            ]
        ];
    }

    public function appendProvision($key, $secondary_key)
    {
        return $this->payload[$secondary_key][$key]['provision'] = [
                'organization_id'               => $this->organization_id,
                'provision_setting_id'          => $this->provisionSettingService->fetchActiveProvisionSetting()->id,
                'valuation_date'                => Carbon::createFromFormat('j F, Y', $this->valuation_date)->toDateString(),
                'bucket_name'                   => $this->bucket_name,
                'import_detail_id'              => $this->import_detail_id,
                'unallocated_portfolio_id'      => $this->options['unallocated_portfolio_id'],
                'marine_products_id'            => $this->options['marine_products_id'],
                'marine_reproducts_id'          => $this->options['marine_reproducts_id'],
                'lambda_posting_voucher_id'     => $this->options['lambda_posting_voucher_id'],
                'marine_exposure_days'          => $this->options['marine_exposure_days'],
                'ibnr_period_year'              => $this->options['ibnr_period_year'],
                'discounting_period_year'       => $this->options['discounting_period_year'],
                'headoffice_portfolio_id'       => $this->options['headoffice_portfolio_id'],
                'management_expense_level_id'   => $this->options['management_expense_level_id'],
                'entry_type_id'                 => $this->entryTypeService->fetchByType('delta')->id,
                'created_by'                    => Auth::user()->id,
                'ibnr_path'                     => CustomHelper::fetchOrganizationStorage($this->organization_id, 'provision_files.ibnr_assumptions'),
                'ra_path'                       => CustomHelper::fetchOrganizationStorage($this->organization_id, 'provision_files.risk_adjustments'),
                'discount_rate_path'            => CustomHelper::fetchOrganizationStorage($this->organization_id, 'provision_files.discount_rates'),
                'claim_pattern_path'            => CustomHelper::fetchOrganizationStorage($this->organization_id, 'provision_files.claim_patterns'),
                'provision_path'                => Str::replaceArray('?', [$this->import_detail_id], CustomHelper::fetchOrganizationStorage($this->organization_id, 'provision_files.output')."?/")

        ];

    }

    public function appendSuccess()
    {
        $this->payload['delta-success'][null]['sub_command'] = $this->options['post_entry_lambda_id'];
        $this->appendCommand('provision', null, 'delta-success');
        $this->appendProvision(null, 'delta-success');
        $this->appendTenantId($this->tenant_id, null, 'delta-success');
        $this->payload['delta-success'][null]['payload'] = [];
        $this->payload['delta-success'] = $this->payload['delta-success'][null];
    }

    public function appendFail()
    {
        $this->payload['db-fail'][null]['sub_command'] = $this->options['fail_lambda_id'];
        $this->appendCommand('provision', null, 'db-fail');
        $this->appendProvision(null, 'db-fail');
        $this->appendTenantId($this->tenant_id, null, 'db-fail');
        $this->payload['db-fail'][null]['payload'] = [];
        $this->payload['db-fail'] = $this->payload['db-fail'][null];

    }

    public function get($property)
    {
        return $this->{$property};
    }

    public function appendTableDelete()
    {
        return $this->payload['tabledeleter'] = [
            "command" => "group_codes",
            "sub_command" => "tabledeleter",
            "tenant_id" => $this->appendTenantId($this->tenant_id, null, 'delta-success'),
            "payload" => [],
            "group_codes" => [
                "organization_id" => $this->organization_id,
                "import_detail_id" => $this->import_detail_id,
                "transition_date" => $this->options['transition_date'], # change this key after merge
                "tables" => [
                    "journal_mappings",
                    "treaty_group_mappings",
                    "treaty_group_codes",
                    "fac_group_mappings",
                    "fac_group_codes",
                    "policy_group_mappings",
                    "group_codes"
                ],
            ]
        ];
    }

}
