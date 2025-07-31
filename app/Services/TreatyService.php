<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Services\{CohortService, MeasurementModelService};
use Illuminate\Support\Facades\Auth;
use App\Models\ReProductsTreaty;

class TreatyService
{
    protected $model;
    public function __construct()
    {
        $this->model = new ReProductsTreaty();
    }

    public function create($request)
    {
        $organization = Auth::user()->organization;
        return $organization->productInformations()->create($request->all());
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
        return $this->model->get();
    }

    public function countRecords()
    {
        return $this->model->count();
    }

    public function fetchAllWithRelatedData($group)
    {
        $cohortsService = new CohortService();
        $cohorts = $cohortsService->fetchColumns(['id', 'name']);

        $measurementModelService = new MeasurementModelService();
        $measurementModels = $measurementModelService->fetchColumns(['id', 'shortcode as name']);

        $portfoliosService = new PortfolioService();
        $portfolios = $portfoliosService->fetchColumns(['id', 'shortcode as name'], $group->applicable_to);

        $data = $this->model->with(['organizations', 'systemDepartments.portfolios' => function ($q) use ($group) {
            $q->wherePivot('criteria_id', $group->criteria_id);
        }, 'businessTypes', 'groupTreaties' => function ($query) use ($group) {
            $query->where('group_id', $group->id);
        }])->get();

        $is_portfolio_exists = $data->first()->groupTreaties->first()?->portfolio_id;

        $product = array();
        foreach ($data as $key => $value) {
            $product[$key]['treaty_pool'] = $value->treaty_pool;
            $product[$key]['description'] = $value->description;
            $product[$key]['business_type_name'] = $value->businessTypes->type;
            $product[$key]['measurement_model_id'] = ($value->groupTreaties->first()->measurement_model_id) ?? 1;
            $product[$key]['cohorts_code_id'] = ($value->groupTreaties->first()->cohorts_code_id) ?? 1;
            $product[$key]['product_grouping'] = ($value->groupTreaties->first()->product_grouping) ?? '01';
            $product[$key]['onerous_threshold'] = ($value->groupTreaties->first()->onerous_threshold) ?? 0.00;
            $product[$key]['system_department_name'] = $value->systemDepartments->description;
            $product[$key]['portfolio_id'] = $is_portfolio_exists ? $value->groupTreaties->first()->portfolio_id : optional($value->systemDepartments->portfolios->first())->id;
            $product[$key]['re_product_key'] = $value->id;
            $product[$key]['groupTreaty_id'] = optional($value->groupTreaties->first())->id;
        }
        $data = array(
            'product' => $product,
            'portfolios'    => $portfolios,
            'cohorts' => $cohorts,
            'measurement' => $measurementModels,
            'grouping' => config('constant.default_prodcut_grouping'),
        );
        return $data;
    }
}
