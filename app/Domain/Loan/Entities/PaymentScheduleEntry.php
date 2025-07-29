<?php

namespace App\Domain\Loan\Entities;

class PaymentScheduleEntry
{
    private int $monthNumber;
    private float $startingBalance;
    private float $monthlyPayment;
    private float $principalComponent;
    private float $interestComponent;
    private float $endingBalance;
    private float $extraPayment;
    private ?int $remainingTerm;

    public function __construct(
        int $monthNumber,
        float $startingBalance,
        float $monthlyPayment,
        float $principalComponent,
        float $interestComponent,
        float $endingBalance,
        float $extraPayment = 0,
        ?int $remainingTerm = null
    ) {
        $this->monthNumber = $monthNumber;
        $this->startingBalance = round($startingBalance, 2);
        $this->monthlyPayment = round($monthlyPayment, 2);
        $this->principalComponent = round($principalComponent, 2);
        $this->interestComponent = round($interestComponent, 2);
        $this->endingBalance = round($endingBalance, 2);
        $this->extraPayment = round($extraPayment, 2);
        $this->remainingTerm = $remainingTerm;
    }

    public function getMonthNumber(): int
    {
        return $this->monthNumber;
    }

    public function getStartingBalance(): float
    {
        return $this->startingBalance;
    }

    public function getMonthlyPayment(): float
    {
        return $this->monthlyPayment;
    }

    public function getPrincipalComponent(): float
    {
        return $this->principalComponent;
    }

    public function getInterestComponent(): float
    {
        return $this->interestComponent;
    }

    public function getEndingBalance(): float
    {
        return $this->endingBalance;
    }

    public function getExtraPayment(): float
    {
        return $this->extraPayment;
    }

    public function getRemainingTerm(): ?int
    {
        return $this->remainingTerm;
    }

    public function getTotalPayment(): float
    {
        return $this->monthlyPayment + $this->extraPayment;
    }
}