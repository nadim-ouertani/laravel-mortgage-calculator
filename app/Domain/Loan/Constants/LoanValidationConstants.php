<?php

namespace App\Domain\Loan\Constants;

abstract class LoanValidationConstants
{
    // Loan amount limits
    public const MIN_LOAN_AMOUNT = 5000; // Bank policy minimum
    public const MAX_LOAN_AMOUNT = 4_000_000; // Updated limits
    
    // Interest rate limits
    public const MIN_INTEREST_RATE = 0.0;
    
    // Term limits
    public const MIN_LOAN_TERM_YEARS = 1;
    
    // Extra payment limits
    public const MIN_EXTRA_PAYMENT = 0.0;
    
    // Basic constants
    public const MONTHS_PER_YEAR = 12;
    public const PERCENTAGE_MULTIPLIER = 100;
    
    // Precision stuff
    public const CURRENCY_DECIMAL_PLACES = 2;
    public const RATE_DECIMAL_PLACES = 3;
}