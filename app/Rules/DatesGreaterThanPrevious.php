<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\OrganizationService;
use Carbon\Carbon;

class DatesGreaterThanPrevious implements Rule
{
    
    protected $model;
    protected $startDate;
    protected $endDate;
    protected $organizationService;


    public function __construct($model,$startDate, $endDate)
    {
        $this->organizationService = new OrganizationService;
        $this->model        = $model;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
    }

    public function passes($attribute, $value)
    {
        $formattedDate = Carbon::parse($value)->format('Y-m-d H:i:s');
        $organization_id = $this->organizationService->getAuthOrganizationId();

        $lastRecord = $this->model::where('organization_id',$organization_id)
        ->latest('ends_at')
        ->first();

        // dd($lastRecord);

        // Ensure start_date is greater than last end_date
        if ($attribute === 'start_date' && $lastRecord) {
            return $formattedDate > $lastRecord->ends_at;
        }

        // Ensure end_date is greater than start_date
        if ($attribute === 'end_date') {
            return $formattedDate > request('start_date');
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Given dates always greater than previous record.';
    }
}
