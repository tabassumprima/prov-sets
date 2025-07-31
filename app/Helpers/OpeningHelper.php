<?php

    namespace App\Helpers;
    use App\Services\EntryTypeService;
    use App\Services\OrganizationService;
    use App\Services\ProvisionSettingService;
    use App\Services\SettingService;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Str;

    class OpeningHelper
    {
        private $payload = [];
        private $bucket_name;
        private $valuation_date, $import_detail_id, $organization_id, $organizatonService, $provisionSettingService, $entryTypeService, $config, $settingService, $options, $tenant_id;


        public function __construct($valuationData,$import_detail_id, $tenant_id, $organization_id = null)
        {
            $this->organizatonService = new OrganizationService();
            $this->provisionSettingService = new ProvisionSettingService();
            $this->entryTypeService = new EntryTypeService();
            $this->settingService = new SettingService;
            $this->bucket_name = config('constant.aws_bucket');

            $this->settingService       =    new SettingService;
            $this->import_detail_id     =    $import_detail_id;
            $this->options              =    $this->settingService->getOptions();
            $this->organization_id      =    $organization_id ?? $this->organization_id;
            $this->tenant_id            =    $tenant_id;
            $this->valuation_date       =     $valuationData;
        }

        public function invokeOpening()
        {
            $this->payload['sub_command'] = $this->options['opening_balance_lambda_id'];
            $this->appendProvision();
            $this->appendTenantId($this->tenant_id);
            $this->appendCommand('provision');
            $this->payload['payload'] = [];

            // $this->appendGroupCodes();
            return $this->payload;
        }

        public function appendProvision()
        {
            return $this->payload['provision'] = [
                'organization_id'               => $this->organization_id,
                'provision_setting_id'          => $this->provisionSettingService->fetchActiveProvisionSetting()->id,
                'valuation_date'                => $this->valuation_date,
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



        public function appendCommand($command)
        {
            return $this->payload['command'] = $command;
        }
        public function getPayload()
        {
            return $this->payload;
        }

        public function appendTenantId($tenant_id)
        {
            return $this->payload['tenant_id'] = $tenant_id;
        }

        // public function appendGroupCodes()
        // {
        //     $this->payload['group_codes'] = [
        //         "organization_id" => $this->organization_id,
        //         "import_detail_id" => $this->import_detail_id,
        //         "transition_date" => $this->options['transition_date'], // change this key after merge
        //         "fac_regex" => "\\d{4}[A-4,a-z,0,9]{5,7}[F]\\d{5}$",
        //         "treaty_regex" => "treaty_id%",
        //         "tables" => [
        //             "journal_mappings",
        //             "treaty_group_mappings",
        //             "treaty_group_codes",
        //             "fac_group_mappings",
        //             "fac_group_codes",
        //             "policy_group_mappings",
        //             "group_codes"
        //         ],
        //     ];

        //     $this->payload['command'] = "group_codes";
        //     $this->payload['sub_command'] = "opening_grouping";
        //     $this->payload['tenant_id'] = $this->appendTenantId($this->tenant_id, null, 'delta-success');
        //     $this->payload['payload'] =[];

        //     return $this->payload;
        // }
    }

?>
