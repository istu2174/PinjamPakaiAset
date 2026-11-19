<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $table = 'notifikasis';

    protected $fillable = [
        'user_id',
        'peminjaman_id',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'dibaca_at',
    ];

    protected function casts(): array
    {
        return [
            'dibaca'    => 'boolean',
            'dibaca_at' => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeBelumDibaca($query)
    {
        return $query->where('dibaca', false);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function getTipeIconAttribute(): string
    {
        return match ($this->tipe) {
            'pengajuan'    => 'file-text',
            'verifikasi'   => 'check-circle',
            'persetujuan'  => 'thumbs-up',
            'reminder'     => 'bell',
            'pengembalian' => 'package',
            'keluhan'      => 'alert-circle',
            'sistem'       => 'info',
            default        => 'bell',
        };
    }

    public function getTipeColorAttribute(): string
    {
        return match ($this->tipe) {
            'pengajuan'    => 'primary',
            'verifikasi'   => 'info',
            'persetujuan'  => 'success',
            'reminder'     => 'warning',
            'pengembalian' => 'secondary',
            'keluhan'      => 'danger',
            'sistem'       => 'secondary',
            default        => 'secondary',
        };
    }
}
