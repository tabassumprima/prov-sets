<?php

namespace App\Http\Requests\DiscountRate;

use App\Helpers\CustomHelper;
use App\Models\DiscountRate;
use App\Rules\UniqueValuationDate;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $discount_rate_id = CustomHelper::decode($this->route('discount_rate'));
        
        $file_id = CustomHelper::decode($this->route('file'));

        $rules = [
            'name'                  => 'required',
            'valuation_date'        => ['date', 'required', new UniqueValuationDate(DiscountRate::class, $discount_rate_id, $file_id)]
        ];
        
        if ($file_id == null && empty($file_id)) {
            $rules['discount_file'] = 'required|file|mimes:csv';
        }else{
            $rules['discount_file'] = 'nullable|file|mimes:csv';
        }

        return $rules;
    }
}
