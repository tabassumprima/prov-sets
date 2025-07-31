<?php

namespace App\Http\Requests\LambdaFunction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $lambdaId = $this->route('lambda');
        $organizationId = $this->organization_id;

        return [
            'name' => [
                'required',
                function ($attribute, $value, $fail) use ($lambdaId, $organizationId) {
                    $lowerName = Str::lower(trim($value));
                
                    $exists = DB::table('lambda_functions')
                    ->whereRaw('LOWER(name) = ?', [$lowerName])
                    ->where('organization_id', $organizationId)
                    ->when($lambdaId, fn($q) => $q->where('id', '!=', $lambdaId))
                    ->exists();
                
                    if ($exists) {
                        $fail("This name has already been taken for this organization.");
                    }
                }           
                
            ],

            'command' => 'required',
            'config' => 'required|json',
        ];
    }
}
