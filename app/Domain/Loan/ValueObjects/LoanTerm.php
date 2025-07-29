<?php

namespace App\Domain\Loan\ValueObjects;

use InvalidArgumentException;

readonly class LoanTerm
{
    private const MONTHS_PER_YEAR = 12;
    
    private int $years;

    public function __construct(int $years)
    {
        if ($years <= 0) {
            throw new InvalidArgumentException('Loan term must be greater than zero');
        }

        $this->years = $years;
    }

    public function getYears(): int
    {
        return $this->years;
    }

    public function getMonths(): int
    {
        return $this->years * self::MONTHS_PER_YEAR; // simple math
    }

    public function toString(): string
    {
        // Handle singular/plural properly
        return $this->years . ' ' . ($this->years === 1 ? 'year' : 'years');
    }

    public function __toString(): string
    {
        return (string) $this->years;
    }

    public function equals(LoanTerm $other): bool
    {
        return $this->years === $other->years; // int comparison is exact
    }
}