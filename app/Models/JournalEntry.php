<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['entry_date', 'reference_type', 'reference_id', 'description', 'total_amount', 'created_by'];

    public function items()
    {
        return $this->hasMany(JournalItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
