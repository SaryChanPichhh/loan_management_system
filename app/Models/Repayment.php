<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'payment_date',
        'payment_method',
        'reference_number',
        'late_fee_applied',
        'is_early_settlement',
        'status',
        'received_by',
        'waived_by',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'principal_paid' => 'decimal:2',
        'interest_paid' => 'decimal:2',
        'penalty_paid' => 'decimal:2',
        'late_fee_paid' => 'decimal:2',
        'payment_date' => 'date',
        'late_fee_applied' => 'boolean',
        'is_early_settlement' => 'boolean',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function schedule()
    {
        return $this->belongsTo(LoanSchedule::class, 'schedule_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
