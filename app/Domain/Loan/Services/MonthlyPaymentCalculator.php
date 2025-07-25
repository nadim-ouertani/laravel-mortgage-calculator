<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\ValueObjects\MonthlyPayment;

class MonthlyPaymentCalculator
{
    public function calculate(Loan $loan): MonthlyPayment
    {
        $amount = $loan->getLoanAmount()->getValue();
        $rate = $loan->getInterestRate()->getMonthlyRate();
        $months = $loan->getLoanTerm()->getMonths();

        if (abs($rate) < 0.00001) {
            // no interest, just divide evenly
            $payment = $amount / $months;
        } else {
            // standard amortization formula
            $payment = ($amount * $rate) / 
                (1 - pow(1 + $rate, -$months));
        }

        return new MonthlyPayment($payment);
    }
}