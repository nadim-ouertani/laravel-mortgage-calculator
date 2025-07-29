<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Contracts\PaymentStrategyInterface;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;
use App\Domain\Loan\Entities\PaymentScheduleEntry;

class ExtraPaymentStrategy implements PaymentStrategyInterface
{
    private const MAX_MONTHS = 999;

    public function __construct(
        private MonthlyPaymentCalculator $paymentCalculator
    ) {}

    public function buildSchedule(Loan $loan): AmortizationSchedule
    {
        $schedule = new AmortizationSchedule($loan->getId() ?? 0);
        
        $balance = $loan->getLoanAmount()->getValue();
        $payment = $this->paymentCalculator->calculate($loan)->getValue();
        $extra = $loan->getExtraPayment()->getValue();
        $rate = $loan->getInterestRate()->getMonthlyRate();
        $maxMonths = $loan->getLoanTerm()->getMonths();
        
        $month = 1;
        
        // extra safety check to prevent infinite loops
        while ($balance > 0 && $month <= $maxMonths * 2) {
            $interest = $balance * $rate;
            $principal = $payment - $interest;
            
            $afterRegular = $balance - $principal;
            
            // apply extra payment
            $actualExtra = min($extra, max(0, $afterRegular));
            $newBalance = $afterRegular - $actualExtra;
            
            if ($newBalance < 0.01) {
                $actualExtra = $afterRegular;
                $newBalance = 0;
            }
            
            $remaining = $this->getRemainingMonths($newBalance, $rate, $payment);

            $entry = new PaymentScheduleEntry(
                monthNumber: $month,
                startingBalance: $balance,
                monthlyPayment: $payment,
                principalComponent: $principal,
                interestComponent: $interest,
                endingBalance: $newBalance,
                extraPayment: $actualExtra,
                remainingTerm: $remaining
            );

            $schedule->addEntry($entry);
            $balance = $newBalance;
            $month++;

            if ($balance <= 0) {
                break;
            }
        }

        return $schedule;
    }

    private function getRemainingMonths(float $balance, float $rate, float $payment): int
    {
        if ($balance <= 0 || $payment <= 0) {
            return 0;
        }

        // no interest case
        if (abs($rate) < 0.00001) {
            return (int) ceil($balance / $payment);
        }

        if ($payment <= $balance * $rate) {
            return self::MAX_MONTHS;
        }

        $months = -log(1 - ($balance * $rate) / $payment) / log(1 + $rate);
        
        return (int) ceil($months);
    }
}