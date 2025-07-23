<?php

namespace Tests\Unit;

use App\Domain\Loan\ValueObjects\LoanTerm;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class LoanTermTest extends TestCase
{
    public function test_creates_term(): void
    {
        $term = new LoanTerm(30);
        $this->assertEquals(30, $term->getYears());
        $this->assertEquals(360, $term->getMonths());
    }

    public function test_throws_exception_for_zero_loan_term(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Loan term must be greater than zero');
        
        new LoanTerm(0);
    }

    public function test_throws_exception_for_negative_loan_term(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Loan term must be greater than zero');
        
        new LoanTerm(-5);
    }


    public function test_min_term(): void
    {
        $term = new LoanTerm(1);
        $this->assertEquals(1, $term->getYears());
        $this->assertEquals(12, $term->getMonths());
    }

    public function test_long_term(): void
    {
        $term = new LoanTerm(100);
        $this->assertEquals(100, $term->getYears());
        $this->assertEquals(1200, $term->getMonths());
    }

    public function test_months_calc(): void
    {
        $term = new LoanTerm(15);
        $this->assertEquals(180, $term->getMonths());
    }



    public function test_to_string(): void
    {
        $term = new LoanTerm(30);
        $this->assertEquals('30', (string) $term);
    }

}