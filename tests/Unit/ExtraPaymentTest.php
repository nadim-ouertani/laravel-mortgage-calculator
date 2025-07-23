<?php

namespace Tests\Unit;

use App\Domain\Loan\ValueObjects\ExtraPayment;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ExtraPaymentTest extends TestCase
{
    public function test_creates_extra_payment(): void
    {
        $extraPayment = new ExtraPayment(500.0);
        
        $this->assertEquals(500.0, $extraPayment->getValue());
        $this->assertEquals('500.00', $extraPayment->toString());
    }

    public function test_zero_payment(): void
    {
        $extraPayment = new ExtraPayment(0.0);
        
        $this->assertEquals(0.0, $extraPayment->getValue());
        $this->assertTrue($extraPayment->isZero());
    }

    public function test_throws_exception_for_negative_extra_payment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Extra payment must be zero or positive');
        
        new ExtraPayment(-100.0);
    }

    public function test_large_payment(): void
    {
        $extraPayment = new ExtraPayment(100000.0);
        $this->assertEquals(100000.0, $extraPayment->getValue());
    }


    public function test_small_payment(): void
    {
        $extraPayment = new ExtraPayment(0.01);
        $this->assertEquals(0.01, $extraPayment->getValue());
    }

    public function test_decimal_value(): void
    {
        $extraPayment = new ExtraPayment(299.99);
        $this->assertEquals(299.99, $extraPayment->getValue());
    }


    public function test_equals(): void
    {
        $extraPayment1 = new ExtraPayment(500.0);
        $extraPayment2 = new ExtraPayment(500.0);
        $extraPayment3 = new ExtraPayment(501.0);
        
        $this->assertTrue($extraPayment1->equals($extraPayment2));
        $this->assertFalse($extraPayment1->equals($extraPayment3));
    }

    public function test_float_precision(): void
    {
        $extraPayment1 = new ExtraPayment(500.004);
        $extraPayment2 = new ExtraPayment(500.006);
        
        $this->assertTrue($extraPayment1->equals($extraPayment2));
    }

    public function test_is_zero(): void
    {
        $zeroPayment = new ExtraPayment(0.0);
        $normalPayment = new ExtraPayment(100.0);
        
        $this->assertTrue($zeroPayment->isZero());
        $this->assertFalse($normalPayment->isZero());
    }

    public function test_rounding(): void
    {
        $extraPayment = new ExtraPayment(299.996);
        $this->assertEquals(300.0, $extraPayment->getValue());
    }


    public function test_to_string(): void
    {
        $extraPayment = new ExtraPayment(1234.56);
        $this->assertEquals('1,234.56', $extraPayment->toString());
    }
}