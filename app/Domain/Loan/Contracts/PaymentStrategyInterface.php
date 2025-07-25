<?php

namespace App\Domain\Loan\Contracts;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;

interface PaymentStrategyInterface
{
    public function buildSchedule(Loan $loan): AmortizationSchedule;
}