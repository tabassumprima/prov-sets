<?php

namespace App\Rules;

use App\Services\CriteriaService;
use App\Services\GroupService;
use App\Services\OrganizationService;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Carbon;

class CriteriaDates implements DataAwareRule, InvokableRule
{


     /**
     * All of the data under validation.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $criteriaService = new CriteriaService();
        $organization = new OrganizationService();
        $groupService = new GroupService();
        $criteria = $criteriaService->fetchLatestCriteriaOfAuthUserOrganization($this->data['applicable_to']);
        $group = $groupService->fetchLatestGroupWithCriteria($criteria?->id);
        $pre_criteria_start_date = $criteria?->start_date ? Carbon::parse($criteria->start_date) : Carbon::now()->startOfDay();

        $criteria_start_date = Carbon::parse($value);
        $today = Carbon::now()->startOfDay();

        if(is_impersonating() && $organization->isBoarding()){
            if(!$criteria ){
                return;
            }
            else if ( !$pre_criteria_start_date->lt($criteria_start_date)){
                $fail("The $attribute must be after " . $pre_criteria_start_date->toDateString());
            }
        }
        else {
            if(!$today->lt($criteria_start_date) && !$criteria){
                $fail("The $attribute must be after " . $today->toDateString());
            }
            else if ($criteria && ($criteria_start_date->lte($pre_criteria_start_date) || $criteria_start_date->lte($today))) {
                $fail("The $attribute must be after " . $today->toDateString());
            }
        }
        if($group){
            if($criteria_start_date->lte($group->start_date)){
                $fail("The $attribute must be after " . Carbon::parse($group->start_date)->format('Y-m-d'));
            }
        }
    }


    // ...

    /**
     * Set the data under validation.
     *
     * @param  array  $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
}
