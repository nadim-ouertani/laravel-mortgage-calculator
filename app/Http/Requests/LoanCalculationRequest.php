<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoanCalculationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'loan_amount' => 'required|numeric|min:5000',
            'annual_interest_rate' => 'required|numeric|min:0',
            'loan_term_years' => 'required|integer|min:1',
            'monthly_extra_payment' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'loan_amount.min' => 'Loan amount must be at least AED 5,000',
            'annual_interest_rate.min' => 'Interest rate cannot be negative',
            'loan_term_years.min' => 'Loan term must be at least 1 year',
            'monthly_extra_payment.min' => 'Extra payment cannot be negative',
        ];
    }
}