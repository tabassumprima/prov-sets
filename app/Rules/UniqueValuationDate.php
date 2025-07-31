<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Services\OrganizationService;
use Carbon\Carbon;

class UniqueValuationDate implements Rule
{
    private $organizationService, $model, $fileId, $id;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($model, $fileId, $id)
    {
        $this->organizationService = new OrganizationService;
        $this->model               = $model;
        $this->fileId              = $fileId;
        $this->id                  = $id;
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
        $check           = true;
        $organization_id = $this->organizationService->getAuthOrganizationId();
        $value           = Carbon::parse($value)->format(config('constant.date_format.set'));
        if(!empty($this->id) && isset($this->id))
        {
            $fileExistWithSameDate = $this->model::withWhereHas('files', function ($query) use ($value) {
                $query->where([['valuation_date', $value], ['file_id', $this->fileId], ['id','!=', $this->id]]);
            })->where('organization_id', $organization_id)->exists();

        }else{
            $fileExistWithSameDate = $this->model::withWhereHas('files', function ($query) use ($value) {
                $query->where([['valuation_date', $value], ['file_id', $this->fileId]]);
            })->where('organization_id', $organization_id)->exists();
        }
        
        if ($fileExistWithSameDate)
            $check = false;
        return $check;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Valuation date must be unique.';
    }
}
