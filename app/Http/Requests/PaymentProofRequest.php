<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class PaymentProofRequest extends FormRequest
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
        return [
            'proof' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB in KB
            ],
            'payment_method' => 'required|string|in:bank_transfer,e_wallet,cash',
            'sender_account' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'transfer_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:500',
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
            'proof.required' => 'Please upload a payment proof.',
            'proof.max' => 'The file size must not exceed 5MB.',
            'proof.mimes' => 'Only JPG, PNG, and PDF files are allowed.',
            'transfer_date.before_or_equal' => 'Transfer date cannot be in the future.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Format transfer date if needed
        if ($this->has('transfer_date')) {
            $this->merge([
                'transfer_date' => \Carbon\Carbon::parse($this->transfer_date)->toDateString(),
            ]);
        }
    }
}
