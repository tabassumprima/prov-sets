<?php

namespace App\Services;

use App\Models\Setting;
use App\Services\ExpenseAllocationService;
use App\Helpers\CustomHelper;

class SettingService {

        protected $model;

        public function __construct()
        {
            $this->model = new Setting();
        }
        public function create($request)
        {
            $existingSettings = $this->model->where('organization_id', $request->organization_id)->first();
            $existingOptions = $existingSettings ? $existingSettings->options->toArray() : [];
            $newOptions = $request->input('options', []);
            $ExpenseAllocationService = new ExpenseAllocationService();
            $currentManagementExpenseLevelId = $existingOptions['management_expense_level_id'] ?? null;
            $newManagementExpenseLevelId = $newOptions['management_expense_level_id'] ?? null;
            if ($currentManagementExpenseLevelId !== $newManagementExpenseLevelId) {
                $ExpenseAllocationService->deleteExpenseAllocation($request->organization_id);
            }
            foreach ($newOptions as $key => $value) {
                if (!is_null($value) && $value !== 0) {
                    $existingOptions[$key] = $value;
                }
            }
            return $this->model->updateOrCreate(
                ['organization_id' => $request->organization_id],
                ['options' => $existingOptions]
            );
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
            return $this->model->findOrFail(CustomHelper::decode($id));
        }


        public function fetchAll()
        {
            return $this->model->all();
        }

        public function getOption($option)
        {
            $setting = $this->model->first()?->options;
            return $setting ? $setting[$option] : null;
        }

        public function getOptions()
        {
            return $this->model->first()->options;
        }

        public function fetchOrganizationSetting()
        {
            return $this->model->first();
        }
        public function fetchByOrganizationId($organization_id)
        {
            return $this->model->where('organization_id', $organization_id)->first();
        }

        public function clearOption($option, $organization_id)
    {
        $setting = $this->model->where('organization_id', $organization_id)->first();

        if ($setting) {
            $options = $setting->options;

            if (isset($options[$option])) {
                unset($options[$option]);
                $setting->options = $options;
                return $setting->save();
            }
        }

        return false;
    }

}
