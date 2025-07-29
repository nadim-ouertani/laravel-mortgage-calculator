<?php

namespace App\Domain\Loan\Contracts;

use App\Domain\Loan\Entities\AmortizationSchedule;

interface AmortizationScheduleRepositoryInterface
{
    public function saveStandardSchedule(AmortizationSchedule $schedule): void;
    
    public function saveExtraPaymentSchedule(AmortizationSchedule $schedule): void;
    
    public function getStandardSchedule(int $loanId): ?AmortizationSchedule;
    
    public function getExtraPaymentSchedule(int $loanId): ?AmortizationSchedule;
    
    public function deleteSchedules(int $loanId): void;
}