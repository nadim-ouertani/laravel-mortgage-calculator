<?php

namespace App\Domain\Loan\Entities;

use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;

class Loan
{
    private ?int $id;
    private LoanAmount $loanAmount;
    private InterestRate $interestRate;
    private int $termYears;
    private float $monthlyExtraPayment;
    private float $calculatedMonthlyPayment;
    private float $effectiveInterestRate;

    public function __construct(
        LoanAmount $loanAmount,
        InterestRate $interestRate,
        int $termYears,
        float $monthlyExtraPayment = 0.0
    ) {
        $this->id = null;
        $this->loanAmount = $loanAmount;
        $this->interestRate = $interestRate;
        $this->termYears = $termYears;
        $this->monthlyExtraPayment = $monthlyExtraPayment;
        $this->calculatedMonthlyPayment = 0.0;
        $this->effectiveInterestRate = 0.0;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getLoanAmount(): LoanAmount { return $this->loanAmount; }
    public function getInterestRate(): InterestRate { return $this->interestRate; }
    public function getTermYears(): int { return $this->termYears; }
    public function getMonthlyExtraPayment(): float { return $this->monthlyExtraPayment; }
    public function getCalculatedMonthlyPayment(): float { return $this->calculatedMonthlyPayment; }
    public function getEffectiveInterestRate(): float { return $this->effectiveInterestRate; }

    // Setters
    public function setId(int $id): void { $this->id = $id; }
    public function setCalculatedMonthlyPayment(float $payment): void { $this->calculatedMonthlyPayment = $payment; }
    public function setEffectiveInterestRate(float $rate): void { $this->effectiveInterestRate = $rate; }
}