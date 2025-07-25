<?php

namespace App\Domain\Loan\ValueObjects;

use App\Domain\Loan\Constants\LoanValidationConstants;
use InvalidArgumentException;

readonly class LoanAmount
{
    private float $value;

    public function __construct(float $amount)
    {
        if ($amount < LoanValidationConstants::MIN_LOAN_AMOUNT) {
            throw new InvalidArgumentException('Loan amount too small');
        }

        if ($amount > LoanValidationConstants::MAX_LOAN_AMOUNT) {
            throw new InvalidArgumentException('Loan amount too large');
        }

        $this->value = round($amount, 2);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function toString(): string
    {
        return number_format($this->value, LoanValidationConstants::CURRENCY_DECIMAL_PLACES);
    }

    public function __toString(): string 
    {
        return (string) $this->value;
    }

    // Floating point comparison - close enough is good enough
    public function equals(LoanAmount $other): bool
    {
        return abs($this->value - $other->value) < LoanValidationConstants::DEFAULT_TOLERANCE;
    }
}