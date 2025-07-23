<?php

namespace App\Domain\Loan\ValueObjects;

use App\Domain\Loan\Constants\LoanValidationConstants;
use InvalidArgumentException;

readonly class LoanTerm
{
    private int $years;

    public function __construct(int $years)
    {
        if ($years < LoanValidationConstants::MIN_LOAN_TERM_YEARS) {
            throw new InvalidArgumentException('Loan term must be at least 1 year');
        }

        $this->years = $years;
    }

    public function getYears(): int
    {
        return $this->years;
    }

    public function getMonths(): int
    {
        return $this->years * LoanValidationConstants::MONTHS_PER_YEAR;
    }
}