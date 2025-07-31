<?php

namespace App\Http\Requests\ClaimPattern;

use App\Helpers\CustomHelper;
use App\Models\ClaimPattern;
use App\Rules\UniqueValuationDate;
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $claim_pattern_id = CustomHelper::decode($this->route('claim_pattern'));
        
        $file_id = CustomHelper::decode($this->route('file'));

        $rules = [
            'name'                  => 'required',
            'valuation_date'        => ['date', 'required', new UniqueValuationDate(ClaimPattern::class, $claim_pattern_id, $file_id)]
        ];

        if ($file_id == null && empty($file_id)) {
            $rules['claim_file'] = 'required|file|mimes:csv';
        }else{
            $rules['claim_file'] = 'nullable|file|mimes:csv';
        }

        return $rules;
    }
}
