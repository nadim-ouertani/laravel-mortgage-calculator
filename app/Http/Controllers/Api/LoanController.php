<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanCalculationRequest;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Services\MonthlyPaymentCalculator;
use App\Domain\Loan\Services\AmortizationService;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    public function __construct(
        private MonthlyPaymentCalculator $paymentCalculator,
        private AmortizationService $amortizationService
    ) {}

    public function calculate(LoanCalculationRequest $request): JsonResponse
    {
        try {
            $loanAmount = new LoanAmount($request->loan_amount);
            $interestRate = new InterestRate($request->annual_interest_rate);
            $loanTerm = new LoanTerm($request->loan_term_years);
            $extraPayment = new ExtraPayment($request->monthly_extra_payment ?? 0);

            $loan = new Loan(
                $loanAmount,
                $interestRate,
                $loanTerm->getYears(),
                $extraPayment->getValue()
            );

            $monthlyPayment = $this->paymentCalculator->calculate(
                $loanAmount,
                $interestRate,
                $loanTerm->getYears()
            );

            $loan->setCalculatedMonthlyPayment($monthlyPayment);
            $loan->setEffectiveInterestRate($interestRate->getAnnualRate());

            $schedule = $this->amortizationService->generateSchedule($loan);

            return response()->json([
                'loan_details' => [
                    'loan_amount' => $loanAmount->getValue(),
                    'annual_interest_rate' => $interestRate->getAnnualRate(),
                    'loan_term_years' => $loanTerm->getYears(),
                    'monthly_extra_payment' => $extraPayment->getValue(),
                    'calculated_monthly_payment' => $monthlyPayment,
                    'effective_interest_rate' => $interestRate->getAnnualRate()
                ],
                'amortization_schedule' => $schedule
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}