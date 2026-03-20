<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{

    protected $fillable = [
        'code',
        'name',
        'gender',
        'phone',
        'address',
        'type',
        'status',
        'document'
    ];
}
