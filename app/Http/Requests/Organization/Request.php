<?php

namespace App\Http\Requests\Organization;

use App\Helpers\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $organization_id =  CustomHelper::decode($this->organization) ?? null;
        return [
            'name'              => 'required|'. Rule::unique('organizations')->ignore($organization_id),
            'type'              => 'required',
            'sales_tax_number'  => 'required',
            'ntn_number'        => 'required',
            'country_id'        => 'required',
            'shortcode'         => 'required|'. Rule::unique('organizations')->ignore($organization_id),
            'logo'              => 'nullable|mimes:png,jpg|max:1024',
            'currency_id'       => 'required',
            'subscription_plan' => 'sometimes|required',
            'address'           => 'required|string|max:225',
            'database_config_id'=> 'required',
            'date_format'       => 'nullable',
            'financial_year'    => 'required',
            'agent_config'      => 'nullable'
        ];
    }
}
