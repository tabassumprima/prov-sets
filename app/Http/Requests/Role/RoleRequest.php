<?php

namespace App\Http\Requests\Role;

use App\Helpers\CustomHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        $roleId =  CustomHelper::decode($this->role);
        return [
            'name' => 'required|string|'. Rule::unique('roles', 'name')->where('organization_id', $this->organization_id)->ignore($roleId)
        ];
    }
}
