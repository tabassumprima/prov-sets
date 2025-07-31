<?php

namespace App\Services;

use App\Models\ProductCode;
use App\Services\{CohortService, MeasurementModelService};

class FacultativeService
{
    protected $model;
    public function __construct()
    {
        $this->model = new ProductCode();
    }

    public function fetchAllWithRelatedData($group)
    {
        $cohortsService = new CohortService();
        $cohorts = $cohortsService->fetchColumns(['id', 'name']);

        $measurementModelService = new MeasurementModelService();
        $measurementModels = $measurementModelService->fetchColumns(['id', 'shortcode as name']);

        $portfoliosService = new PortfolioService();
        $portfolios = $portfoliosService->fetchColumns(['id', 'shortcode as name'], $group->applicable_to);

        $data = $this->model->with([
            'organizations',
            'systemDepartments.portfolios' => function ($q) use ($group) {
                $q->wherePivot('criteria_id', $group->criteria_id);
            },
            'businessTypes',
            'groupFacultative' => function ($query) use ($group) {
                $query->where('group_id', $group->id);
            }
        ])->get();

        $is_portfolio_exists = $data->first()->groupFacultative->first()?->portfolio_id;

        $product = array();
        foreach ($data as $key => $value) {
            $product[$key]['code'] = $value->code;
            $product[$key]['description'] = $value->description;
            $product[$key]['business_type_name'] = $value->businessTypes->type;
            $product[$key]['measurement_model_id'] = ($value->groupFacultative->first()->measurement_model_id) ?? 1;
            $product[$key]['cohorts_code_id'] = ($value->groupFacultative->first()->cohorts_code_id) ?? 1;
            $product[$key]['product_grouping'] = ($value->groupFacultative->first()->product_grouping) ?? '01';
            $product[$key]['onerous_threshold'] = ($value->groupFacultative->first()->onerous_threshold) ?? 0.00;
            $product[$key]['system_department_name'] = $value->systemDepartments->description;
            $product[$key]['portfolio_id'] = $is_portfolio_exists ? $value->groupFacultative->first()->portfolio_id : optional($value->systemDepartments->portfolios->first())->id;
            $product[$key]['product_key'] = $value->id;
            $product[$key]['groupFacultative_id'] = optional($value->groupFacultative->first())->id;
        }
        $data = array(
            'product' => $product,
            'portfolios' => $portfolios,
            'cohorts' => $cohorts,
            'measurement' => $measurementModels,
            'grouping' => config('constant.default_prodcut_grouping'),
        );
        return $data;
    }
}
