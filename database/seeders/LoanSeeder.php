<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Application\Services\LoanApplicationService;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds - creates sample loan calculations for demo purposes
     */
    public function run(): void
    {
        $loanService = app(LoanApplicationService::class);

        // Sample loan 1: Standard family home loan
        $this->createSampleLoan($loanService, [
            'loan_amount' => 500000,
            'annual_interest_rate' => 3.5,
            'loan_term_years' => 25,
            'monthly_extra_payment' => 0
        ], 'Standard 25-year family home loan');

        // Sample loan 2: With extra payments
        $this->createSampleLoan($loanService, [
            'loan_amount' => 300000,
            'annual_interest_rate' => 4.5,
            'loan_term_years' => 20,
            'monthly_extra_payment' => 1000
        ], 'Accelerated loan with extra payments');

        // Sample loan 3: Luxury property
        $this->createSampleLoan($loanService, [
            'loan_amount' => 2000000,
            'annual_interest_rate' => 4.25,
            'loan_term_years' => 30,
            'monthly_extra_payment' => 5000
        ], 'Luxury property with substantial extra payments');

        $this->command->info('Created 3 sample loan calculations with amortization schedules');
    }

    private function createSampleLoan(LoanApplicationService $service, array $data, string $description): void
    {
        try {
            $result = $service->createLoanCalculation($data);
            $this->command->info("✓ {$description}: Loan ID {$result['loan']->getId()}");
        } catch (\Exception $e) {
            $this->command->error("✗ Failed to create sample loan: {$description} - {$e->getMessage()}");
        }
    }
}
