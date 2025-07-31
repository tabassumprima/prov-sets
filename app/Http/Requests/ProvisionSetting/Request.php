<?php

namespace App\Http\Requests\ProvisionSetting;

use App\Services\OrganizationService;
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
        $organization = new OrganizationService();
        return [
            'name'=> ['required','max:100',
            Rule::unique('provision_settings', 'name')
            ->where(fn ($query) => $query->where('organization_id', $organization->getAuthOrganizationId()))],
            'description'=> 'required|max:100'
        ];
    }
}
