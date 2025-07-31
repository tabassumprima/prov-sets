<?php

namespace App\Http\Requests\CloudSetting;

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
            'access_token'          => 'required',
            'access_token_expiry'   => 'required',
            'rds_db_name'           => 'required',
            'rds_user'           => 'required',
            'rds_password'           => 'required',
            'rds_host'           => 'required',
            'bucket'           => 'required',
        ];
    }
}
