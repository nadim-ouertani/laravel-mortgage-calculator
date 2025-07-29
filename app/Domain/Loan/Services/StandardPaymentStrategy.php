<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Contracts\PaymentStrategyInterface;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;
use App\Domain\Loan\Entities\PaymentScheduleEntry;

class StandardPaymentStrategy implements PaymentStrategyInterface
{
    public function __construct(
        private MonthlyPaymentCalculator $paymentCalculator
    ) {}

    public function buildSchedule(Loan $loan): AmortizationSchedule
    {
        $schedule = new AmortizationSchedule($loan->getId() ?? 0);
        
        $balance = $loan->getLoanAmount()->getValue();
        $payment = $this->paymentCalculator->calculate($loan)->getValue();
        $rate = $loan->getInterestRate()->getMonthlyRate();
        $months = $loan->getLoanTerm()->getMonths();

        for($month = 1; $month <= $months; $month++) {
            $interest = $balance * $rate;
            $principal = $payment - $interest;
            
            // final payment adjustment
            if($month == $months || $principal > $balance) {
                $principal = $balance;
                $payment = $principal + $interest;
            }

            $newBalance = $balance - $principal;

            $entry = new PaymentScheduleEntry(
                monthNumber: $month,
                startingBalance: $balance,
                monthlyPayment: $payment,
                principalComponent: $principal,
                interestComponent: $interest,
                endingBalance: $newBalance
            );

            $schedule->addEntry($entry);
            $balance = $newBalance;

            if($balance <= 0) {
                break;
            }
        }

        return $schedule;
    }
}