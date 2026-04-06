<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Repayment extends Model
{
    protected $fillable = [
        'loan_id',
        'schedule_id',
        'amount',
        'principal_paid',
        'interest_paid',
        'penalty_paid',
        'late_fee_paid',
        'late_fee_applied',
        'is_early_settlement',
        'payment_date',
        'payment_method',
        'reference_number',
        'status',
        'notes',
        'received_by',
        'waived_by',
    ];

    protected $casts = [
        'amount'              => 'decimal:2',
        'principal_paid'      => 'decimal:2',
        'interest_paid'       => 'decimal:2',
        'penalty_paid'        => 'decimal:2',
        'late_fee_paid'       => 'decimal:2',
        'late_fee_applied'    => 'boolean',
        'is_early_settlement' => 'boolean',
        'payment_date'        => 'date',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(LoanSchedule::class, 'schedule_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
