<?php

namespace App\Http\Requests\Group;

use App\Services\CriteriaService;
use App\Services\GroupService;
use App\Services\OrganizationService;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $organizationService = new OrganizationService();
        $groupService = new GroupService();
        $criteriaService = new CriteriaService();
        $criteria = $criteriaService->fetchLatestCriteriaOfAuthUserOrganization($this->applicable_to);
        $criteriaStartDate = $criteria?->start_date ? Carbon::parse($criteria->start_date) : Carbon::now()->startOfDay();

        $today = Carbon::now()->startOfDay();
        $isBoarding = $organizationService->isBoarding();
        $isImpersonating = is_impersonating();
        $group = $groupService->fetchLatestGroupWithCriteria($this->criteria_id);

        if ($isBoarding && $isImpersonating) {
            // First-ever group for this criteria for admins the start date must be equal to criteria start date
            $startDate = $criteriaStartDate;
        } else {
            $startDate = $criteriaStartDate->lt($today) ? $today : $criteriaStartDate;

            if ($group) {
                $latestGroupStartDate = Carbon::parse($group->start_date)->addDay();
                $startDate = $latestGroupStartDate->gt($startDate) ? $latestGroupStartDate : $startDate;
            }
        }

        $formattedDate = $startDate->format(config('constant.date_format.set'));

        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:70',
            'criteria_id' => 'required',
            'start_date' => [
                'required',
                'after_or_equal:' . $formattedDate,
                Rule::unique('groups')->where(fn($query) => $query->where('criteria_id', $this->criteria_id)),
                function ($attribute, $value, $fail) use ($isBoarding, $isImpersonating, $group, $criteriaStartDate) {
                    if ($isBoarding && $isImpersonating && !$group) {
                        if (Carbon::parse($value)->ne($criteriaStartDate)) {
                            $fail("The start date for the first group must be exactly " . $criteriaStartDate->format(config('constant.date_format.set')));
                        }
                    }
                },
            ],
            'end_date' => 'nullable|date',
            'status_id' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'criteria_id.required' => 'Please select a Portfolio Criteria before submitting.',
        ];
    }
}
