<?php

namespace App\Application\DTOs;

class LoanCalculationRequest
{
    public function __construct(
        public readonly float $loanAmount,
        public readonly float $annualInterestRate,
        public readonly int $loanTermYears,
        public readonly float $monthlyExtraPayment = 0
    ) {}

    public function toArray(): array
    {
        return [
            'loan_amount' => $this->loanAmount,
            'annual_interest_rate' => $this->annualInterestRate,
            'loan_term_years' => $this->loanTermYears,
            'monthly_extra_payment' => $this->monthlyExtraPayment,
        ];
    }
}