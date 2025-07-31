<?php

namespace App\Http\Requests\Folder;

use Illuminate\Foundation\Http\FormRequest;

class FilesRequest extends FormRequest
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
            'table_type' =>  'required',
            'import_file' => 'required|file|mimes:csv' 
        ];
    }

    public function messages()
    {
        return [
            'import_file.required' => 'Import file have some issue.',
        ];
    }
}
