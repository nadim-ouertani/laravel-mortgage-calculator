<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Loan\Contracts\LoanRepositoryInterface;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\MonthlyPayment;
use App\Infrastructure\Models\LoanModel;

class EloquentLoanRepository implements LoanRepositoryInterface
{
    public function save(Loan $loan): Loan
    {
        $data = [
            'loan_amount' => $loan->getLoanAmount()->getValue(),
            'annual_interest_rate' => $loan->getInterestRate()->getAnnualRate(),
            'loan_term_years' => $loan->getLoanTerm()->getYears(),
            'monthly_extra_payment' => $loan->getExtraPayment()->getValue(),
            'calculated_monthly_payment' => $loan->getCalculatedPayment()?->getValue(),
            'effective_interest_rate' => $loan->getEffectiveInterestRate(),
        ];

        if ($loan->getId()) {
            $model = LoanModel::findOrFail($loan->getId());
            $model->update($data);
        } else {
            $model = LoanModel::create($data);
            $loan->setId($model->id);
        }

        return $loan;
    }

    public function findById(int $id): ?Loan
    {
        $model = LoanModel::find($id);
        
        if (!$model) {
            return null;
        }

        return $this->mapToDomain($model);
    }

    public function delete(int $id): bool
    {
        return LoanModel::where('id', $id)->delete() > 0;
    }

    private function mapToDomain(LoanModel $model): Loan
    {
        $loan = new Loan(
            loanAmount: new LoanAmount($model->loan_amount),
            interestRate: new InterestRate($model->annual_interest_rate),
            loanTerm: new LoanTerm($model->loan_term_years),
            extraPayment: new \App\Domain\Loan\ValueObjects\ExtraPayment($model->monthly_extra_payment),
            id: $model->id
        );

        if ($model->calculated_monthly_payment) {
            $loan->setCalculatedPayment(new MonthlyPayment($model->calculated_monthly_payment));
        }

        if ($model->effective_interest_rate) {
            $loan->setEffectiveInterestRate($model->effective_interest_rate);
        }

        return $loan;
    }
}