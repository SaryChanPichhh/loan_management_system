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

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    public function guarantors(): HasMany
    {
        return $this->hasMany(Guarantor::class);
    }
}
