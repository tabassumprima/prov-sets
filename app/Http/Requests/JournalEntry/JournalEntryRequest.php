<?php

namespace App\Http\Requests\JournalEntry;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryRequest extends FormRequest
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
            'voucher_type_id'            => 'required|numeric',
            'accounting_year_id'         => 'required|numeric',
            'branch_info_id'             => 'required|numeric',
            'business_type_id'           => 'required|numeric',
            'system_date'                => 'required|date',
            'system_narration1'          => 'required|string',
            'system_narration2'          => 'nullable|string',
            'entries'                    => 'required|array',
            'entries.*.gl_code_id'       => 'required|numeric',
            'entries.*.portfolio_id'     => 'required|numeric',
            'entries.*.debit_amount'     => 'sometimes|numeric',
            'entries.*.credit_amount'    => 'sometimes|numeric',
            'entries.*.policy_reference' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'entries.*.gl_code_id.required'             => 'gl_code_id is required',
            'entries.*.portfolio_id.required'           => 'portfolio_id is required',
            'entries.*.debit.required'                  => 'debit is required',
            'entries.*.credit.required'                 => 'credit is required',
            'entries.*.policy_reference.required'       => 'document reference is required',
        ];
    }
}
