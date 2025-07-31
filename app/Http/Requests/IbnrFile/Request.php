<?php

namespace App\Http\Requests\IbnrFile;

use App\Helpers\CustomHelper;
use App\Models\IbnrAssumption;
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
        $ibnr_assumption_id = CustomHelper::decode($this->route('ibnr_assumption'));
        
        $file_id = CustomHelper::decode($this->route('file'));

        $rules = [
            'name'                  => 'required',
            'valuation_date'        => ['date', 'required', new UniqueValuationDate(IbnrAssumption::class, $ibnr_assumption_id, $file_id)]
        ];
        
        if ($file_id == null && empty($file_id)) {
            $rules['ibnr_file'] = 'required|file|mimes:csv';
        }else{
            $rules['ibnr_file'] = 'nullable|file|mimes:csv';
        }

        return $rules;
    }
}
