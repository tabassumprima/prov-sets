<?php

namespace App\Services;

use App\Models\ExpenseAllocation;
use App\Helpers\CustomHelper;
use App\Services\OrganizationService;

class ExpenseAllocationService
{

    protected $model;

    public function __construct()
    {
        $this->model = new ExpenseAllocation();
    }

    public function fetchData($provisionSettingId)
    {
        $managementExpenseLevelId = CustomHelper::fetchManagementExpenseLevel();
        if ($managementExpenseLevelId === null) {
            return view('user.expense_allocation.create', ['codeDescList' => []]);
        }

        $glCodesids = CustomHelper::fetchGlCodesWithChartOfAccount([$managementExpenseLevelId])['gl_codes']->toArray();
        $expenseTypes = config('constant.expense_types');
        $allocationBasis = config('constant.allocation_basis');
        $GlCodeService = new GlCodeService();
        $glCodes = $GlCodeService->fetchGlCodesDetails($glCodesids, $provisionSettingId);

        $product = $glCodes->mapWithKeys(function ($item, $key) use ($allocationBasis, $expenseTypes) {
            return [$key => [
                "gl_code_id"        => $item->id,
                "code"              => $item->code,
                "description"       => $item->description,
                "expense_type"      => isset($item->expenseAllocation) ? $item->expenseAllocation->expense_type : head($expenseTypes),
                "allocation_basis"  => isset($item->expenseAllocation) ? $item->expenseAllocation->allocation_basis : head($allocationBasis),
                "allocation_rate"   => isset($item->expenseAllocation) ? $item->expenseAllocation->allocation_rate : 0,
                "expense_allocation_id" => isset($item->expenseAllocation) ? $item->expenseAllocation->id : '',
            ]];
        });
        $data = [
            'codeDescList' => $product,
            'expense_types' => array_values($expenseTypes),
            'allocation_basis' => array_values($allocationBasis),
            'allocation_rate' => 0.00
        ];
        return $data;
    }

    public function create($request, $provisionSetting)
    {
        $organizationService = new OrganizationService;
        $provisionSettingService = new ProvisionSettingService();
        $provisionSettingService->verifyProvisionSetting($provisionSetting);
        $organization_id = $organizationService->getAuthOrganizationId();
        $data = json_decode($request->input('data'), true);
        $results = [];
        foreach ($data as $group) {
            $model = ExpenseAllocation::updateOrCreate(
                [
                    'id' => !empty($group['expense_allocation_id']) ? $group['expense_allocation_id'] : null,
                ],
                [
                    'organization_id' => $organization_id,
                    'gl_code_id' => $group['gl_code_id'],
                    'expense_type' => $group['expense_type'],
                    'allocation_basis' => $group['allocation_basis'],
                    'allocation_rate' => $group['allocation_rate'],
                    'provision_setting_id'  => $provisionSetting,
                ]
            );
            $results[] = $model;
        }
        return response()->json($results);
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

    public function deleteExpenseAllocation($organization_id)
    {
        $this->model->where('organization_id', $organization_id)->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail(CustomHelper::decode($id));
    }

    public function fetchAll()
    {
        return $this->model->all();
    }
}
