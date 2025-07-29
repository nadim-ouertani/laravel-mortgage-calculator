<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;
use App\Domain\Loan\ValueObjects\MonthlyPayment;

// loan calculations wrapper
class LoanAmortizationService
{
    public function __construct(
        private MonthlyPaymentCalculator $paymentCalculator,
        private ScheduleGenerator $scheduleGenerator,
        private StandardPaymentStrategy $standardStrategy,
        private ExtraPaymentStrategy $extraPaymentStrategy,
        private EffectiveRateCalculator $effectiveRateCalculator
    ) {}
    
    public function calculateMonthlyPayment(Loan $loan): MonthlyPayment
    {
        return $this->paymentCalculator->calculate($loan);
    }

    public function getStandardSchedule(Loan $loan): AmortizationSchedule
    {
        return $this->scheduleGenerator->generateStandard($loan, $this->standardStrategy);
    }

    public function getExtraPaymentSchedule(Loan $loan): AmortizationSchedule
    {
        return $this->scheduleGenerator->generateWithExtraPayments($loan, $this->extraPaymentStrategy);
    }

    public function getEffectiveRate(Loan $loan, AmortizationSchedule $schedule): float
    {
        return $this->effectiveRateCalculator->calc($loan, $schedule);
    }
}