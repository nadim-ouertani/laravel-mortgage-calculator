<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_loan(): void
    {
        $payload = [
            'loan_amount' => 200000,
            'annual_interest_rate' => 5.5,
            'loan_term_years' => 30,
            'monthly_extra_payment' => 0
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'loan_id',
                    'loan_details' => [
                        'loan_amount',
                        'annual_interest_rate',
                        'loan_term_years',
                        'monthly_extra_payment',
                        'calculated_monthly_payment',
                        'effective_interest_rate'
                    ],
                    'standard_schedule' => [
                        'total_payments',
                        'total_interest',
                        'total_principal',
                        'entries'
                    ]
                ]);

        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(200000, $data['loan_details']['loan_amount']);
        $this->assertEquals(5.5, $data['loan_details']['annual_interest_rate']);
        $this->assertEquals(30, $data['loan_details']['loan_term_years']);
        $this->assertEquals(0, $data['loan_details']['monthly_extra_payment']);
        $this->assertGreaterThan(1000, $data['loan_details']['calculated_monthly_payment']);
        $this->assertEquals(360, $data['standard_schedule']['total_payments']);
    }

    public function test_extra_payments(): void
    {
        $payload = [
            'loan_amount' => 200000,
            'annual_interest_rate' => 5.5,
            'loan_term_years' => 30,
            'monthly_extra_payment' => 200
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'loan_id',
                    'loan_details',
                    'standard_schedule',
                    'extra_payment_schedule' => [
                        'total_payments',
                        'total_interest',
                        'total_principal',
                        'total_extra_payments',
                        'interest_savings',
                        'time_savings',
                        'entries'
                    ]
                ]);

        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(200, $data['loan_details']['monthly_extra_payment']);
        $this->assertArrayHasKey('extra_payment_schedule', $data);
        $this->assertLessThan(360, $data['extra_payment_schedule']['total_payments']);
        $this->assertGreaterThan(0, $data['extra_payment_schedule']['interest_savings']);
    }

    public function test_validates_required_fields(): void
    {
        $response = $this->postJson('/api/loans/calculate', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors([
                    'loan_amount',
                    'annual_interest_rate',
                    'loan_term_years'
                ]);
    }

    public function test_validates_negative_loan_amount(): void
    {
        $payload = [
            'loan_amount' => -50000,
            'annual_interest_rate' => 5.5,
            'loan_term_years' => 30
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['loan_amount']);
    }

    public function test_accepts_high_interest_rate(): void
    {
        $payload = [
            'loan_amount' => 200000,
            'annual_interest_rate' => 35,
            'loan_term_years' => 30
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'loan_id',
                    'loan_details',
                    'standard_schedule'
                ]);
    }

    public function test_retrieve_loan(): void
    {
        $payload = [
            'loan_amount' => 150000,
            'annual_interest_rate' => 4.5,
            'loan_term_years' => 25
        ];

        $createResponse = $this->postJson('/api/loans/calculate', $payload);
        $loanId = $createResponse->json('loan_id');

        $response = $this->getJson("/api/loans/{$loanId}");
        $response->assertStatus(200);
        
        $data = $response->json();
        $this->assertEquals($loanId, $data['loan_details']['id']);
    }

    public function test_delete_loan(): void
    {
        $payload = [
            'loan_amount' => 100000,
            'annual_interest_rate' => 6.0,
            'loan_term_years' => 20
        ];

        $createResponse = $this->postJson('/api/loans/calculate', $payload);
        $loanId = $createResponse->json('loan_id');

        $response = $this->deleteJson("/api/loans/{$loanId}");
        $response->assertStatus(200);

        $getResponse = $this->getJson("/api/loans/{$loanId}");
        $getResponse->assertStatus(404);
    }

    public function test_validates_negative_interest_rate(): void
    {
        $payload = [
            'loan_amount' => 200000,
            'annual_interest_rate' => -2.5,
            'loan_term_years' => 30
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['annual_interest_rate']);
    }

    public function test_validates_zero_loan_term(): void
    {
        $payload = [
            'loan_amount' => 200000,
            'annual_interest_rate' => 5.0,
            'loan_term_years' => 0
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['loan_term_years']);
    }

    public function test_validates_excessive_extra_payment(): void
    {
        $payload = [
            'loan_amount' => 100000,
            'annual_interest_rate' => 5.0,
            'loan_term_years' => 30,
            'monthly_extra_payment' => 150000
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    public function test_zero_interest_rate(): void
    {
        $payload = [
            'loan_amount' => 120000,
            'annual_interest_rate' => 0,
            'loan_term_years' => 10,
            'monthly_extra_payment' => 0
        ];

        $response = $this->postJson('/api/loans/calculate', $payload);

        $response->assertStatus(200);
        $data = $response->json();
        $this->assertTrue($data['success']);
        $this->assertEquals(1000, $data['loan_details']['calculated_monthly_payment']);
    }

    public function test_retrieve_nonexistent_loan(): void
    {
        $response = $this->getJson('/api/loans/99999');
        $response->assertStatus(404)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }

    public function test_delete_nonexistent_loan(): void
    {
        $response = $this->deleteJson('/api/loans/99999');
        $response->assertStatus(404)
                ->assertJsonStructure([
                    'success',
                    'message',
                    'errors'
                ]);
    }
}
