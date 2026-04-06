<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoanCollateral extends Model
{
    protected $table = 'loan_collaterals';

    protected $fillable = [
        'loan_id',
        'collateral_type',
        'description',
        'estimated_value',
        'valuation_date',
        'status',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'valuation_date'  => 'date',
    ];

    // ── Relations ──────────────────────────────────────────────────────

    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function docs(): HasMany
    {
        return $this->hasMany(LoanCollateralDoc::class, 'collateral_id');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    /**
     * Bootstrap badge CSS class based on status.
     */
    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active'   => 'badge-success',
            'released' => 'badge-info',
            'seized'   => 'badge-danger',
            default    => 'badge-secondary',
        };
    }
}
