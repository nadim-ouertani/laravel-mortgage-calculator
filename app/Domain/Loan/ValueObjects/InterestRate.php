<?php

namespace App\Domain\Loan\ValueObjects;

use App\Domain\Loan\Constants\LoanValidationConstants;
use InvalidArgumentException;

readonly class InterestRate
{
    private float $annualRate;

    public function __construct(float $annualPercentage)
    {
        // Negative rates? Not in this economy!
        if ($annualPercentage < LoanValidationConstants::MIN_INTEREST_RATE) {
            throw new InvalidArgumentException('Interest rate cannot be negative');
        }

        $this->annualRate = round($annualPercentage, 3);
    }

    public function getAnnualRate(): float
    {
        return $this->annualRate;
    }

    public function getMonthlyRate(): float
    {
        // Convert annual % to monthly decimal rate
        return ($this->annualRate / LoanValidationConstants::PERCENTAGE_MULTIPLIER) / LoanValidationConstants::MONTHS_PER_YEAR;
    }

    public function toString(): string
    {
        return number_format($this->annualRate, 3) . '%';
    }

    public function __toString(): string
    {
        return (string) $this->annualRate;
    }

    public function equals(InterestRate $other): bool
    {
        // hardcoded tolerance - should probably use constant but meh
        return abs($this->annualRate - $other->annualRate) < 0.001;
    }
}