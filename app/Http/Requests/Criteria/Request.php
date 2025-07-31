<?php

namespace App\Http\Requests\Criteria;

use App\Models\Criteria;
use App\Rules\CriteriaDates;
use App\Services\CriteriaService;
use Illuminate\Validation\Rule;
use App\Services\OrganizationService;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
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
        $criteriaService = new CriteriaService();
        $organization = new OrganizationService();
        $organizationId = $organization->getAuthOrganizationId();
        $criteria = $criteriaService->fetchLatestCriteriaOfAuthUserOrganization($this->applicable_to);

        return [
            'name' => [
                'required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($organizationId) {
                    $existing = Criteria::where('organization_id', $organizationId)
                        ->where('name', $value)
                        ->first();
                    if ($existing) {
                        $fail("A criteria with this name already exists in " . ucfirst($existing->applicable_to) . ".");
                    }
                },
            ],
           'description' => 'required|string|max:70',
            'applicable_to' => 'required|string|max:255',
            'start_date' => ['required', new CriteriaDates],
            'end_date' => 'nullable|date',
            'status_id' => 'nullable',
        ];
    }
}
