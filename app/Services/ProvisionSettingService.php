<?php

namespace App\Services;

use App\Models\{ProvisionSetting, ProvisionMapping, ReProvisionFacultativeMapping, ReProvisionTreatyMapping};
use App\Services\ProductService;
use App\Services\TreatyService;
use Illuminate\Support\Facades\Storage;
use App\Helpers\CustomHelper;
use Exception;

class ProvisionSettingService
{

    protected $model;
    public function __construct()
    {
        $this->model = new ProvisionSetting();
    }

    public function create($request)
    {
        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();
        $request->merge(['status_id' => CustomHelper::fetchStatus('not-started', 'default'), 'organization_id' => $organization_id]);
        return $this->model->create($request->all());
    }

    public function update($data, $id)
    {
        $currency = $this->fetch($id);
        return $currency->fill($data->all())->save();
    }

    public function delete($id)
    {
        $currency = $this->fetch($id);
        return $currency->delete();
    }

    public function fetch($id)
    {
        return $this->model->with(['reProvisionTreatyMappings', 'reProvisionFacultativeMappings', 'mappings', 'ExpenseAllocations'])->findOrFail(CustomHelper::decode($id));
    }

    public function compareCount($provisionSettingid)
    {
        $productService = new ProductService();
        $treatyService = new TreatyService();

        $managementExpenseLevelId = CustomHelper::fetchManagementExpenseLevel();
        if ($managementExpenseLevelId == null) {
            return;
        }

        $glCodeCount = count(CustomHelper::fetchGlCodesWithChartOfAccount([$managementExpenseLevelId])['gl_codes']);
        $provisionSetting = $this->model->withCount(['reProvisionTreatyMappings', 'reProvisionFacultativeMappings', 'ExpenseAllocations', 'mappings'])->findOrFail(CustomHelper::decode($provisionSettingid));
        $treatyCount = $treatyService->countRecords();
        $productCount =  $productService->countRecords();
        //if the number of products is 0 then throw error, meaning the products have not been imported.
        if ($provisionSetting) {
            if ($treatyCount === 0)
                throw new \Exception('Import Error: No Product Treaties found.');
            elseif ($glCodeCount === 0)
                throw new \Exception('Import Error:No Gl Codes found.');
            elseif ($productCount === 0)
                throw new \Exception('Import Error: No Products Found.');
            else {
                return [
                    'reProductTreaty' =>  $provisionSetting->re_provision_treaty_mappings_count === $treatyCount,
                    'reProductFacultative' =>  $provisionSetting->re_provision_facultative_mappings_count === $productCount,
                    'ExpenseAllocation' =>  $provisionSetting->expense_allocations_count === $glCodeCount,
                    'ProductInsurance' => $provisionSetting->mappings_count === $productCount
                ];
            }
        }
    }

    public function fetchAll()
    {
        return $this->model->with('status')->get();
    }

    public function fetchAllWithRelations($relations = array())
    {
        return $this->model->with($relations)->get();
    }

