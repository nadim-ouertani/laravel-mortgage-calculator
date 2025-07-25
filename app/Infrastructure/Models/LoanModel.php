<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanModel extends Model
{
    protected $table = 'loans';

    protected $fillable = [
        'loan_amount',
        'annual_interest_rate',
        'loan_term_years',
        'monthly_extra_payment',
        'calculated_monthly_payment',
        'effective_interest_rate',
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'annual_interest_rate' => 'decimal:3',
        'loan_term_years' => 'integer',
        'monthly_extra_payment' => 'decimal:2',
        'calculated_monthly_payment' => 'decimal:2',
        'effective_interest_rate' => 'decimal:3',
    ];

    public function amortizationSchedule(): HasMany
    {
        return $this->hasMany(AmortizationScheduleModel::class, 'loan_id');
    }

    public function extraRepaymentSchedule(): HasMany
    {
        return $this->hasMany(ExtraRepaymentScheduleModel::class, 'loan_id');
    }
}