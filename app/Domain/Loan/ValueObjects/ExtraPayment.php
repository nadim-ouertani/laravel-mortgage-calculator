<?php

namespace App\Domain\Loan\ValueObjects;

use InvalidArgumentException;

readonly class ExtraPayment
{
    private float $value;

    public function __construct(float $payment)
    {
        if ($payment < 0) {
            throw new InvalidArgumentException('Extra payment must be zero or positive');
        }

        // Round to 2 decimal places for currency
        $this->value = round($payment, 2);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function toString(): string
    {
        return number_format($this->value, 2);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public function equals(ExtraPayment $other): bool
    {
        return abs($this->value - $other->value) < 0.01;
    }

    // Check if essentially zero
    public function isZero(): bool
    {
        return $this->value < 0.01;
    }

    // Banks don't like when you pay more than the loan itself
    public static function validateAgainstLoanAmount(float $extraPayment, float $loanAmount): void
    {
        if ($extraPayment >= $loanAmount) {
            throw new InvalidArgumentException('Extra payment cannot be more than the loan amount');
        }
    }
}