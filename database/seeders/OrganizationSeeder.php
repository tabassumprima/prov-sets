<?php

namespace Database\Seeders;

use App\Services\OrganizationAccessTokenService;
use App\Services\ChartOfAccountService;
use App\Services\OrganizationService;
use Exception;
use Illuminate\Database\Seeder;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;


class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = [
            [
                'name'                  => 'Organization 1',
                'shortcode'             => 'org',
                'sales_tax_number'      => '123456789',
                'ntn_number'            => '123456789',
                'date_format'           => 'd M, Y',
                'financial_year'        => 'January - December',
                'type'                  => 'Life',
                'country_id'            => 1,
                'currency_id'           => 1,
                'address'               => 'Address 1',
                'database_config_id'    => 1,
                'agent_config'          => null,
                'tenant_id'             => '1234554523423424'
            ],
            [
                'name'                  => 'Organization 2',
                'shortcode'             => 'org2',
                'sales_tax_number'      => '123456789',
                'ntn_number'            => '123456789',
                'date_format'           => 'd M, Y',
                'financial_year'        => 'January - December',
                'type'                  => 'Life',
                'country_id'            => 1,
                'currency_id'           => 1,
                'address'               => 'Address 2',
                'database_config_id'    => 1,
                'agent_config'          => null,
                'tenant_id'             => '123423442523423421'
            ],
            [
                'name'                  => 'Organization 3',
                'shortcode'             => 'org4',
                'sales_tax_number'      => '123456789',
                'ntn_number'            => '123456789',
                'date_format'           => 'd M, Y',
                'financial_year'        => 'January - December',
                'type'                  => 'Life',
                'country_id'            => 2,
                'currency_id'           => 2,
                'address'               => 'Address 3',
                'database_config_id'    => 1,
                'agent_config'          => null,
                'tenant_id'             => '12312132332234249'
            ],
            [
                'name'                  => 'Prima Consulting',
                'shortcode'             => 'PRM',
                'sales_tax_number'      => '123456789',
                'ntn_number'            => '123456789',
                'date_format'           => 'd M, Y',
                'financial_year'        => 'January - December',
                'type'                  => 'Life',
                'country_id'            => 3,
                'currency_id'           => 3,
                'address'               => 'Address 3',
                'database_config_id'    => 1,
                'agent_config'          => null,
                'tenant_id'             => '12323387654352234240'
            ]
        ];

        $organizationService = new OrganizationService();
        foreach ($organizations as $organization) {
            $organization_id       = Organization::create($organization);
            try{
                $organizationService->insertDynamoDb($organization['tenant_id'], $organization['shortcode']);
            }catch(Exception $e){
                Log::error("unable to insert to dynamodb");
                Log::error($e->getMessage());
            }

            $chartOfAccountService = new ChartOfAccountService;
            $chartOfAccountService->initCreate($organization_id->id);
        }
    }
}
