<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Surat extends Model
{
    protected $table = 'surats';

    protected $fillable = [
        'peminjaman_id',
        'nomor_surat',
        'tanggal_surat',
        'file_path',
        'barcode_token',
        'barcode_diakses_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat'      => 'date',
            'barcode_diakses_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }
}
