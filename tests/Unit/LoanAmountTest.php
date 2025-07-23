<?php

namespace Tests\Unit;

use App\Domain\Loan\ValueObjects\LoanAmount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoanAmountTest extends TestCase
{
    public function test_creates_amount(): void
    {
        $amount = new LoanAmount(250000);
        $this->assertEquals(250000, $amount->getValue());
    }

    public function test_throws_exception_for_zero_loan_amount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Loan amount too small');
        
        new LoanAmount(0);
    }

    public function test_throws_exception_for_negative_loan_amount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Loan amount too small');
        
        new LoanAmount(-1000);
    }

    public function test_throws_exception_for_excessive_loan_amount(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Loan amount too large');
        
        new LoanAmount(4000001);
    }

    public function test_max_amount(): void
    {
        $amount = new LoanAmount(4000000);
        $this->assertEquals(4000000, $amount->getValue());
    }

    public function test_min_amount(): void
    {
        $amount = new LoanAmount(5000);
        $this->assertEquals(5000, $amount->getValue());
    }

    public function test_decimal_amount(): void
    {
        $amount = new LoanAmount(250000.50);
        $this->assertEquals(250000.50, $amount->getValue());
    }


    public function test_to_string(): void
    {
        $amount = new LoanAmount(250000);
        $this->assertEquals('250000', (string) $amount);
    }
}