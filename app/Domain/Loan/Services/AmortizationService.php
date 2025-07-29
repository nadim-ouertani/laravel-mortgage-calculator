<?php

namespace App\Domain\Loan\Services;

use App\Domain\Loan\Entities\Loan;

class AmortizationService
{
    public function generateSchedule(Loan $loan): array
    {
        $schedule = [];
        $balance = $loan->getLoanAmount()->getValue();
        $monthlyPayment = $loan->getCalculatedMonthlyPayment();
        $monthlyRate = $loan->getInterestRate()->getMonthlyRate();
        $totalMonths = $loan->getTermYears() * 12;

        for ($month = 1; $month <= $totalMonths && $balance > 0.01; $month++) {
            $interestPayment = $balance * $monthlyRate;
            $principalPayment = $monthlyPayment - $interestPayment;
            
            if ($principalPayment > $balance) {
                $principalPayment = $balance;
                $monthlyPayment = $principalPayment + $interestPayment;
            }
            
            $newBalance = $balance - $principalPayment;
            
            $schedule[] = [
                'month_number' => $month,
                'starting_balance' => round($balance, 2),
                'monthly_payment' => round($monthlyPayment, 2),
                'principal_component' => round($principalPayment, 2),
                'interest_component' => round($interestPayment, 2),
                'ending_balance' => round($newBalance, 2)
            ];
            
            $balance = $newBalance;
        }
        
        return $schedule;
    }
}