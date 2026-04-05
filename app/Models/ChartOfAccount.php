<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    protected $fillable = ['code', 'name', 'type', 'description', 'is_active'];

    public function journalItems()
    {
        return $this->hasMany(JournalItem::class);
    }
}
