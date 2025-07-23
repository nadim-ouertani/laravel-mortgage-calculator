<?php

namespace App\Domain\Loan\ValueObjects;

use App\Domain\Loan\Constants\LoanValidationConstants;
use InvalidArgumentException;

readonly class InterestRate
{
    private float $annualRate;

    public function __construct(float $annualPercentage)
    {
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
        return ($this->annualRate / LoanValidationConstants::PERCENTAGE_MULTIPLIER) / LoanValidationConstants::MONTHS_PER_YEAR;
    }
}