    public function fetchJsTable($provisionSetting)
    {
        $data = $this->model->with(['organization' => function ($query) {
            $query->with(['activeDiscountRates', 'activeIbnrAssumptions', 'activeRiskAdjustments', 'products' => function ($q) {
                $q->with(['systemDepartments.portfolios', 'systemDepartments.provisionMappings']);
            }]);
        }, 'mappings' => function ($query) {
            $query->orderBy('product_code_id', 'ASC');
        }])->findOrFail(CustomHelper::decode($provisionSetting));
        $groupProducts = $data->organization->products;
        $product = array();
        foreach ($groupProducts as $key => $value) {
            $mapping = $data->mappings->firstWhere('product_code_id', $value->id);

            $product[$key]['product_code'] = $value->code;
            $product[$key]['description'] = $value->description;
            $product[$key]['system_department_name'] = $value->systemDepartments->description;
            $product[$key]['discount_rates_id'] = $mapping?->discount_rates_id ?? '';
            $product[$key]['ibnr_assumptions_id'] = $mapping?->ibnr_assumptions_id ?? '';
            $product[$key]['salvage'] = $mapping?->ibnr_assumptions_id ??'';
            $product[$key]['ulae'] = $mapping?->ulae ?? '';
            $product[$key]['enid'] = $mapping?->enid ?? '';
            $product[$key]['risk_adjustments_id'] = $mapping?->risk_adjustments_id  ?? '';
            $product[$key]['claim_patterns_id'] = $mapping?->claim_patterns_id  ?? '';
            $product[$key]['expense_allocation'] = $mapping?->expense_allocation ?? '';
            $product[$key]['earning_pattern'] = $mapping?->earning_pattern ?? '';
            $product[$key]['ulr'] = $mapping?->ulr ?? '';
            $product[$key]['system_department_id'] = $value->systemDepartments->id;
            $product[$key]['provisionMapping_id'] = (isset($data->mappings[$key])) ? $data->mappings[$key]->id : '';
            $product[$key]['product_code_id'] = $value->id;
        }

        $data = [
            'discount_rates'   => $data->organization->activeDiscountRates,
            'ibnrAssumptions'  => $data->organization->activeIbnrAssumptions,
            'riskAdjustments'  => $data->organization->activeRiskAdjustments,
            'claimPatterns'    => $data->organization->activeClaimPatterns,
            'products'         => $product
        ];
        return $data;
    }

    public function fetchJsReTreatyTable($provisionSetting)
    {
        $data = $this->model->with(['organization' => function ($query) {
            $query->with(['activeDiscountRates', 'activeIbnrAssumptions', 'activeRiskAdjustments', 'activeClaimPatterns', 're_products_treaties' => function ($q) {
                $q->with(['systemDepartments.portfolios', 'systemDepartments.reProvisionTreatyMappings']);
            }]);
        }, 'reProvisionTreatyMappings'  => function ($query) {
            $query->orderBy('re_products_treaty_id', 'ASC');
        }])->findOrFail(CustomHelper::decode($provisionSetting));
        $groupProducts = $data->organization->re_products_treaties;
        $product = array();
        foreach ($groupProducts as $key => $value) {           
            $mapping = $data->reProvisionTreatyMappings->firstWhere('re_products_treaty_id', $value->id);

            $product[$key]['product_code'] = $value->treaty_pool;
            $product[$key]['description'] = $value->description;
            $product[$key]['system_department_name'] = $value->systemDepartments->description;
            $product[$key]['discount_rates_id'] = $mapping?->discount_rates_id ?? '';
            $product[$key]['ibnr_assumptions_id'] = $mapping?->ibnr_assumptions_id ?? '';
            $product[$key]['risk_adjustments_id'] = $mapping?->risk_adjustments_id ?? '';
            $product[$key]['claim_patterns_id'] = $mapping?->claim_patterns_id ?? '';
            $product[$key]['expense_allocation'] = $mapping?->expense_allocation ?? '';
            $product[$key]['earning_pattern'] = $mapping?->earning_pattern ?? '';
            $product[$key]['re_recovery_ratio'] = $mapping?->re_recovery_ratio ?? '';
            $product[$key]['system_department_id'] = $value->systemDepartments->id;
            $product[$key]['provisionMapping_id'] = $mapping?->id ?? '';
            $product[$key]['product_code_id'] = $value->id;
        }

        $data = [
            'discount_rates'   => $data->organization->activeDiscountRates,
            'ibnrAssumptions'  => $data->organization->activeIbnrAssumptions,
            'riskAdjustments'  => $data->organization->activeRiskAdjustments,
            'claimPatterns'    => $data->organization->activeClaimPatterns,
            'products'         => $product
        ];
        return $data;
    }

