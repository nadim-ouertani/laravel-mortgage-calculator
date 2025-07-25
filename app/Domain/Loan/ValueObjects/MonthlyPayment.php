<?php

namespace App\Domain\Loan\ValueObjects;

use InvalidArgumentException;

// Simple monthly payment value object
readonly class MonthlyPayment 
{
    private float $value;

    public function __construct(float $payment)
    {
        if ($payment < 0) {
            throw new InvalidArgumentException('Payment can\'t be negative');
        }

        $this->value = round($payment, 2); // standard currency rounding
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

    public function equals(MonthlyPayment $other): bool
    {
        // floating point comparison with small tolerance
        return abs($this->value - $other->value) < 0.01;
    }
}