<?php

namespace Tests\Unit;

use App\Domain\Loan\Factories\LoanFactory;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use PHPUnit\Framework\TestCase;

class LoanFactoryTest extends TestCase
{
    public function test_creates_loan_from_array(): void
    {
        $data = [
            'loan_amount' => 250000.0,
            'annual_interest_rate' => 4.5,
            'loan_term_years' => 30,
            'monthly_extra_payment' => 200.0
        ];

        $loan = LoanFactory::fromArray($data);

        $this->assertEquals(250000.0, $loan->getLoanAmount()->getValue());
        $this->assertEquals(4.5, $loan->getInterestRate()->getAnnualRate());
        $this->assertEquals(30, $loan->getLoanTerm()->getYears());
        $this->assertEquals(200.0, $loan->getExtraPayment()->getValue());
    }

    public function test_creates_loan_with_default_extra_payment(): void
    {
        $data = [
            'loan_amount' => 150000.0,
            'annual_interest_rate' => 3.5,
            'loan_term_years' => 25
        ];

        $loan = LoanFactory::fromArray($data);

        $this->assertEquals(0.0, $loan->getExtraPayment()->getValue());
    }

    public function test_creates_loan_from_request_data(): void
    {
        $validatedData = [
            'loan_amount' => 400000.0,
            'annual_interest_rate' => 5.0,
            'loan_term_years' => 20,
            'monthly_extra_payment' => 500.0
        ];

        $loan = LoanFactory::fromRequestData($validatedData);

        $this->assertEquals(400000.0, $loan->getLoanAmount()->getValue());
        $this->assertEquals(5.0, $loan->getInterestRate()->getAnnualRate());
        $this->assertEquals(20, $loan->getLoanTerm()->getYears());
        $this->assertEquals(500.0, $loan->getExtraPayment()->getValue());
    }

    public function test_creates_loan_with_parameters(): void
    {
        $loan = LoanFactory::create(
            loanAmount: 300000.0,
            interestRate: 6.0,
            loanTerm: 15,
            extraPayment: 1000.0
        );

        $this->assertEquals(300000.0, $loan->getLoanAmount()->getValue());
        $this->assertEquals(6.0, $loan->getInterestRate()->getAnnualRate());
        $this->assertEquals(15, $loan->getLoanTerm()->getYears());
        $this->assertEquals(1000.0, $loan->getExtraPayment()->getValue());
    }

    public function test_creates_loan_with_default_parameter(): void
    {
        $loan = LoanFactory::create(
            loanAmount: 200000.0,
            interestRate: 4.0,
            loanTerm: 30
        );

        $this->assertEquals(0.0, $loan->getExtraPayment()->getValue());
    }

    public function test_factory_creates_proper_value_objects(): void
    {
        $data = [
            'loan_amount' => 100000.0,
            'annual_interest_rate' => 5.5,
            'loan_term_years' => 10,
            'monthly_extra_payment' => 100.0
        ];

        $loan = LoanFactory::fromArray($data);

        $this->assertInstanceOf(LoanAmount::class, $loan->getLoanAmount());
        $this->assertInstanceOf(InterestRate::class, $loan->getInterestRate());
        $this->assertInstanceOf(LoanTerm::class, $loan->getLoanTerm());
        $this->assertInstanceOf(ExtraPayment::class, $loan->getExtraPayment());
    }
}