<?php

namespace Tests\Unit;

use App\Http\Transformers\LoanTransformer;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\Entities\AmortizationSchedule;
use App\Domain\Loan\Entities\PaymentScheduleEntry;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use App\Domain\Loan\ValueObjects\MonthlyPayment;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class LoanTransformerTest extends TestCase
{
    private LoanTransformer $transformer;
    private Loan $sampleLoan;
    private AmortizationSchedule $sampleSchedule;

    protected function setUp(): void
    {
        $this->transformer = new LoanTransformer();
        
        $this->sampleLoan = new Loan(
            new LoanAmount(100000),
            new InterestRate(5.0),
            new LoanTerm(10),
            new ExtraPayment(200),
            1
        );
        $this->sampleLoan->setCalculatedPayment(new MonthlyPayment(1060.66));
        $this->sampleLoan->setEffectiveInterestRate(4.2);

        $this->sampleSchedule = new AmortizationSchedule(1);
        $this->sampleSchedule->addEntry(new PaymentScheduleEntry(
            monthNumber: 1,
            startingBalance: 100000,
            monthlyPayment: 1060.66,
            principalComponent: 643.99,
            interestComponent: 416.67,
            endingBalance: 99356.01
        ));
    }

    public function test_transforms_loan_calculation_response(): void
    {
        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => null
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );

        $this->assertTrue($response['success']);
        $this->assertEquals(1, $response['loan_id']);
        $this->assertArrayHasKey('loan_details', $response);
        $this->assertArrayHasKey('standard_schedule', $response);
        $this->assertArrayNotHasKey('extra_payment_schedule', $response);
    }

    public function test_transforms_loan_calculation_response_with_extra_payments(): void
    {
        $extraSchedule = new AmortizationSchedule(1);
        $extraSchedule->addEntry(new PaymentScheduleEntry(
            monthNumber: 1,
            startingBalance: 100000,
            monthlyPayment: 1060.66,
            principalComponent: 643.99,
            interestComponent: 416.67,
            endingBalance: 99156.01,
            extraPayment: 200,
            remainingTerm: 115
        ));

        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => $extraSchedule
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('extra_payment_schedule', $response);
        $this->assertArrayHasKey('interest_savings', $response['extra_payment_schedule']);
        $this->assertArrayHasKey('time_savings', $response['extra_payment_schedule']);
    }

    public function test_transforms_loan_details_response(): void
    {
        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => null
        ];

        $response = $this->transformer->transformLoanDetailsResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );

        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('loan_details', $response);
        $this->assertEquals(1, $response['loan_details']['id']);
        $this->assertEquals(100000, $response['loan_details']['loan_amount']);
    }

    public function test_loan_details_structure(): void
    {
        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => null
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );
        $loanDetails = $response['loan_details'];

        $expectedKeys = [
            'loan_amount',
            'annual_interest_rate', 
            'loan_term_years',
            'monthly_extra_payment',
            'calculated_monthly_payment',
            'effective_interest_rate'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $loanDetails);
        }

        $this->assertEquals(100000, $loanDetails['loan_amount']);
        $this->assertEquals(5.0, $loanDetails['annual_interest_rate']);
        $this->assertEquals(10, $loanDetails['loan_term_years']);
        $this->assertEquals(200, $loanDetails['monthly_extra_payment']);
    }

    public function test_schedule_structure(): void
    {
        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => null
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );
        $schedule = $response['standard_schedule'];

        $expectedKeys = [
            'total_payments',
            'total_interest',
            'total_principal',
            'entries'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $schedule);
        }

        $this->assertIsArray($schedule['entries']);
        $this->assertCount(1, $schedule['entries']);
    }

    public function test_schedule_entry_structure(): void
    {
        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => null
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );
        $entry = $response['standard_schedule']['entries'][0];

        $expectedKeys = [
            'month',
            'starting_balance',
            'monthly_payment',
            'principal',
            'interest',
            'ending_balance'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $entry);
        }

        $this->assertEquals(1, $entry['month']);
        $this->assertEquals(100000, $entry['starting_balance']);
    }

    public function test_extra_payment_entry_structure(): void
    {
        $extraSchedule = new AmortizationSchedule(1);
        $extraSchedule->addEntry(new PaymentScheduleEntry(
            monthNumber: 1,
            startingBalance: 100000,
            monthlyPayment: 1060.66,
            principalComponent: 643.99,
            interestComponent: 416.67,
            endingBalance: 99156.01,
            extraPayment: 200,
            remainingTerm: 115
        ));

        $result = [
            'loan' => $this->sampleLoan,
            'standard_schedule' => $this->sampleSchedule,
            'extra_payment_schedule' => $extraSchedule
        ];

        $response = $this->transformer->transformLoanCalculationResponse(
            $result['loan'],
            $result['standard_schedule'],
            $result['extra_payment_schedule']
        );
        $entry = $response['extra_payment_schedule']['entries'][0];

        $expectedKeys = [
            'month',
            'starting_balance',
            'monthly_payment',
            'principal',
            'interest',
            'extra_payment',
            'ending_balance',
            'remaining_term'
        ];

        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $entry);
        }

        $this->assertEquals(200, $entry['extra_payment']);
        $this->assertEquals(115, $entry['remaining_term']);
    }
}