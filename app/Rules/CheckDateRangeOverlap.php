<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\OrganizationService;
use Carbon\Carbon;

class CheckDateRangeOverlap implements Rule
{
    protected $model;
    protected $startDate;
    protected $endDate;
    protected $organizationService;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model,$startDate, $endDate)
    {
        $this->organizationService = new OrganizationService;
        $this->model        = $model;
        $this->startDate    = $startDate;
        $this->endDate      = $endDate;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Get the organization ID (assuming the service is injected or available)
        $organization_id = $this->organizationService->getAuthOrganizationId();
        
        // Ensure the organization ID is captured inside the closure
        $query = $this->model::where(function ($query) use ($organization_id) {
            // Query for overlapping date ranges within the same organization
            $query->where('organization_id', '=', $organization_id)
                ->where(function ($query) {
                    $query->whereBetween('starts_at', [$this->startDate, $this->endDate])
                          ->orWhereBetween('ends_at', [$this->startDate, $this->endDate])
                          ->orWhere(function ($query) {
                              $query->where('starts_at', '<=', $this->startDate)
                                    ->where('ends_at', '>=', $this->endDate);
                          });
                });
        });
    
        // Return true if no overlap exists, false otherwise
        return !$query->exists();
    }
    

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected date range overlaps with an existing record.';
    }
}
