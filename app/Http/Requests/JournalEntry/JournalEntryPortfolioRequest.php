<?php

namespace App\Http\Requests\JournalEntry;

use Illuminate\Foundation\Http\FormRequest;

class JournalEntryPortfolioRequest extends FormRequest
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
            'date_range' => [
                'required',
                function ($attribute, $value, $fail) {
                    $type = $this->input('type');
                    $dates = explode(' to ', $value);
                    if (!in_array($type, ['BS', 'BREAKUP'])) {
                        if (empty($dates[0]) || empty($dates[1])) {
                            $fail('Both start and end dates are required.');
                        }
                    } else {
                        if (empty($dates[0])) {
                            $fail('Date is required.');
                        }
                    }
                }
            ]
        ];
    }
}
