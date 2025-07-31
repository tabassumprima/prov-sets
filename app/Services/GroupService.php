<?php

namespace App\Services;

use App\Models\Group;
use App\Helpers\CustomHelper;
use App\Jobs\GroupCodeMapping;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class GroupService
{
    protected $model;
    public function __construct()
    {
        $this->model = new Group();
    }

    public function create($request)
    {
        $organization = Auth::user()->organization;
        $this->updateLastGroupEndDate($request, true);
        $request->merge(['status_id' => $this->findStatus($request->start_date)]);

        $criteriaService = new CriteriaService();
        $criteria = $criteriaService->fetch($request->criteria_id);

        if ($criteria->end_date)
            $request->merge(['end_date' => $criteria->end_date]);

        return $organization->groups()->create($request->all());
    }

    public function update($data, $id)
    {
        $group = $this->fetch($id);
        return $group->fill($data->all())->save();
    }

    public function delete($id)
    {
        $byPassAllcheck = auth()->user()->isImpersonated() && auth()->user()->organization->isBoarding;
        $group          = $this->fetch($id);
        $criteria_id = $group->criteria_id;
        if ($byPassAllcheck || ($group->status->slug != 'started' && $group->status->slug != 'expired')) {
            $latestGroupId = $this->fetchLatestGroupIdOfCriteria($criteria_id);
            if (CustomHelper::has_any_relations($group, ['treaty', 'facultative', 'product'])) {
                throw new \Exception('Group cannot be deleted because it has linked mappings.');
            }
            if (CustomHelper::decode($id) == $latestGroupId) {
                $group->delete();
                $this->updateEndDateOfLatestGroupCriteria($criteria_id);
            } else {
                throw new \Exception(trans('user/group.delete_only_latest'));
            }
        } else {
            throw new \Exception(trans('user/group.error_active'));
        }
    }

    public function fetch($id)
    {
        return $this->model->with(['status', 'organization', 'treaty', 'facultative'])->findOrFail(CustomHelper::decode($id));
    }

    public function fetchWithCriteria($id)
    {
        return $this->model->with('criteria')->findOrFail(CustomHelper::decode($id));
    }


    public function fetchAll()
    {
        $organizationId = Auth::user()->organization->id;
        return $this->model->with('criteria', 'status')->where('organization_id', $organizationId)->orderBy('criteria_id', 'asc')->get();
    }

    // Verify request will check if the group is active else return original request
    public function updateLastGroupEndDate($data, $setEndDate = false)
    {
        $latestGroup = $this->fetchLatestGroupWithCriteria($data->criteria_id);
        //set last group end date to start date of new group
        if ($latestGroup) {
            if ($setEndDate)
                $latestGroup->end_date = Carbon::parse($data->start_date)->subDay();
            else
                $latestGroup->setRawAttributes(['end_date' => null]);
            $latestGroup->save();
        }
        return $latestGroup;
    }

    public function updateEndDateOfLatestGroupCriteria($criteria_id)
    {
        $latestGroup = $this->fetchLatestGroupWithCriteria($criteria_id);
        if ($latestGroup) {
            if ($latestGroup->criteria->end_date) {
                $latestGroup->end_date = $latestGroup->criteria->end_date;
            } else {
                $latestGroup->end_date = null;
            }
            $startedStatus = $this->findStatus($latestGroup->start_date, $latestGroup->criteria->end_date);
            $latestGroup->status_id = $startedStatus;
            $latestGroup->save();
        }
    }

    // Get latest Group of authorize perons's organization
    public function  fetchLatestGroupWithCriteria($criteriaId)
    {
        return $this->model->with('criteria')->where([['organization_id', Auth::user()->organization->id], ['criteria_id', $criteriaId]])->latest()->first();
    }

    // Get Group ID of latest group criteria
    public function fetchLatestGroupIdOfCriteria($criteria_id)
    {
        return $this->model->select('id')->where('criteria_id', $criteria_id)->latest()->first()->id;
    }

    private function findStatus($start_date, $end_date = null)
    {
        $status = '';
        $statusService = new StatusService();
        $start_date = Carbon::parse($start_date)->startOfDay();
        $current_date = Carbon::now()->startOfDay();
        if ($end_date)
            $end_date = Carbon::parse($end_date)->startOfDay();

        //group not started when current date is less than start date
        if ($current_date->lt($start_date)) {
            $status = $statusService->fetchStatusByModelSlug('group', 'not-started')->id;
        }
        //group started when current date is between start and end date OR
        //when current date is greater than equal start date and end date is null
        elseif ($current_date->between($start_date, $end_date) || ($current_date->gte($start_date) && $end_date == null)) {
            $status = $statusService->fetchStatusByModelSlug('group', 'started')->id;
        }
        ///group not started when current date is legreater than end date
        elseif ($current_date->gt($end_date)) {
            $status = $statusService->fetchStatusByModelSlug('group', 'expired')->id;
        }
        return $status;
    }

    public function updateStartingGroup($date, $newStatus)
    {
        $statusService  = new StatusService();
        $status         = $statusService->fetchStatusByModelSlug('group', 'not-started');
        return $this->model->whereDate('start_date', $date)->status($status->id)->update(['status_id' => $newStatus]);
    }

    public function updateEndingGroup($date, $newStatus)
    {
        $statusService  = new StatusService();
        $status         = $statusService->fetchStatusByModelSlug('group', 'started');
        return $this->model->whereDate('end_date', '<', $date)->status($status->id)->update(['status_id' => $newStatus]);
    }



    public function verifyGroupStatus($group_id)
    {
        $groupStatus =   CustomHelper::isActiveOrExpired($group_id, $this->model);
        if ($groupStatus) {
            throw new \Exception('Group is active or expired . You cannot edit products.');
        }
    }

}
