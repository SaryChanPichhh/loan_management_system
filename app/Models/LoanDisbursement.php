<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanDisbursement extends Model
{
    protected $fillable = [
        'loan_id',
        'amount',
        'method',
        'reference_number',
        'bank_name',
        'account_number',
        'notes',
        'disbursed_at',
        'disbursed_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'disbursed_at' => 'datetime',
    ];

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function disbursedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }
}
