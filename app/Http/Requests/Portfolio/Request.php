<?php

namespace App\Http\Requests\Portfolio;

use App\Helpers\CustomHelper;
use App\Services\OrganizationService;
use Illuminate\Validation\Rule;
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
        $organizationService = new OrganizationService();
        $organiztion_id = $organizationService->getAuthOrganizationId();
        if($this->data)
            $id = collect($this->data)->pluck('id')->toArray();
        else
            $id = collect($this->id);
        return [
            'data.*.id'                       => 'nullable',
            'data.*.portfolio_id'             => 'required',
            'data.*.system_department_id'     => 'required',
            'name'                            => [
                Rule::requiredIf(function () { return $this->has('name');}),
                Rule::unique('portfolios', 'name')->whereNotIn('id', $id)->where('organization_id', $organiztion_id), 'max:100'],
                'shortcode'                       => [
                Rule::requiredIf(function () { return $this->has('shortcode');}),
                Rule::unique('portfolios', 'shortcode')->whereNotIn('id', $id)->where('organization_id', $organiztion_id)],
                'max:6'
        ];
    }

    public function messages()
    {
        return [
            'portfolios.*.portfolio_id.required'               => 'portfolios name is required',
            'portfolios.*.system_department_id.required'    => 'System department is required',
            'portfolios.*.system_department_id.array'       => 'System department must be an array',
            'portfolios.*.system_department_id.*.distinct'  => 'System department must be unique among all portfoilios',
        ];
    }
}
