<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoanCollateralDoc extends Model
{
    protected $table = 'loan_collateral_docs';

    // The docs table has no `updated_at` column (only uploaded_at)
    public $timestamps = false;

    protected $fillable = [
        'collateral_id',
        'document_type',
        'file_name',
        'storage_path',
        'mime_type',
        'file_size_bytes',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at'     => 'datetime',
        'file_size_bytes' => 'integer',
    ];

    // ── Relations ──────────────────────────────────────────────────────

    public function collateral(): BelongsTo
    {
        return $this->belongsTo(LoanCollateral::class, 'collateral_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // ── Helpers ────────────────────────────────────────────────────────

    /**
     * Human-readable file size.
     */
    public function fileSizeHuman(): string
    {
        $bytes = (int) $this->file_size_bytes;
        if ($bytes < 1024)        return "{$bytes} B";
        if ($bytes < 1048576)     return round($bytes / 1024, 1) . ' KB';
        return round($bytes / 1048576, 1) . ' MB';
    }
}
