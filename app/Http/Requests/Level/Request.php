<?php

namespace App\Http\Requests\Level;

use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return 
        [
            'dl_code' => ['sometimes','min:3','unique:levels,code', ],
            'level' => ['required','min:3'],
            'category'=> ['required'],
        ];
    }
}
