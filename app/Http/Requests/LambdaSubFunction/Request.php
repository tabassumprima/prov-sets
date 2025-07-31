<?php

namespace App\Http\Requests\LambdaSubFunction;

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
        return [
            'lambda_function_id' => 'required|string',
            'narration' => 'required',
            'entries.*.transaction_type' => 'required|string',
            'entries.*.level_id' => [
                'nullable', // Allow it to be empty
                'required_without:entries.*.gl_code_id', // Required if gl_code_id is not present
            ],
            'entries.*.gl_code_id' => [
                'nullable',
                'required_without:entries.*.level_id', // Required if level_id is not present

            ],
        ];
    }

    public function messages()
    {
        return [
            'lambda_function_id.required' => 'Lambda Function is required.',
            'narration.required' => 'Narration is required.',
            'entries.*.level_id.required_without' => 'Level is required if GL Code is not provided in entry :attribute.',
            'entries.*.gl_code_id.required_without' => 'GL Code is required if Level is not provided in entry :attribute.',
            'entries.*.level_id.prohibited' => 'Level cannot be provided if GL Code is present in entry :attribute.',
            'entries.*.gl_code_id.prohibited' => 'GL Code cannot be provided if Level is present in entry :attribute.',
            'entries.*.transaction_type.required' => 'Transaction type is required in entry :attribute.',
        ];
    }

    public function attributes()
    {
        $attributes = [];
        foreach ($this->input('entries', []) as $index => $entry) {
            $displayIndex = $index + 1; // Start index from 1
            $attributes["entries.{$index}.level_id"] = "entry {$displayIndex} level";
            $attributes["entries.{$index}.gl_code_id"] = "entry {$displayIndex} GL Code";
            $attributes["entries.{$index}.transaction_type"] = "entry {$displayIndex} transaction type";
        }
        return $attributes;
    }



}
