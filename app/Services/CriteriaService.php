<?php

namespace App\Services;

use App\Models\Criteria;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CriteriaService
{

    protected $model;
    private $types;

    public function __construct()
    {
        $this->model = new Criteria();
        $this->types = ['insurance', 're-insurance'];
    }

    public function create($request)
    {
        if (!in_array($request->applicable_to, $this->types))
            throw new \Exception('Specified type does not exists');

        $organization = Auth::user()->organization;
        $this->updateLastCriteriaEndDate($request, true);
        $request->merge(['status_id' => $this->findStatus($request->start_date)]);
        return $organization->criterias()->create($request->all());
    }

    public function update($request, $id)
    {
        if (in_array($request->applicable_to, $this->types));
            throw new \Exception('Specified type does not exists');

        $criteria = $this->fetch($id);
        return $criteria->fill($request->all())->save();
    }

    public function delete($id)
    {
        $data           = array('success' => true, 'message' => null);
        $byPassAllcheck = auth()->user()->isImpersonated() && auth()->user()->organization->isBoarding;
        $criteria = $this->fetch($id);
        if ($byPassAllcheck || $criteria->status->slug != 'started' && $criteria->status->slug != 'expired') {
            $applicable_to  = $criteria->applicable_to;
            $latestCriteria = $this->fetchLatestCriteriaOfAuthUserOrganization($applicable_to);
            if ($latestCriteria->id == $id) {
                //Check if criteria has any group
                if ($criteria->group->isEmpty()) {
                    $criteria->delete();
                    $latestCriteria = $this->fetchLatestCriteriaOfAuthUserOrganization($applicable_to);
                    $this->updateLastCriteriaEndDate($latestCriteria);  
                } else {
                    $data['success'] = false;
                    $data['message'] = trans('user/criteria.delete_group_first');
                }
            } else {
                $data['success'] = false;
                $data['message'] = trans('user/criteria.delete_only_latest');
            }
        } else {
            $data['success'] = false;
            $data['message'] = trans('user/criteria.delete_active_one');
        }

        return $data;
    }

    public function fetch($id)
    {
        return $this->model->with('status', 'organization')->findOrFail($id);
    }

    public function fetchAll()
    {
        $organizationId = Auth::user()->organization->id;
        return $this->model->with('status')->where('organization_id', $organizationId)->get();
    }

    public function fetchActive()
    {
        return $this->model->active()->get();
    }

    public function isActiveOrExpired($criteria_id)
    {
        return $this->model->where('id', $criteria_id)->whereHas('status', function ($query) {
            $query->whereIn('slug', ['started', 'expired']);
        })->whereHas('organization', function ($query) {
            $query->where('isBoarding', false);
        })->exists();
    }

    public function fetchColumns(array $columns, $criteria_type)
    {
        return $this->model->select($columns)->where('applicable_to', $criteria_type)->get();
    }

    public function fetchUnexpiredCriteria($criteria_type)
    {
        return $this->model->where('applicable_to', $criteria_type)->whereHas('status', function ($query) {
            $query->where('slug', '!=', 'expired');
        })->get();
    }

    // Get latest criteria of authorize perons's organization
    public function fetchLatestCriteriaOfAuthUserOrganization($type)
    {
        return $this->model->where('organization_id', Auth::user()->organization->id)->applicableTo($type)->latest()->first();
    }

    // Verify request will check if the criteria is active else return original request
    public function updateLastCriteriaEndDate($data, $setEndDate = false)
    {
        $statusService = new StatusService();
        $latestCriteria = $this->fetchLatestCriteriaOfAuthUserOrganization(optional($data)->applicable_to);
        $latestCriteria_id = optional($latestCriteria)->id;
        //set last criteria end date to start date of new criteria
        if ($latestCriteria) {
            if ($setEndDate) {
                //set end date of latest criteria
                $latestCriteria->end_date = Carbon::parse($data->start_date)->subDay();
            } else {
                $latestCriteria->end_date = null;
               
            }
            $startedStatus = $this->findStatus($latestCriteria->start_date);
            $latestCriteria->status_id = $startedStatus;
            $latestCriteria->save();
            //set end date of latest group of this criteria           
            $groupService = new GroupService();
            $groupService->updateEndDateOfLatestGroupCriteria($latestCriteria_id);
        }
        return $latestCriteria;
    }

    public function availableFutureDate()
    {
        $data = array();
        $criteriaInsurance   = $this->fetchLatestCriteriaOfAuthUserOrganization('insurance');
        $criteriaReInsurance = $this->fetchLatestCriteriaOfAuthUserOrganization('re-insurance');
        $groupService = new GroupService();
        $groupInsurance = $groupService->fetchLatestGroupWithCriteria($criteriaInsurance?->id);
        $groupReInsurance = $groupService->fetchLatestGroupWithCriteria($criteriaReInsurance?->id);
        $data['insurance']   = $criteriaInsurance ? Carbon::parse($criteriaInsurance->start_date)->addDay()->format(config('constant.date_format.set')) : null;
        $data['reInsurance'] = $criteriaReInsurance ? Carbon::parse($criteriaReInsurance->start_date)->addDay()->format(config('constant.date_format.set')) : null;
        $data['groupInsurance'] = $groupInsurance ? Carbon::parse($groupInsurance->start_date)->format(config('constant.date_format.set')) : null;
        $data['groupReInsurance'] = $groupReInsurance ? Carbon::parse($groupReInsurance->start_date)->format(config('constant.date_format.set')) : null;
        return (object) $data;
    }

    private function findStatus($start_date, $end_date = null)
    {
        $status = '';
        $statusService = new StatusService();
        $start_date = Carbon::parse($start_date)->startOfDay();
        $current_date = Carbon::now()->startOfDay();
        if ($end_date)
            $end_date = Carbon::parse($end_date)->startOfDay();

        //Criteria not started when current date is less than start date
        if ($current_date->lt($start_date)) {
            $status = $statusService->fetchStatusByModelSlug('criteria', 'not-started')->id;
        }
        //Criteria started when current date is between start and end date OR
        //when current date is greater than equal start date and end date is null
        elseif ($current_date->between($start_date, $end_date) || ($current_date->gte($start_date) && $end_date == null)) {
            $status = $statusService->fetchStatusByModelSlug('criteria', 'started')->id;
        }
        ///Criteria not started when current date is legreater than end date
        elseif ($current_date->gt($end_date)) {
            $status = $statusService->fetchStatusByModelSlug('criteria', 'expired')->id;
        }
        return $status;
    }

    public function fetchStatus($id)
    {
        return $this->model->select('status_id')->findOrFail($id)->status_id;
    }

    public function groupCount($type)
    {
        return $this->model->whereHas('group', function ($query) use ($type) {
            $query->where('applicable_to', $type);
        })->withCount('group')->get();
    }

    public function updateStartingCriteria($date, $newStatus)
    {
        $statusService  = new StatusService();
        $status         = $statusService->fetchStatusByModelSlug('criteria', 'not-started');
        return $this->model->whereDate('start_date', $date)->status($status->id)->update(['status_id' => $newStatus]);
    }

    public function updateEndingCriteria($date, $newStatus)
    {
        $statusService  = new StatusService();
        $status         = $statusService->fetchStatusByModelSlug('criteria', 'started');
        return $this->model->whereDate('end_date', "<", $date)->status($status->id)->update(['status_id' => $newStatus]);
    }

    public function fetchCriteriaByDate($date)
    {
        $date = Carbon::parse($date)->toDateTimeString();
        // dd($this->model->whereDate('start_date', '>=', Carbon::parse($date))->whereDate('end_date', '<=', Carbon::parse($date))->get());
        return $this->model->orWhere(function($query) use ($date){
            $query->where('start_date', '<=', $date);
            $query->where('end_date', '>=', $date);
        })->orWhere(function($query) use ($date){
            $query->where('start_date', '<=', $date);
            $query->where('end_date', null);
        })->get();
    }
}
