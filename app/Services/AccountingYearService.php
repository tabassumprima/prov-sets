<?php

namespace App\Services;

use App\Services\SettingService;
use App\Models\AccountingYear;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccountingYearService
{
    protected $model, $accountingYear;
    public function __construct()
    {
        $this->model = new AccountingYear();
    }

    public function fetchAll()
    {
        return $this->model->all();
    }

    public function fetch($id)
    {
        $this->model = $this->model->findOrFail($id);
        return $this->model;
    }

    public function fetchAllWithColumns($columns)
    {
        return $this->model->select($columns)->get();
    }

    public function getId($year)
    {
        return $this->model->where('year', $year)->first()->id;
    }

    public function getYearRange($id)
    {
        $this->fetch($id);
        $start_date = Carbon::parse($this->model->start_date)->toDateString();
        $end_date = Carbon::parse($this->model->end_date)->toDateString();
        return Str::replaceArray('?', [$start_date, $end_date], "? to ?");
    }

    public function get($property, $id = null)
    {
        return $this->model ? $this->model->{$property} : $this->fetch($id)->{$property};
    }

    public function fetchByTransitionDate()
    {
        $settingService  = new SettingService();
        $transitionDate  = $settingService->getOption('transition_date');
        if ($transitionDate) {
            $date = Carbon::createFromFormat('Y-m-d', $transitionDate);
            $formattedDate = $date->format('Y-m-d');
            $data = $this->model->whereDate('start_date', '>=', $formattedDate)
                                ->orderBy('start_date', 'desc')
                                ->get();
        } else {
            $data = $this->fetchAll()->sortByDesc('start_date');
        }
        return $data;
    }

    public function getIdByStartDate($startDate)
    {
        $accountingYear = $this->model->whereDate('start_date', $startDate)->first();

        return $accountingYear ? $accountingYear->id : null;
    }

    public function getIdByEndDate($endDate)
    {
        $accountingYear = $this->model->whereDate('end_date', $endDate)->first();
        return $accountingYear ? $accountingYear->id : null;
    }

    public function getNextAccountingYear($end_date)
    {
        return $this->model->whereDate('end_date', '>' ,$end_date)->orderBy('end_date', 'asc')->first();

    }

}
