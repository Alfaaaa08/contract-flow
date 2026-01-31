<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest {

    public function authorize(): bool {
        return true; // No auth logic implemented yet.
    }

    public function rules(): array {
        return [
            'name'             => ['required', 'string',  'max:255'],
            'client_id'        => ['required', 'integer'],
            'contract_type_id' => ['required', 'integer'],
            'value'            => ['required', 'numeric', 'min:0'],
            'start_date'       => ['nullable', 'date',    'after_or_equal:today'],
            'end_date'         => ['required', 'date',    'after:start_date'],
        ];
    }

    public function messages(): array {
        return [
            'name.required'                 => 'Contract name is required',
            'client_id.required'            => 'Client is required',
            'contract_type_id.required'     => 'Type is required',
            'value.required'                => 'Value is required',
            'value.min'                     => 'Value must be a positive number',
            'start_date.date'               => 'Start date must be a valid date',
            'end_date.required'             => 'End date is required',
            'end_date.date'                 => 'End date must be a valid date',
            'end_date.after'                => 'End date must be after start date',
        ];
    }
}
