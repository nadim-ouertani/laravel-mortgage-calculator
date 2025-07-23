<?php

namespace Tests\Unit;

use App\Domain\Loan\ValueObjects\InterestRate;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class InterestRateTest extends TestCase
{
    public function test_creates_rate(): void
    {
        $rate = new InterestRate(6.5);
        $this->assertEquals(6.5, $rate->getAnnualRate());
    }

    public function test_monthly_rate(): void
    {
        $rate = new InterestRate(6.0);
        $expectedMonthlyRate = 6.0 / 12 / 100;
        $this->assertEquals($expectedMonthlyRate, $rate->getMonthlyRate());
    }

    public function test_throws_exception_for_negative_interest_rate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Interest rate cannot be negative');
        
        new InterestRate(-1);
    }


    public function test_zero_rate(): void
    {
        $rate = new InterestRate(0);
        $this->assertEquals(0, $rate->getAnnualRate());
        $this->assertEquals(0, $rate->getMonthlyRate());
    }

    public function test_high_rate(): void
    {
        $rate = new InterestRate(50);
        $this->assertEquals(50, $rate->getAnnualRate());
        $this->assertEqualsWithDelta(50 / 12 / 100, $rate->getMonthlyRate(), 0.0001);
    }

    public function test_precision(): void
    {
        $rate = new InterestRate(6.125);
        $this->assertEquals(6.125, $rate->getAnnualRate());
        $this->assertEquals(6.125 / 12 / 100, $rate->getMonthlyRate());
    }




    public function test_to_string(): void
    {
        $rate = new InterestRate(6.5);
        $this->assertEquals('6.5', (string) $rate);
    }
}