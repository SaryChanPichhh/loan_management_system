<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalItem extends Model
{
    protected $fillable = ['journal_entry_id', 'chart_of_account_id', 'type', 'amount', 'description'];

    public function entry()
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account()
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
}
