<?php

namespace App\Application\Services;

use App\Domain\Loan\Contracts\LoanRepositoryInterface;
use App\Domain\Loan\Contracts\AmortizationScheduleRepositoryInterface;
use App\Domain\Loan\Services\LoanAmortizationService;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Factories\LoanFactory;

class LoanApplicationService
{
    public function __construct(
        private LoanRepositoryInterface $loanRepository,
        private AmortizationScheduleRepositoryInterface $scheduleRepository,
        private LoanAmortizationService $calculationService
    ) {}

    public function createLoanCalculation(array $data): array
    {
        $loan = LoanFactory::fromRequestData($data);
        
        $monthlyPayment = $this->calculationService->calculateMonthlyPayment($loan);
        $loan->setCalculatedPayment($monthlyPayment);
        $loan = $this->loanRepository->save($loan);
        
        $standardSchedule = $this->calculationService->getStandardSchedule($loan);
        $this->scheduleRepository->saveStandardSchedule($standardSchedule);
        
        $extraSchedule = null;
        if ($loan->hasExtraPayments()) {
            $extraSchedule = $this->calculationService->getExtraPaymentSchedule($loan);
            $this->scheduleRepository->saveExtraPaymentSchedule($extraSchedule);
        }
        
        $effectiveRateCalculator = app(\App\Domain\Loan\Services\EffectiveRateCalculator::class);
        $scheduleForRate = $extraSchedule ?? $standardSchedule;
        $loan->calculateEffectiveRate($scheduleForRate, $effectiveRateCalculator);
        $this->loanRepository->save($loan);

        return [
            'loan' => $loan,
            'standard_schedule' => $standardSchedule,
            'extra_payment_schedule' => $extraSchedule,
        ];
    }

    public function getLoanWithSchedules(int $loanId): array
    {
        $loan = $this->loanRepository->findById($loanId);
        
        if (!$loan) {
            throw new \InvalidArgumentException('Loan not found');
        }

        $standardSchedule = $this->scheduleRepository->getStandardSchedule($loanId);
        $extraSchedule = $this->scheduleRepository->getExtraPaymentSchedule($loanId);

        return [
            'loan' => $loan,
            'standard_schedule' => $standardSchedule,
            'extra_payment_schedule' => $extraSchedule,
        ];
    }

    public function deleteLoan(int $loanId): bool
    {
        $this->scheduleRepository->deleteSchedules($loanId);
        return $this->loanRepository->delete($loanId);
    }
}