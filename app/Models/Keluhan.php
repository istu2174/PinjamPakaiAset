<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keluhan extends Model
{
    protected $table = 'keluhanss';

    protected $fillable = [
        'user_id',
        'peminjaman_id',
        'ditangani_oleh',
        'judul',
        'isi',
        'lampiran',
        'status',
        'respon_pengelola',
        'direspon_at',
    ];

    protected function casts(): array
    {
        return [
            'direspon_at' => 'datetime',
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

    public function ditanganiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeBelumSelesai($query)
    {
        return $query->whereIn('status', ['pending', 'diproses']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => 'Menunggu',
            'diproses'  => 'Sedang Diproses',
            'selesai'   => 'Selesai',
            default     => '-',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'  => 'warning',
            'diproses' => 'info',
            'selesai'  => 'success',
            default    => 'secondary',
        };
    }
}
