<?php

namespace App\Http\Requests\Setting;

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


    public function validator($factory)
    {
        return $factory->make(
        $this->sanitize(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    public function sanitize()
    {
        $this->merge([
            'options' => [
                'marine_exposure_days'          =>  $this->input('options.marine_exposure_days') ? (int) $this->input('options.marine_exposure_days') : null,
                'discounting_period_year'       =>  $this->input('options.discounting_period_year') ? (int) $this->input('options.discounting_period_year') : null,
                'ibnr_period_year'              =>  $this->input('options.ibnr_period_year') ? (int) $this->input('options.ibnr_period_year') : null,
                'unallocated_portfolio_id'      =>  $this->input('options.unallocated_portfolio_id') ? (int) $this->input('options.unallocated_portfolio_id') : null,
                'marine_products_id'            =>  $this->input('options.marine_products_id') ? collect($this->input('options.marine_products_id'))->map(fn($q) => (int) $q):  null,
                'marine_reproducts_id'          =>  $this->input('options.marine_reproducts_id') ? collect($this->input('options.marine_reproducts_id'))->map(fn($q) => (int) $q):  null,
                'headoffice_portfolio_id'       =>  $this->input('options.headoffice_portfolio_id') ? (int) $this->input('options.headoffice_portfolio_id') : null,
                'lambda_posting_voucher_id'     =>  $this->input('options.lambda_posting_voucher_id') ? (int) $this->input('options.lambda_posting_voucher_id') : null,
                'management_expense_level_id'   =>  $this->input('options.management_expense_level_id') ? (int) $this->input('options.management_expense_level_id') : null,
                'opening_balance_lambda_id'     =>  $this->input('options.opening_balance_lambda_id') ? (int) $this->input('options.opening_balance_lambda_id') : null,
                'post_entry_lambda_id'          =>  $this->input('options.post_entry_lambda_id') ? (int) $this->input('options.post_entry_lambda_id') : null,
                'fail_lambda_id'                =>  $this->input('options.fail_lambda_id') ? (int) $this->input('options.fail_lambda_id')  : null,
                'transition_date'               =>  $this->input('options.transition_date') ? $this->input('options.transition_date') : null,
                'is_auto_import'                =>  $this->input('options.is_auto_import') ? $this->input('options.is_auto_import') : null,

            ]
        ]);
        return $this->all();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return array_merge(
            [
                
                'options.unallocated_portfolio_id'       => 'required_if:form_type,general',
                'options.headoffice_portfolio_id'        => 'required_if:form_type,general',
                'options.lambda_posting_voucher_id'      => 'required_if:form_type,general',
                'options.management_expense_level_id'    => 'required_if:form_type,general',
                'options.marine_products_id'             => 'required_if:form_type,marine-insurance',
                'options.marine_reproducts_id'           => 'required_if:form_type,marine-reinsurance',
                'options.opening_balance_lambda_id'      => 'required_if:form_type,provision',
                'options.post_entry_lambda_id'           => 'required_if:form_type,provision',
                'options.fail_lambda_id'                 => 'required_if:form_type,provision',
                'options.transition_date'                => 'required_if:form_type,import',
                'options.is_auto_import'                 => 'required_if:form_type,import',
            ],
            $this->input('form_type') === 'general' ? [
                'options.marine_exposure_days'           => 'required|integer',
                'options.discounting_period_year'        => 'required|integer',
                'options.ibnr_period_year'               => 'required|integer',
            ] : []
        );
    }

    public function messages()
    {
        return [
            'options.unallocated_portfolio_id.required_if' => 'Unallocated Portfolio is required.',
            'options.headoffice_portfolio_id.required_if' => 'Headoffice Portfolio is required.',
            'options.lambda_posting_voucher_id.required_if' => 'Lambda Posting Voucher is required.',
            'options.management_expense_level_id.required_if' => 'Management Expense Level is required.',
            'options.marine_products_id.required_if' => 'Marine Insurance Products is required.',
            'options.marine_reproducts_id.required_if' => 'Marine Reinsurance Products is required.',
            'options.opening_balance_lambda_id.required_if' => 'Opening Balance Lambda is required.',
            'options.post_entry_lambda_id.required_if' => 'Post Entry Lambda is required.',
            'options.fail_lambda_id.required_if' => 'Fail Lambda is required.',
            'options.transition_date.required_if' => 'Transition Date is required.',
            'options.is_auto_import.required_if' => 'Auto Import option is required.',

            'options.marine_exposure_days.required' => 'Marine Exposure Days is required.',
            'options.ibnr_period_year.required' => 'IBNR Period Year is required.',
            'options.discounting_period_year.required' => 'Discount Period Year is required.',
            
            'options.marine_exposure_days.integer' => 'Marine Exposure Days must be an integer.',
            'options.ibnr_period_year.integer' => 'IBNR Period Year must be an integer.',
            'options.discounting_period_year.integer' => 'Discount Period Year must be an integer.',
            
        ];
    }
}
