<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;

class EffectiveRateCalculator
{
    public function calc(Loan $loan, AmortizationSchedule $schedule): float
    {
        if ($schedule->isEmpty()) {
            return $loan->getInterestRate()->getAnnualRate();
        }

        $totalInt = $schedule->getTotalInterestPaid();
        $amt = $loan->getLoanAmount()->getValue();
        $months = $schedule->getActualTerm();
        
        if ($months == 0) {
            return $loan->getInterestRate()->getAnnualRate();
        }

        // simple conversion to annual %
        $monthlyRate = ($totalInt / $amt) / $months;
        $result = $monthlyRate * 12 * 100;

        return round($result, 3);
    }
}