<?php

namespace App\Http\Requests\RiskAdjustment;

use App\Helpers\CustomHelper;
use App\Models\RiskAdjustment;
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
        $risk_adjustment_id = CustomHelper::decode($this->route('risk_adjustment'));
        
        $file_id = CustomHelper::decode($this->route('file'));

        $rules = [
            'name'                  => 'required',
            'valuation_date'        => ['date', 'required', new UniqueValuationDate(RiskAdjustment::class, $risk_adjustment_id,$file_id)]
        ];

        if ($file_id == null && empty($file_id)) {
            $rules['risk_adjustment_file'] = 'required|file|mimes:csv';
        }else{
            $rules['risk_adjustment_file'] = 'nullable|file|mimes:csv';
        }

        return $rules;
    }
}
