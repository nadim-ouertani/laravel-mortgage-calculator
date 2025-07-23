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
            throw new InvalidArgumentException('Loan amount must be at least AED ' . number_format(LoanValidationConstants::MIN_LOAN_AMOUNT));
        }

        $this->value = round($amount, 2);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function toString(): string
    {
        return number_format($this->value, 2);
    }
}