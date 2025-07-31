<?php

namespace App\Http\Requests\GroupProduct;

use Illuminate\Foundation\Http\FormRequest;

class GroupProductRequest extends FormRequest
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
        return [
            'group_id' => 'nullable',
            'data.*.product_key' => 'nullable',
            'data.*.measurement_model_id' => 'required',
            'data.*.cohorts_code_id' => 'required',
            'data.*.product_grouping' => 'required',
            'data.*.onerous_threshold' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'data.*.product_key.required'                    => 'portfolios name is required',
            'data.*.measurement_model_id.required'               => 'portfolios shortcode is required',
            'data.*.cohorts_code_id.required'    => 'System department is required',
            'data.*.product_grouping.required'       => 'product_grouping must be an array',

        ];
    }
}
