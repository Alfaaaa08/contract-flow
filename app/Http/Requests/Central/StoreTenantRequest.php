<?php

declare(strict_types=1);

namespace App\Http\Requests\Central;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => [
                'required',
                'string',
                'max:255',
                'alpha_dash:ascii',
                Rule::unique('tenants', 'id'),
                Rule::unique('domains', 'domain'),
            ],
            'admin_email' => ['required', 'email', 'max:255', Rule::unique('tenants', 'admin_email')],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'domain.alpha_dash' => 'The domain may only contain letters, numbers, dashes, and underscores.',
            'domain.unique' => 'This domain is already in use.',
            'admin_email.unique' => 'This email is already associated with another tenant.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'admin_email' => 'admin email address',
        ];
    }
}
