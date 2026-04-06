<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guarantor extends Model
{
    protected $fillable = [
        'customer_id',
        'full_name',
        'national_id',
        'phone',
        'address',
        'income',
        'relationship',
        'document_path',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
