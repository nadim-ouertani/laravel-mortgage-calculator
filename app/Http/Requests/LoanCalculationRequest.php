<?php

namespace App\Http\Requests;

use App\Domain\Loan\Constants\LoanValidationConstants;
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
            'loan_amount' => [
                'required',
                'numeric',
                'min:' . LoanValidationConstants::MIN_LOAN_AMOUNT,
                'max:' . LoanValidationConstants::MAX_LOAN_AMOUNT
            ],
            'annual_interest_rate' => [
                'required',
                'numeric',
                'min:' . LoanValidationConstants::MIN_INTEREST_RATE
            ],
            'loan_term_years' => [
                'required',
                'integer',
                'min:' . LoanValidationConstants::MIN_LOAN_TERM_YEARS
            ],
            'monthly_extra_payment' => [
                'sometimes',
                'numeric',
                'min:' . LoanValidationConstants::MIN_EXTRA_PAYMENT
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'loan_amount.required' => 'Loan amount is required',
            'loan_amount.numeric' => 'Loan amount must be a valid number',
            'loan_amount.min' => 'Minimum loan amount is AED 5,000',
            'loan_amount.max' => 'Maximum loan amount is AED 4,000,000',
            'annual_interest_rate.required' => 'Annual interest rate is required',
            'annual_interest_rate.numeric' => 'Interest rate must be a valid number',
            'annual_interest_rate.min' => 'Interest rate cannot be negative',
            'loan_term_years.required' => 'Loan term is required',
            'loan_term_years.integer' => 'Loan term must be a whole number of years',
            'loan_term_years.min' => 'Loan term must be at least 1 year',
            'monthly_extra_payment.numeric' => 'Extra payment must be a valid number',
            'monthly_extra_payment.min' => 'Extra payment cannot be negative'
        ];
    }
}