    public function fetchJsReFacultativeTable($provisionSetting)
    {
        $data = $this->model->with(['organization' => function ($query) {
            $query->with(['activeDiscountRates', 'activeIbnrAssumptions', 'activeRiskAdjustments', 'activeClaimPatterns', 'products' => function ($q) {
                $q->with(['systemDepartments.portfolios', 'systemDepartments.reProvisionFacultativeMappings']);
            }]);
        }, 'reProvisionFacultativeMappings' => function ($query) {
            $query->orderBy('product_code_id', 'ASC');
        }])->findOrFail(CustomHelper::decode($provisionSetting));
        $groupProducts = $data->organization->products;
        $product = array();
        foreach ($groupProducts as $key => $value) {
            $mapping = $data->reProvisionFacultativeMappings->firstWhere('product_code_id', $value->id);
        
            $product[$key]['product_code'] = $value->code;
            $product[$key]['description'] = $value->description;
            $product[$key]['system_department_name'] = $value->systemDepartments->description;
            $product[$key]['discount_rates_id'] = $mapping?->discount_rates_id ?? '';
            $product[$key]['ibnr_assumptions_id'] = $mapping?->ibnr_assumptions_id ?? '';
            $product[$key]['risk_adjustments_id'] = $mapping?->risk_adjustments_id ?? '';
            $product[$key]['claim_patterns_id'] = $mapping?->claim_patterns_id ?? '';
            $product[$key]['expense_allocation'] = $mapping?->expense_allocation ?? '';
            $product[$key]['earning_pattern'] = $mapping?->earning_pattern ?? '';
            $product[$key]['re_recovery_ratio'] = $mapping?->re_recovery_ratio ?? '';
            $product[$key]['system_department_id'] = $value->systemDepartments->id;
            $product[$key]['provisionMapping_id'] = $mapping?->id ?? '';
            $product[$key]['product_code_id'] = $value->id;      
        }     

        $data = [
            'discount_rates'   => $data->organization->activeDiscountRates,
            'ibnrAssumptions'  => $data->organization->activeIbnrAssumptions,
            'riskAdjustments'  => $data->organization->activeRiskAdjustments,
            'claimPatterns'    => $data->organization->activeClaimPatterns,
            'products'         => $product
        ];

        return $data;
    }
    public function createMapping($request, $provisionSetting)
    {
        $organizationService = new OrganizationService;
        $this->verifyProvisionSetting($provisionSetting);
        $organization_id = $organizationService->getAuthOrganizationId();
        $data = json_decode($request->input('data'), true);
        foreach ($data as $group) {
            $model = ProvisionMapping::updateOrCreate(['id' => !empty($group['provisionMapping_id']) ? $group['provisionMapping_id'] : null], [
                'system_department_id'  => $group['system_department_id'],
                'provision_setting_id'  => $provisionSetting,
                'organization_id'       => $organization_id,
                'product_code_id'       => $group['product_code_id'],
                'risk_adjustments_id'   => $group['risk_adjustments_id'],
                'ibnr_assumptions_id'   => $group['ibnr_assumptions_id'],
                'claim_patterns_id'     => $group['claim_patterns_id'],
                'discount_rates_id'     => $group['discount_rates_id'],
                'expense_allocation'    => $group['expense_allocation'],
                'earning_pattern'       => $group['earning_pattern'],
                'ulr'                   => $group['ulr'],
                'ulae'                  => $group['ulae'],
                'enid'                  => $group['enid'],

            ]);
        }

        return $model;
    }

    public function createTreatyMapping($request, $provisionSetting)
    {
        $organizationService = new OrganizationService;
        $this->verifyProvisionSetting($provisionSetting);
        $organization_id = $organizationService->getAuthOrganizationId();
        $data = json_decode($request->input('data'), true);
        foreach ($data as $group) {
            $model = ReProvisionTreatyMapping::updateOrCreate(['id' => !empty($group['provisionMapping_id']) ? $group['provisionMapping_id'] : null], [
                'system_department_id'  => $group['system_department_id'],
                'provision_setting_id'  => $provisionSetting,
                'organization_id'       => $organization_id,
                're_products_treaty_id' => $group['product_code_id'],
                'risk_adjustments_id'   => $group['risk_adjustments_id'],
                'ibnr_assumptions_id'   => $group['ibnr_assumptions_id'],
                'claim_patterns_id'     => $group['claim_patterns_id'],
                'discount_rates_id'     => $group['discount_rates_id'],
                'expense_allocation'    => $group['expense_allocation'],
                'earning_pattern'       => $group['earning_pattern'],
                're_recovery_ratio'     => $group['re_recovery_ratio']
            ]);
        }

        return $model;
    }

