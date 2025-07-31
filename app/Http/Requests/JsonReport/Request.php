<?php

namespace App\Http\Requests\JsonReport;

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
        return [
            'report_format_file'    => 'required|mimes:json|max:2048',
            'report_type'           => 'required'
        ];
    }
}
