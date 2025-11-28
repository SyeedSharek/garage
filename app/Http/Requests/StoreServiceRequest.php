<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique(Service::class, 'code'),
            ],
            'name' => [
                'required',
                'array',
            ],
            'name.en' => [
                'required',
                'string',
                'max:255',
            ],
            'name.ar' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'array',
            ],
            'description.en' => [
                'nullable',
                'string',
            ],
            'description.ar' => [
                'nullable',
                'string',
            ],
            'unit_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'unit' => [
                'required',
                'string',
                'max:50',
            ],
            'is_active' => [
                'sometimes',
                'boolean',
            ],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
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
            'code.unique' => 'The service code has already been taken.',
            'name.required' => 'The service name is required.',
            'name.en.required' => 'The English service name is required.',
            'name.ar.required' => 'The Arabic service name is required.',
            'unit_price.required' => 'The unit price is required.',
            'unit_price.numeric' => 'The unit price must be a number.',
            'unit_price.min' => 'The unit price must be at least 0.',
            'unit.required' => 'The unit is required.',
        ];
    }
}

