<?php

namespace App\Http\Transformers;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;

class LoanTransformer
{
    public function transformLoanCalculationResponse(Loan $loan, AmortizationSchedule $standardSchedule, ?AmortizationSchedule $extraSchedule = null): array
    {
        $response = [
            'success' => true,
            'loan_id' => $loan->getId(),
            'loan_details' => [
                'id' => $loan->getId(),
                'loan_amount' => $loan->getLoanAmount()->getValue(),
                'annual_interest_rate' => $loan->getInterestRate()->getAnnualRate(),
                'loan_term_years' => $loan->getLoanTerm()->getYears(),
                'monthly_extra_payment' => $loan->getExtraPayment()->getValue(),
                'calculated_monthly_payment' => $loan->getCalculatedPayment()->getValue(),
                'effective_interest_rate' => $loan->getEffectiveInterestRate()
            ],
            'standard_schedule' => [
                'total_payments' => $standardSchedule->getTotalPayments(),
                'total_interest' => $standardSchedule->getTotalInterestPaid(),
                'total_principal' => $loan->getLoanAmount()->getValue(),
                'entries' => $this->transformSchedule($standardSchedule)
            ]
        ];

        if ($extraSchedule && $loan->hasExtraPayments()) {
            $interestSavings = $standardSchedule->getTotalInterestPaid() - $extraSchedule->getTotalInterestPaid();
            $timeSavings = $standardSchedule->getTotalPayments() - $extraSchedule->getTotalPayments();
            
            $response['extra_payment_schedule'] = [
                'total_payments' => $extraSchedule->getTotalPayments(),
                'total_interest' => $extraSchedule->getTotalInterestPaid(),
                'total_principal' => $loan->getLoanAmount()->getValue(),
                'total_extra_payments' => $extraSchedule->getTotalExtraPayments(),
                'interest_savings' => $interestSavings,
                'time_savings' => $timeSavings,
                'entries' => $this->transformSchedule($extraSchedule)
            ];
        }

        return $response;
    }

    public function transformLoanDetailsResponse(Loan $loan, AmortizationSchedule $standardSchedule, ?AmortizationSchedule $extraSchedule = null): array
    {
        return $this->transformLoanCalculationResponse($loan, $standardSchedule, $extraSchedule);
    }

    private function transformSchedule(AmortizationSchedule $schedule): array
    {
        return array_map(function ($entry) {
            return [
                'month' => $entry->getMonthNumber(),
                'starting_balance' => round($entry->getStartingBalance(), 2),
                'monthly_payment' => round($entry->getMonthlyPayment(), 2),
                'principal' => round($entry->getPrincipalComponent(), 2),
                'interest' => round($entry->getInterestComponent(), 2),
                'extra_payment' => round($entry->getExtraPayment(), 2),
                'ending_balance' => round($entry->getEndingBalance(), 2),
                'remaining_term' => $entry->getRemainingTerm(),
            ];
        }, $schedule->getEntries());
    }

}