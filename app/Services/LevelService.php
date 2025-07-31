<?php

namespace App\Services;

use App\Models\ChartOfAccount;
use App\Models\Level;
use Illuminate\Support\Facades\DB;
use App\Services\ChartOfAccountService;

class LevelService{

    protected $model;

    public function __construct()
    {
        $this->model = new Level();
    }

    public function create($organization_id, $level, $code, $category = null)
    {

        $level =  $this->model->create([
            'organization_id' => $organization_id,
            'level' => $level,
            'category' => $category,
            'code' => $code,
        ]);
        return $level->id;
    }

    public function updateOrCreate($organization_id, $level,$code=null)
    {
        $level = $this->model->updateOrCreate(['organization_id' => $organization_id, 'level' => $level,'code' => $code],
        ['organization_id' => $organization_id, 'level' => $level ,'code' => $code]);

        return $level->id;
    }

    public function fetchIdByLevel($organization_id, $level)
    {
        return $this->model->where(['level' => $level, 'organization_id'=> $organization_id])->first()?->id;
    }

    public function fetchAll()
    {
        return $this->model->all();
    }


    public function fetchById($id)
    {
        return $this->model->find($id);
    }

    public function deleteAllExceptInitLevel()
    {
        $levels = $this->model
            ->withCount('lambda_entries')
            ->whereDoesntHave('chart_of_accounts')
            ->get();

        foreach ($levels as $level) {
            if ($level->lambda_entries_count > 0)
                throw new \Exception("Cannot delete level '{$level->level}' because it is referenced in lambda entries. Please delete those entries first.");
        } 
        return $this->model->whereDoesntHave('chart_of_accounts')->delete();   
    }

    public function fetchLevelByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }


}
