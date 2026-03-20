<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'loan_reference',
        'customer_name',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'points',
        'status',
        'notes'
    ];
}
