<?php

namespace App\Domain\Loan\Factories;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\ExtraPayment;

class LoanFactory
{
    /**
     * Create loan from array data
     */
    public static function fromArray(array $data): Loan
    {
        $extraPaymentAmount = $data['monthly_extra_payment'] ?? 0.0;
        
        // Validate extra payment against loan amount if provided
        if ($extraPaymentAmount > 0) {
            ExtraPayment::validateAgainstLoanAmount($extraPaymentAmount, $data['loan_amount']);
        }
        
        return new Loan(
            loanAmount: new LoanAmount($data['loan_amount']),
            interestRate: new InterestRate($data['annual_interest_rate']),
            loanTerm: new LoanTerm($data['loan_term_years']),
            extraPayment: new ExtraPayment($extraPaymentAmount)
        );
    }

    /**
     * Create loan from request data
     */
    public static function fromRequestData(array $requestData): Loan
    {
        $extraPaymentAmount = $requestData['monthly_extra_payment'] ?? 0.0;
        
        // Validate extra payment against loan amount if provided
        if ($extraPaymentAmount > 0) {
            ExtraPayment::validateAgainstLoanAmount($extraPaymentAmount, $requestData['loan_amount']);
        }
        
        return new Loan(
            loanAmount: new LoanAmount($requestData['loan_amount']),
            interestRate: new InterestRate($requestData['annual_interest_rate']),
            loanTerm: new LoanTerm($requestData['loan_term_years']),
            extraPayment: new ExtraPayment($extraPaymentAmount)
        );
    }

    /**
     * Create loan with individual parameters
     */
    public static function create(
        float $loanAmount,
        float $interestRate,
        int $loanTerm,
        float $extraPayment = 0.0
    ): Loan {
        // Validate extra payment against loan amount if provided
        if ($extraPayment > 0) {
            ExtraPayment::validateAgainstLoanAmount($extraPayment, $loanAmount);
        }
        
        return new Loan(
            loanAmount: new LoanAmount($loanAmount),
            interestRate: new InterestRate($interestRate),
            loanTerm: new LoanTerm($loanTerm),
            extraPayment: new ExtraPayment($extraPayment)
        );
    }
}