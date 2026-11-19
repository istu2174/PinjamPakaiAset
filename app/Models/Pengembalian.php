<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengembalian extends Model
{
    protected $table = 'pengembalians';

    protected $fillable = [
        'peminjaman_id',
        'dikonfirmasi_oleh',
        'tanggal_kembali_aktual',
        'kondisi_saat_kembali',
        'catatan',
        'bukti_pengembalian',
        'terlambat',
        'hari_terlambat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_kembali_aktual' => 'date',
            'terlambat'              => 'boolean',
            'hari_terlambat'         => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    public function dikonfirmasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi_saat_kembali) {
            'baik'        => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat'  => 'Rusak Berat',
            default        => '-',
        };
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi_saat_kembali) {
            'baik'         => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat'  => 'danger',
            default        => 'secondary',
        };
    }
}
