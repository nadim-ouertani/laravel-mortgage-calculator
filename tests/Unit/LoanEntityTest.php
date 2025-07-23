<?php

namespace Tests\Unit;

use App\Domain\Loan\Entities\Loan;
use App\Domain\Loan\ValueObjects\LoanAmount;
use App\Domain\Loan\ValueObjects\InterestRate;
use App\Domain\Loan\ValueObjects\LoanTerm;
use App\Domain\Loan\ValueObjects\MonthlyPayment;
use App\Domain\Loan\ValueObjects\ExtraPayment;
use PHPUnit\Framework\TestCase;

class LoanEntityTest extends TestCase
{
    public function test_create_loan(): void
    {
        $loanAmount = new LoanAmount(250000);
        $interestRate = new InterestRate(6.5);
        $loanTerm = new LoanTerm(30);
        $extraPayment = new ExtraPayment(300.0);

        $loan = new Loan($loanAmount, $interestRate, $loanTerm, $extraPayment);

        $this->assertEquals($loanAmount, $loan->getLoanAmount());
        $this->assertEquals($interestRate, $loan->getInterestRate());
        $this->assertEquals($loanTerm, $loan->getLoanTerm());
        $this->assertEquals($extraPayment, $loan->getExtraPayment());
    }

    public function test_create_without_extra(): void
    {
        $loanAmount = new LoanAmount(200000);
        $interestRate = new InterestRate(5.0);
        $loanTerm = new LoanTerm(25);

        $loan = new Loan($loanAmount, $interestRate, $loanTerm);

        $this->assertEquals($loanAmount, $loan->getLoanAmount());
        $this->assertEquals($interestRate, $loan->getInterestRate());
        $this->assertEquals($loanTerm, $loan->getLoanTerm());
        $this->assertEquals(0.0, $loan->getExtraPayment()->getValue());
    }

    public function test_zero_extra(): void
    {
        $loanAmount = new LoanAmount(300000);
        $interestRate = new InterestRate(4.5);
        $loanTerm = new LoanTerm(20);
        $extraPayment = new ExtraPayment(0.0);

        $loan = new Loan($loanAmount, $interestRate, $loanTerm, $extraPayment);

        $this->assertEquals(0.0, $loan->getExtraPayment()->getValue());
    }


    public function test_has_extra_payment(): void
    {
        $loanAmount = new LoanAmount(250000);
        $interestRate = new InterestRate(6.0);
        $loanTerm = new LoanTerm(30);

        $loan1 = new Loan($loanAmount, $interestRate, $loanTerm);
        $this->assertFalse($loan1->hasExtraPayments());

        $loan2 = new Loan($loanAmount, $interestRate, $loanTerm, new ExtraPayment(0.0));
        $this->assertFalse($loan2->hasExtraPayments());

        $loan3 = new Loan($loanAmount, $interestRate, $loanTerm, new ExtraPayment(500.0));
        $this->assertTrue($loan3->hasExtraPayments());
    }




    public function test_getters(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30),
            new ExtraPayment(300.0)
        );

        $this->assertInstanceOf(LoanAmount::class, $loan->getLoanAmount());
        $this->assertInstanceOf(InterestRate::class, $loan->getInterestRate());
        $this->assertInstanceOf(LoanTerm::class, $loan->getLoanTerm());
        $this->assertInstanceOf(ExtraPayment::class, $loan->getExtraPayment());
        $this->assertIsBool($loan->hasExtraPayments());
    }


    public function test_with_id(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30),
            new ExtraPayment(300.0),
            123
        );

        $this->assertEquals(123, $loan->getId());
    }

    public function test_set_id(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30)
        );

        $this->assertNull($loan->getId());
        
        $loan->setId(456);
        $this->assertEquals(456, $loan->getId());
    }

    public function test_calculated_payment(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30)
        );

        $this->assertNull($loan->getCalculatedPayment());
        
        $calculatedPayment = new MonthlyPayment(1580.17);
        $loan->setCalculatedPayment($calculatedPayment);
        
        $this->assertEquals($calculatedPayment, $loan->getCalculatedPayment());
        $this->assertEquals(1580.17, $loan->getCalculatedPayment()->getValue());
    }

    public function test_effective_rate(): void
    {
        $loan = new Loan(
            new LoanAmount(250000),
            new InterestRate(6.5),
            new LoanTerm(30)
        );

        $this->assertNull($loan->getEffectiveInterestRate());
        
        $loan->setEffectiveInterestRate(4.25);
        
        $this->assertEquals(4.25, $loan->getEffectiveInterestRate());
    }
}