<?php

namespace App\Http\Requests\User;

use App\Helpers\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $user_id =  CustomHelper::decode($this->user_id);
        return [
            'name'              => 'required|string|max:50|regex:/^[a-zA-Z0-9\s]+$/',
            'email'             => 'required|string|email|max:255|'.Rule::unique('users')->ignore($user_id),
            'phone'             => 'required|string|regex:/^[0-9]+$/|max:13|min:7',
            'user_role'         => Rule::requiredIf(fn () => $this->has('user_role')),
            'password'          => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active'         => $user_id ? 'required' : 'nullable',
            'verification_type' => 'required'
        ];

    }
}
