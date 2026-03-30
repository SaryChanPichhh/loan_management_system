<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    protected $fillable = [
        'application_code',
        'customer_id',
        'product_id',
        'requested_amount',
        'requested_months',
        'purpose',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
        'loan_id',
        'created_by'
    ];

    protected $casts = [
        'requested_amount' => 'decimal:2',
        'requested_months' => 'integer',
        'reviewed_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(LoanProduct::class, 'product_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class, 'loan_id');
    }

    public function getStatusBadgeHtmlAttribute()
    {
        return match ($this->status) {
            'pending' => '<span class="badge badge-info">រងចាំការពិនិត្យ (Pending)</span>',
            'under_review' => '<span class="badge badge-warning">កំពុងពិនិត្យ</span>',
            'approved' => '<span class="badge badge-success">បានអនុម័ត</span>',
            'rejected' => '<span class="badge badge-danger">បដិសេធ</span>',
            'cancelled' => '<span class="badge badge-dark">បានបោះបង់</span>',
            default => '<span class="badge badge-light">' . $this->status . '</span>',
        };
    }
}
