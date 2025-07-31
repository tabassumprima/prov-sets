<?php

namespace App\Services;

use App\Helpers\CustomHelper;
use App\Services\CriteriaService;
use App\Models\Portfolio;
use App\Models\PortfolioSystemDepartment;
use Carbon\Carbon;

class PortfolioService
{
    protected $model;
    private $types;

    public function __construct()
    {
        $this->model = new Portfolio();
        $this->types = ['insurance', 're-insurance'];
    }

    public function create($request)
    {
        if (!in_array($request->type, $this->types))
            throw new \Exception('Specified type does not exists');

        $organizationService = new OrganizationService();
        $organization_id = $organizationService->getAuthOrganizationId();
        $request->merge(['organization_id' => $organization_id]);
        return $this->model->create($request->toArray());
    }

    public function saveMapping($request)
    {
        $this->verifyCriteriaStatus($request->criteria);
        $this->detachDeparts($request->criteria); //First detach all the departments from the portfolio
        foreach ($request->data as $portfolio) {
            $model = $this->model->find($portfolio['portfolio_id']);
            //then attach the departments to the portfolio
            $model->systemDepartments()->attach($portfolio['system_department_id'], ['criteria_id' => $request->criteria]);
        }
        return $model;
    }

    public function update($request, $id)
    {
        $portfolio = $this->fetch(CustomHelper::decode($id));
        $portfolio->fill($request->all())->save();
        return $portfolio;
    }

    public function delete($id)
    {
        $portfolio = $this->fetch($id);
        return $portfolio->delete();
    }

    public function fetch($id)
    {
        return $this->model->findOrFail($id);
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function getId($shortcode)
    {
        $query = $this->model->where('shortcode', $shortcode)->first();
        return ($query->id) ?? null;
    }

    public function fetchIdByName($portfolio)
    {
        $data = $this->model->select(['id'])->where(['name' => $portfolio['name']])->first();
        return $data->id ?? '';
    }

    public function fetchColumns(array $columns, $criteria_type)
    {
        return $this->model->select($columns)->where('type', $criteria_type)->get();
    }

    public function uniqueCheck($portfolio)
    {
        $data = $this->model->select(['name', 'shortcode'])->where('name', $portfolio['name'])->orWhere('shortcode', $portfolio['shortcode'])->first();
        if ($data != null) {
            if (optional($data)->name != $portfolio['name'] || optional($data)->shortcode != $portfolio['shortcode'])
                throw new \Exception('Shortcode and name must have same value');
        }
    }

    public function fetchSystemDepartmentId($data, $name)
    {
        $ids = [];
        foreach ($data as $portfolio) {
            if ($portfolio['name'] == $name) {
                array_push($ids, $portfolio['system_department_id']);
            }
        }
        return $ids;
    }

    //Detach Deaprtments from Portfolio
    public function detachDeparts($criteria_id)
    {
        $pivot = $this->model->withWhereHas('systemDepartments', function ($q) use ($criteria_id) {
            $q->where('criteria_id', $criteria_id);
        })->get();

        foreach ($pivot as $portfolio) {
            $portfolio->systemDepartments()->wherePivot('criteria_id', $criteria_id)->detach();
        }
    }

    public function verifyCriteriaStatus($criteria_id)
    {
        $criteriaService = new CriteriaService();
        $criteriaStatus = $criteriaService->isActiveOrExpired($criteria_id);
        if ($criteriaStatus) {
            throw new \Exception('Criteria is active or expired. You cannot edit portfolios.');
        }
    }

    public function fetchPortfoliosByDate($request)
    {
        $date = Carbon::parse($request->voucher_date)->toDateTimeString();
        $type = $request->type;
        return $this->model->select('id','name')->whereHas('portfolioSystemDepartments', function($query) use ($date, $request, $type) {
            $query->whereHas('criteria', function($query) use ($date, $type) {
                $query->where(function($query) use ($date){
                    $query->where('criterias.start_date', '<=', $date);
                    $query->where('criterias.end_date', '>=', $date);
                })->orWhere(function($query) use ($date){
                    $query->where('criterias.start_date', '<=', $date);
                    $query->where('criterias.end_date', null);
                });
        })->when($type == 'insurance', function($query) use ($type) {
            $query->where('type', $type);
        }, function ($query) use ($type) {
            $query->where('type', '!=' , 'insurance');
        })
        ->where('system_department_id', $request->system_department_id);
    })->get();
    }
}
