<?php

namespace App\Domain\Loan\Entities;

use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\MonthlyPayment;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use App\Domain\Loan\Services\EffectiveRateCalculator;

class Loan
{
    private ?int $id;
    private LoanAmount $loanAmount;
    private InterestRate $interestRate;
    private LoanTerm $loanTerm;
    private ExtraPayment $extraPayment;
    private ?MonthlyPayment $calculatedPayment;
    private ?float $effectiveInterestRate;

    public function __construct(
        LoanAmount $loanAmount,
        InterestRate $interestRate,
        LoanTerm $loanTerm,
        ExtraPayment $extraPayment = null,
        ?int $id = null
    ) {
        $this->id = $id;
        $this->loanAmount = $loanAmount;
        $this->interestRate = $interestRate;
        $this->loanTerm = $loanTerm;
        $this->extraPayment = $extraPayment ?? new ExtraPayment(0);
        
        // Validate extra payment against loan amount if provided
        if ($extraPayment && $extraPayment->getValue() > 0) {
            ExtraPayment::validateAgainstLoanAmount($extraPayment->getValue(), $loanAmount->getValue());
        }
        
        $this->calculatedPayment = null;
        $this->effectiveInterestRate = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('ID must be positive');
        }
        
        $this->id = $id;
    }

    public function getLoanAmount(): LoanAmount
    {
        return $this->loanAmount;
    }

    public function getInterestRate(): InterestRate
    {
        return $this->interestRate;
    }

    public function getLoanTerm(): LoanTerm
    {
        return $this->loanTerm;
    }

    public function getExtraPayment(): ExtraPayment
    {
        return $this->extraPayment;
    }

    public function getCalculatedPayment(): ?MonthlyPayment
    {
        return $this->calculatedPayment;
    }

    public function setCalculatedPayment(MonthlyPayment $payment): void
    {
        if ($payment->getValue() <= 0) {
            throw new \InvalidArgumentException('Payment must be greater than zero');
        }
        
        $this->calculatedPayment = $payment;
    }

    public function getEffectiveInterestRate(): ?float
    {
        return $this->effectiveInterestRate;
    }

    public function setEffectiveInterestRate(float $rate): void
    {
        if ($rate < 0) {
            throw new \InvalidArgumentException('Rate must be positive');
        }
        
        $this->effectiveInterestRate = $rate;
    }

    public function hasExtraPayments(): bool
    {
        return $this->extraPayment->getValue() > 0;
    }

    // Calculate effective rate using the provided calculator
    public function calculateEffectiveRate(AmortizationSchedule $schedule, EffectiveRateCalculator $calculator): void
    {
        $effectiveRate = $calculator->calc($this, $schedule);
        $this->setEffectiveInterestRate($effectiveRate);
    }
}