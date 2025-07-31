<?php

namespace App\Http\Requests\Folder;

use App\Rules\CheckDateRangeOverlap;
use App\Rules\DatesGreaterThanPrevious;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Folder;
use Illuminate\Support\Carbon;

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
            'start_date' => 
            [
                'required',
                'date',
                new DatesGreaterThanPrevious(Folder::class, $this->start_date, $this->end_date )
            ],
            'end_date' => [
                'required', 
                'date', 
                'after:start_date', // This should be a string
                new CheckDateRangeOverlap(Folder::class, $this->start_date, $this->end_date), // Custom rule
                'before_or_equal:' . Carbon::parse($this->start_date)->addMonths(3)->format('Y-m-d'), // Max 3 months from start_date
            ],
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'The start date is required.',
            'end_date.required' => 'The end date is required.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'end_date.before_or_equal' => 'The date range must not exceed 3 months.',
        ];
    }
}
