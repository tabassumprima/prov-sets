<?php

namespace App\Http\Requests\Country;

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
        $countryId = CustomHelper::decode($this->country);
      
        return [
            'name' => 'required|string|max:255|'.Rule::unique('countries', 'name')->ignore($countryId),
            'code' => 'required|string|max:255|'.Rule::unique('countries', 'code')->ignore($countryId),
            'timeZone' => 'required',
        ];
    }
}
