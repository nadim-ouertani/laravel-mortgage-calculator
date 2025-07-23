<?php

namespace App\Domain\Loan\Constants;

class LoanValidationConstants
{
    // Basic validation constants
    public const MIN_LOAN_AMOUNT = 5000.0;
    public const MIN_INTEREST_RATE = 0.0;
    public const MIN_LOAN_TERM_YEARS = 1;
    
    // Calculation constants
    public const MONTHS_PER_YEAR = 12;
    public const PERCENTAGE_MULTIPLIER = 100;
}