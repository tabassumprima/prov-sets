<?php

namespace App\Http\Requests\Currency;

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
        $currencyId = CustomHelper::decode($this->route('currency'));
      
        return [
            'name' => 'required|string|max:255|'.Rule::unique('currencies', 'name')->ignore($currencyId),
            'symbol' => 'required|string|max:255|'.Rule::unique('currencies', 'symbol')->ignore($currencyId),
        ];
    }
}
