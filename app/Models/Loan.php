<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Core identifiers
        'loan_code',
        'customer_id',
        'application_id',
        'product_id',

        // Financials
        'principal_amount',
        'disbursed_amount',
        'interest_rate',

        // Duration
        'duration_months',

        // Status & purpose
        'status',
        'purpose',

        // Dates
        'start_date',
        'end_date',
        'first_payment_date',
        'grace_period_end_date',
        'early_settlement_date',

        // Flags
        'collateral_required',
        'guarantor_required',

        // Audit / tracking
        'approved_by',
        'rejected_by',
        'rejected_reason',
        'created_by',

        // Legacy
        'note',
    ];

    protected $casts = [
        'principal_amount'    => 'decimal:2',
        'disbursed_amount'    => 'decimal:2',
        'interest_rate'       => 'decimal:4',
        'collateral_required' => 'boolean',
        'guarantor_required'  => 'boolean',
        'start_date'          => 'date',
        'end_date'            => 'date',
        'first_payment_date'  => 'date',
        'grace_period_end_date' => 'date',
        'early_settlement_date' => 'date',
    ];

    // ─── Relationships ────────────────────────────────────────────

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(LoanProduct::class, 'product_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(LoanApplication::class, 'application_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(LoanSchedule::class);
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────

    /**
     * Human-readable Khmer status label.
     */
    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'      => 'កំពុងរង់ចាំ',
            'under_review' => 'កំពុងពិនិត្យ',
            'approved'     => 'បានអនុម័ត',
            'rejected'     => 'បដិសេធ',
            'active'       => 'កំពុងដំណើរការ',
            'completed'    => 'បានបញ្ចប់',
            'defaulted'    => 'មិនបានសង',
            'written_off'  => 'ចាត់ទុកជាខាត',
            default        => $this->status,
        };
    }

    /**
     * Bootstrap badge CSS class for status.
     */
    public function statusBadge(): string
    {
        return match ($this->status) {
            'pending'      => 'badge-warning',
            'under_review' => 'badge-info',
            'approved'     => 'badge-primary',
            'rejected'     => 'badge-danger',
            'active'       => 'badge-success',
            'completed'    => 'badge-secondary',
            'defaulted'    => 'badge-danger',
            'written_off'  => 'badge-dark',
            default        => 'badge-light',
        };
    }
}
