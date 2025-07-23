<?php

namespace Tests\Unit;

use App\Domain\Loan\Services\LoanAmortizationService;
use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use Tests\TestCase;

class LoanAmortizationServiceTest extends TestCase
{
    private LoanAmortizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(LoanAmortizationService::class);
    }

    public function test_monthly_payment(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);

        $this->assertEquals(1580.17, round($monthlyPayment->getValue(), 2));
    }

    public function test_zero_interest(): void
    {
        $loan = new Loan(
            new LoanAmount(120000),
            new InterestRate(0),
            new LoanTerm(10)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);

        $this->assertEquals(1000, $monthlyPayment->getValue());
    }

    public function test_high_interest(): void
    {
        $loan = new Loan(
            new LoanAmount(100000),
            new InterestRate(25.0),
            new LoanTerm(15)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);

        $this->assertEquals(2135.53, round($monthlyPayment->getValue(), 2));
    }

    public function test_short_term(): void
    {
        $loan = new Loan(
            new LoanAmount(100000),
            new InterestRate(5.0),
            new LoanTerm(5)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);
        $this->assertEquals(1887.12, round($monthlyPayment->getValue(), 2));
    }

    public function test_long_term(): void
    {
        $loan = new Loan(
            new LoanAmount(300000),
            new InterestRate(4.0),
            new LoanTerm(40)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);
        $this->assertEquals(1253.82, round($monthlyPayment->getValue(), 2));
    }

    public function test_known_values(): void
    {
        $loan1 = new Loan(
            new LoanAmount(200000),
            new InterestRate(3.5),
            new LoanTerm(30)
        );
        $monthlyPayment1 = $this->service->calculateMonthlyPayment($loan1);
        $this->assertEquals(898.09, round($monthlyPayment1->getValue(), 2));

        $loan2 = new Loan(
            new LoanAmount(500000),
            new InterestRate(4.25),
            new LoanTerm(15)
        );
        $monthlyPayment2 = $this->service->calculateMonthlyPayment($loan2);
        $this->assertEquals(3761.39, round($monthlyPayment2->getValue(), 2));
    }

    public function test_standard_schedule(): void
    {
        $loan = new Loan(
            new LoanAmount(100000),
            new InterestRate(5.0),
            new LoanTerm(10)
        );

        $schedule = $this->service->getStandardSchedule($loan);

        $this->assertEquals(120, count($schedule->getEntries()));
    }

    public function test_extra_payment_schedule(): void
    {
        $loan = new Loan(
            new LoanAmount(100000),
            new InterestRate(5.0),
            new LoanTerm(10),
            new ExtraPayment(200)
        );

        $schedule = $this->service->getExtraPaymentSchedule($loan);

        $this->assertLessThan(120, count($schedule->getEntries()));
    }

    public function test_effective_rate(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30),
            new ExtraPayment(300)
        );

        $schedule = $this->service->getExtraPaymentSchedule($loan);
        $effectiveRate = $this->service->getEffectiveRate($loan, $schedule);

        $this->assertGreaterThan(0, $effectiveRate);
        $this->assertLessThan($loan->getInterestRate()->getAnnualRate(), $effectiveRate);
    }

    public function test_small_loan(): void
    {
        $loan = new Loan(
            new LoanAmount(5000),
            new InterestRate(0.001),
            new LoanTerm(1)
        );

        $monthlyPayment = $this->service->calculateMonthlyPayment($loan);
        $this->assertLessThan(450, $monthlyPayment->getValue());
    }



    public function test_final_balance(): void
    {
        $loan = new Loan(
            new LoanAmount(10000),
            new InterestRate(12.0),
            new LoanTerm(1)
        );

        $schedule = $this->service->getStandardSchedule($loan);
        $entries = $schedule->getEntries();
        $lastEntry = end($entries);

        $this->assertEquals(0, $lastEntry->getEndingBalance());
    }

    public function test_large_extra_payment(): void
    {
        $loan = new Loan(
            new LoanAmount(100000),
            new InterestRate(5.0),
            new LoanTerm(10),
            new ExtraPayment(5000)
        );

        $schedule = $this->service->getExtraPaymentSchedule($loan);
        
        $this->assertLessThan(120, $schedule->getActualTerm());
        $this->assertEquals(0, $schedule->last()->getEndingBalance());
    }






}