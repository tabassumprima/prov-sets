<?php

namespace App\Services;

use App\Models\SystemDepartment;

class SystemDepartmentService
{
    protected $model;

    public function __construct()
    {
        $this->model = new SystemDepartment();
    }

    public function create($request)
    {
        return $this->model->create($request->all());
    }

    public function update($request, $id)
    {
        $systemDepartment = $this->fetch($id);
        return $systemDepartment->fill($request->all())->save();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchAll()
    {
        $organizationService = new OrganizationService;
        $organization_id = $organizationService->getAuthOrganizationId();
        return $this->model->where('organization_id', $organization_id)->get();
    }

    public function fetchColumns($columns, $id)
    {
        return $this->model->select($columns)->where('id', $id)->first();
    }

    public function fetchSystemDepartments($criteria_id)
    {
        $criteriaService = new CriteriaService();
        $criteria        = $criteriaService->fetch($criteria_id);

        $data = $this->model->with(['portfolios' => function ($query) use ($criteria_id, $criteria) {
            $query->where(['type' => $criteria->applicable_to])->wherePivot('criteria_id', $criteria_id);
        }])->get();

        $systemDepartments = [];
        foreach ($data as $systemDepartment) {
            array_push($systemDepartments, [
                'description'          => $systemDepartment->description,
                'portfolio_id'         => optional($systemDepartment->portfolios->where('type', $criteria->applicable_to)->first())->id,
                'system_department_id' => $systemDepartment->id,
                'id'                   => optional($systemDepartment->portfolios->where('type', $criteria->applicable_to)->first())->id,
            ]);
        }
        return $systemDepartments;
    }

public function fetchSystemDepartmentsByPortfolio($portfolio_id, $columns = ['*'], $exclude_portfolio = false)
    {
        $data = $this->model->select($columns)->withWhereHas('portfolios', function ($query) use ($portfolio_id, $exclude_portfolio) {
            $query->when($exclude_portfolio, function ($query) use ($portfolio_id) {
                $query->where('portfolio_id', '!=', $portfolio_id);
            }, function($query) use ($portfolio_id) {
                $query->where('portfolio_id', $portfolio_id);
            });
        })->get();
        return $data;

    }

    public function getId($systemDepartment)
    {
        return ($this->model->where('code', $systemDepartment)->first()->id) ?? 1;
    }
}
