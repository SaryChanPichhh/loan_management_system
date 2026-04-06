<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanProduct extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'description',
        'min_amount',
        'max_amount',
        'interest_rate',
        'interest_type',
        'max_term_months',
        'grace_period_days',
        'late_fee_rate',
        'guarantor_income_multiplier',
        'requires_collateral_above',
        'penalty_rate',
        'status',
    ];

    protected $casts = [
        'min_amount'                => 'decimal:2',
        'max_amount'                => 'decimal:2',
        'interest_rate'             => 'decimal:4',
        'late_fee_rate'             => 'decimal:4',
        'penalty_rate'              => 'decimal:4',
        'guarantor_income_multiplier' => 'decimal:2',
        'requires_collateral_above' => 'decimal:2',
        'status'                    => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function interestTypeLabel(): string
    {
        return match ($this->interest_type) {
            'FLAT'              => 'ថេរ (Flat)',
            'REDUCING_BALANCE'  => 'ឬ ន (Reducing Balance)',
            'COMPOUND'          => 'ស្មុគស្មាញ (Compound)',
            default             => $this->interest_type,
        };
    }
}
