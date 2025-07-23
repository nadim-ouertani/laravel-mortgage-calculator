<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\Constants\LoanValidationConstants;

class MonthlyPaymentCalculator
{
    public function calculate(LoanAmount $loanAmount, InterestRate $interestRate, int $termYears): float
    {
        $principal = $loanAmount->getValue();
        $monthlyRate = $interestRate->getMonthlyRate();
        $numberOfMonths = $termYears * LoanValidationConstants::MONTHS_PER_YEAR;

        // Handle zero interest rate case
        if ($monthlyRate == 0) {
            return $principal / $numberOfMonths;
        }

        // Standard mortgage payment formula
        $monthlyPayment = ($principal * $monthlyRate) / (1 - pow(1 + $monthlyRate, -$numberOfMonths));
        
        return round($monthlyPayment, 2);
    }
}