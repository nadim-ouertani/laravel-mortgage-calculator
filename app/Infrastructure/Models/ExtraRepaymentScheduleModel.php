<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtraRepaymentScheduleModel extends Model
{
    protected $table = 'extra_repayment_schedule';

    protected $fillable = [
        'loan_id',
        'month_number',
        'starting_balance',
        'monthly_payment',
        'principal_component',
        'interest_component',
        'extra_repayment',
        'ending_balance',
        'remaining_term',
    ];

    protected $casts = [
        'loan_id' => 'integer',
        'month_number' => 'integer',
        'starting_balance' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
        'principal_component' => 'decimal:2',
        'interest_component' => 'decimal:2',
        'extra_repayment' => 'decimal:2',
        'ending_balance' => 'decimal:2',
        'remaining_term' => 'integer',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(LoanModel::class, 'loan_id');
    }
}