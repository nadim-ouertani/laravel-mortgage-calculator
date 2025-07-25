<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Contracts\PaymentStrategyInterface;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;

class ScheduleGenerator
{
    public function generate(Loan $loan, PaymentStrategyInterface $strategy): AmortizationSchedule
    {
        return $strategy->buildSchedule($loan);
    }

    public function generateStandard(Loan $loan, StandardPaymentStrategy $strategy): AmortizationSchedule
    {
        return $this->generate($loan, $strategy);
    }

    public function generateWithExtraPayments(Loan $loan, ExtraPaymentStrategy $strategy): AmortizationSchedule
    {
        return $this->generate($loan, $strategy);
    }
}