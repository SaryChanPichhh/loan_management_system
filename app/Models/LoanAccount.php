<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanAccount extends Model
{
    protected $fillable = [
        'loan_id',
        'account_number',
        'outstanding_balance',
        'total_principal_paid',
        'total_interest_paid',
        'total_penalty_paid',
        'total_late_fee_paid',
        'overdue_amount',
        'days_past_due',
        'last_payment_at',
    ];

    protected $casts = [
        'outstanding_balance'  => 'decimal:2',
        'total_principal_paid' => 'decimal:2',
        'total_interest_paid'  => 'decimal:2',
        'total_penalty_paid'   => 'decimal:2',
        'total_late_fee_paid'  => 'decimal:2',
        'overdue_amount'       => 'decimal:2',
        'last_payment_at'      => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }
}
