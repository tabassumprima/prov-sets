<?php

namespace App\Http\Requests\IbnrFile;

use App\Helpers\CustomHelper;
use App\Models\IbnrAssumption;
use App\Rules\UniqueValuationDate;
use App\Services\OrganizationService;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class IbnrRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $OrganizationService = new OrganizationService();
        $rules = [
            'name' => [
                'required',
                Rule::unique('ibnr_assumptions', 'name')->where(fn ($query) => $query->where('organization_id', $OrganizationService->getAuthOrganizationId()))     
                ->ignore(CustomHelper::decode(request()->route('ibnr_assumption')), 'id'), 
            ],
            'triangle_type'         => 'required',
            'frequency'             => 'required'
        ];
    

        return $rules;
    }
}
