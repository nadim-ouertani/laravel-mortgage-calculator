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

        $this->value = round($payment, 2);
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function isZero(): bool
    {
        return $this->value < 0.01;
    }
}