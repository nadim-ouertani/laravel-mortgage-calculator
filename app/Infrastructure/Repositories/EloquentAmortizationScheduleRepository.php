<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Loan\Contracts\AmortizationScheduleRepositoryInterface;
use App\Domain\Loan\Entities\AmortizationSchedule;
use App\Domain\Loan\Entities\PaymentScheduleEntry;
use App\Infrastructure\Models\AmortizationScheduleModel;
use App\Infrastructure\Models\ExtraRepaymentScheduleModel;

class EloquentAmortizationScheduleRepository implements AmortizationScheduleRepositoryInterface
{
    public function saveStandardSchedule(AmortizationSchedule $schedule): void
    {
        // Clear existing entries
        AmortizationScheduleModel::where('loan_id', $schedule->getLoanId())->delete();

        // Insert new entries
        $entries = array_map(function (PaymentScheduleEntry $entry) use ($schedule) {
            return [
                'loan_id' => $schedule->getLoanId(),
                'month_number' => $entry->getMonthNumber(),
                'starting_balance' => $entry->getStartingBalance(),
                'monthly_payment' => $entry->getMonthlyPayment(),
                'principal_component' => $entry->getPrincipalComponent(),
                'interest_component' => $entry->getInterestComponent(),
                'ending_balance' => $entry->getEndingBalance(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $schedule->getEntries());

        if (!empty($entries)) {
            AmortizationScheduleModel::insert($entries);
        }
    }

    public function saveExtraPaymentSchedule(AmortizationSchedule $schedule): void
    {
        // Clear existing entries
        ExtraRepaymentScheduleModel::where('loan_id', $schedule->getLoanId())->delete();

        // Insert new entries
        $entries = array_map(function (PaymentScheduleEntry $entry) use ($schedule) {
            return [
                'loan_id' => $schedule->getLoanId(),
                'month_number' => $entry->getMonthNumber(),
                'starting_balance' => $entry->getStartingBalance(),
                'monthly_payment' => $entry->getMonthlyPayment(),
                'principal_component' => $entry->getPrincipalComponent(),
                'interest_component' => $entry->getInterestComponent(),
                'extra_repayment' => $entry->getExtraPayment(),
                'ending_balance' => $entry->getEndingBalance(),
                'remaining_term' => $entry->getRemainingTerm(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $schedule->getEntries());

        if (!empty($entries)) {
            ExtraRepaymentScheduleModel::insert($entries);
        }
    }

    public function getStandardSchedule(int $loanId): ?AmortizationSchedule
    {
        $models = AmortizationScheduleModel::where('loan_id', $loanId)
            ->orderBy('month_number')
            ->get();

        if ($models->isEmpty()) {
            return null;
        }

        $schedule = new AmortizationSchedule($loanId);

        foreach ($models as $model) {
            $entry = new PaymentScheduleEntry(
                monthNumber: $model->month_number,
                startingBalance: $model->starting_balance,
                monthlyPayment: $model->monthly_payment,
                principalComponent: $model->principal_component,
                interestComponent: $model->interest_component,
                endingBalance: $model->ending_balance
            );

            $schedule->addEntry($entry);
        }

        return $schedule;
    }

    public function getExtraPaymentSchedule(int $loanId): ?AmortizationSchedule
    {
        $models = ExtraRepaymentScheduleModel::where('loan_id', $loanId)
            ->orderBy('month_number')
            ->get();

        if ($models->isEmpty()) {
            return null;
        }

        $schedule = new AmortizationSchedule($loanId);

        foreach ($models as $model) {
            $entry = new PaymentScheduleEntry(
                monthNumber: $model->month_number,
                startingBalance: $model->starting_balance,
                monthlyPayment: $model->monthly_payment,
                principalComponent: $model->principal_component,
                interestComponent: $model->interest_component,
                endingBalance: $model->ending_balance,
                extraPayment: $model->extra_repayment,
                remainingTerm: $model->remaining_term
            );

            $schedule->addEntry($entry);
        }

        return $schedule;
    }

    public function deleteSchedules(int $loanId): void
    {
        AmortizationScheduleModel::where('loan_id', $loanId)->delete();
        ExtraRepaymentScheduleModel::where('loan_id', $loanId)->delete();
    }
}