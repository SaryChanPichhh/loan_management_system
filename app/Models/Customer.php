<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'gender',
        'phone',
        'email',
        'address',
        'national_id',
        'date_of_birth',
        'age_verified',
        'occupation',
        'monthly_income',
        'has_existing_loan',
        'credit_score',
        'type',
        'status',
        'document_path',
        'created_by',
    ];

    protected $casts = [
        'monthly_income'    => 'decimal:2',
        'age_verified'      => 'boolean',
        'has_existing_loan' => 'boolean',
        'status'            => 'boolean',
        'date_of_birth'     => 'date',
    ];

    /**
     * Get validation rules for Customer.
     * 
     * @param int|null $id
     * @return array
     */
    public static function validationRules(?int $id = null): array
    {
        return [
            'code'              => 'required|unique:customers,code,' . $id,
            'name'              => 'required|max:255',
            'gender'            => 'required|in:Male,Female,Other',
            'phone'             => 'required|max:30',
            'email'             => 'nullable|email|max:255',
            'address'           => 'nullable|max:255',
            'national_id'       => 'nullable|max:50',
            'date_of_birth'     => 'nullable|date',
            'age_verified'      => 'nullable|boolean',
            'occupation'        => 'nullable|max:255',
            'monthly_income'    => 'nullable|numeric|min:0',
            'has_existing_loan' => 'nullable|boolean',
            'credit_score'      => 'nullable|integer|min:0|max:1000',
            'type'              => 'required|max:50',
            'status'            => 'nullable|boolean',
            'document_path'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function loanSchedules()
    {
        return $this->hasManyThrough(LoanSchedule::class, Loan::class);
    }

    public function guarantors(): HasMany
    {
        return $this->hasMany(Guarantor::class);
    }

    /**
     * Calculate a dynamic credit score for the customer.
     * Scale: 0 - 100
     */
    public function getCalculatedCreditScoreAttribute()
    {
        $baseScore = 50;
        
        $schedules = $this->loanSchedules;
        
        if ($schedules->isEmpty()) {
            return $baseScore;
        }

        $paidOnTime = $schedules->where('status', 'paid')->count();
        $overdue = $schedules->where('status', 'overdue')->count();
        $partial = $schedules->where('status', 'partial')->count();

        // Scoring Logic
        $score = $baseScore + ($paidOnTime * 5) - ($overdue * 10) - ($partial * 3);

        return max(0, min(100, $score));
    }
}
