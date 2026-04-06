<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    // transactions table only has created_at (no updated_at)
    const UPDATED_AT = null;

    protected $fillable = [
        'account_id',
        'type',
        'amount',
        'running_balance',
        'reference',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'running_balance' => 'decimal:2',
        'created_at'      => 'datetime',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(LoanAccount::class, 'account_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