    public function createFacultativeMapping($request, $provisionSetting)
    {
        $organizationService = new OrganizationService;
        $this->verifyProvisionSetting($provisionSetting);
        $organization_id = $organizationService->getAuthOrganizationId();
        $data = json_decode($request->input('data'), true);
        // dd($data);
        foreach ($data as $group) {
        // if($group['product_code_id'] == '172')
        //         dd($group['discount_rates_id']);
            $model = ReProvisionFacultativeMapping::updateOrCreate(['id' => !empty($group['provisionMapping_id']) ?  $group['provisionMapping_id'] : null], [
                'system_department_id'  => $group['system_department_id'],
                'provision_setting_id'  => $provisionSetting,
                'organization_id'       => $organization_id,
                'product_code_id'       => $group['product_code_id'],
                'risk_adjustments_id'   => $group['risk_adjustments_id'],
                'ibnr_assumptions_id'   => $group['ibnr_assumptions_id'],
                'claim_patterns_id'     => $group['claim_patterns_id'],
                'discount_rates_id'     => $group['discount_rates_id'],
                'expense_allocation'    => $group['expense_allocation'],
                'earning_pattern'       => $group['earning_pattern'],
                're_recovery_ratio'     => $group['re_recovery_ratio']
            ]);
            // if($model->product_code_id == 172)
            //     dd($model->discount_rates_id , $model->ibnr_assumptions_id);
                // dd($model->ibnr_assumptions_id);
        }

        return $model;
    }

    public function initCreate($organization_id)
    {
        $data                    = array();
        $defaultFileName         = 'rule.json';
        $data['organization_id'] = $organization_id;
        foreach (config('constant.s3_paths.provision_rules') as $key => $report) {
            $fileName            = 'rule.json';
            $data['identifier']  = $key;
            $adminStoragePath    = CustomHelper::fetchAdminStorage('provision_rules.' . $key);
            $organizationStorage = CustomHelper::fetchOrganizationStorage($organization_id, 'provision_rules.' . $key);
            $data['file_url']    = $organizationStorage . $fileName;
            $fileCopied          = Storage::disk('s3')->copy($adminStoragePath . $defaultFileName, $data['file_url']);
            if ($fileCopied) {
                $generalConfigurationService = new GeneralConfigurationService();
                $generalConfigurationService->create($data);
            }
        }
    }

    public function fetchStatus()
    {
        $status_started_id = CustomHelper::fetchStatus('started', 'default');

        $active_provision_count = $this->verifyActiveStatus($status_started_id);
        if ($active_provision_count)
            return CustomHelper::fetchStatus('not-started', 'default');
        else
            return $status_started_id;
    }

    public function verifyActiveStatus($status_started_id)
    {
        return $this->model->withWhereHas('status', function ($q) use ($status_started_id) {
            $q->where('id', $status_started_id);
        })->count();
    }

    public function verifyProvisionSetting($provision_setting){
        if(CustomHelper::isActiveOrExpired($provision_setting, $this->model)){
            throw new Exception('Disable the Provision Setting to edit the mapping.');
        }
    }


    public function fetchActiveProvisionSetting()
    {
        $status_id = CustomHelper::fetchStatus('started');
        return $this->model->where('status_id', $status_id)->latest()->first();
    }

    public function fetchCurrentProvision($provision_setting)
    {
        $provisionSetting = $this->fetch($provision_setting);
        $relations = ['reProvisionTreatyMappings', 'reProvisionFacultativeMappings', 'mappings', 'ExpenseAllocations'];
        foreach ($relations as $relation) {
            if ($provisionSetting->{$relation}()->exists()) {
                throw new \Exception('Provision Setting cannot be deleted because it has linked mappings.');
            }
        }
    }
}
