<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $event = $this->route('event');

        return [
            'category' => [
                'required',
                'string',
                Rule::in($event->categories ?? [])
            ],
            'participant_info.t_shirt_size' => 'required|string|in:XS,S,M,L,XL,XXL',
            'participant_info.emergency_contact_name' => 'required|string|max:255',
            'participant_info.emergency_contact_phone' => 'required|string|max:20',
            'participant_info.medical_conditions' => 'nullable|string|max:500',
            'terms_accepted' => 'required|accepted',
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
            'category.required' => 'Please select a race category.',
            'category.in' => 'The selected category is not available for this event.',
            'participant_info.t_shirt_size.required' => 'T-shirt size is required.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
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
            'participant_info.t_shirt_size' => 'T-shirt size',
            'participant_info.emergency_contact_name' => 'emergency contact name',
            'participant_info.emergency_contact_phone' => 'emergency contact phone',
        ];
    }
}
