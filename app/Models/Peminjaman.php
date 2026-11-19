<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'peminjam_id',
        'aset_id',
        'jumlah_pinjam',
        'nomor_pengajuan',
        'tanggal_pinjam',
        'tanggal_kembali',
        'keperluan',
        'dokumen_pendukung',
        'priority_score',
        'konflik_jadwal',
        'status',
        'catatan_pengelola',
        'catatan_admin',
        'alasan_penolakan',
        'diverifikasi_at',
        'disetujui_at',
        'ditolak_at',
        'diverifikasi_oleh',
        'disetujui_oleh',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_pinjam'    => 'integer',
            'tanggal_pinjam'   => 'date',
            'tanggal_kembali'  => 'date',
            'konflik_jadwal'   => 'boolean',
            'priority_score'   => 'integer',
            'diverifikasi_at'  => 'datetime',
            'disetujui_at'     => 'datetime',
            'ditolak_at'       => 'datetime',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function peminjam(): BelongsTo
    {
        return $this->belongsTo(User::class, 'peminjam_id');
    }

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function diverifikasiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function surat(): HasOne
    {
        return $this->hasOne(Surat::class, 'peminjaman_id');
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class, 'peminjaman_id');
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'peminjaman_id');
    }

    public function keluhanss(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'peminjaman_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeAktif($query)
    {
        return $query->whereIn('status', ['pending', 'diverifikasi', 'disetujui', 'dipinjam']);
    }

    /**
     * Hanya peminjaman dari akun yang dihitung dalam rekap bulanan
     * (akun yang ditandai admin "dikecualikan_rekap" tidak ikut dihitung).
     */
    public function scopeMasukRekap($query)
    {
        return $query->whereHas('peminjam', fn ($q) => $q->where('dikecualikan_rekap', false));
    }

    /** Peminjaman dari akun yang dikecualikan dari rekap bulanan */
    public function scopeDikecualikanRekap($query)
    {
        return $query->whereHas('peminjam', fn ($q) => $q->where('dikecualikan_rekap', true));
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'      => 'Menunggu Verifikasi',
            'diverifikasi' => 'Sudah Diverifikasi',
            'disetujui'    => 'Disetujui',
            'ditolak'      => 'Ditolak',
            'dipinjam'     => 'Sedang Dipinjam',
            'selesai'      => 'Selesai',
            'dibatalkan'   => 'Dibatalkan',
            default        => '-',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'      => 'warning',
            'diverifikasi' => 'info',
            'disetujui'    => 'success',
            'ditolak'      => 'danger',
            'dipinjam'     => 'primary',
            'selesai'      => 'secondary',
            'dibatalkan'   => 'dark',
            default        => 'secondary',
        };
    }

    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali);
    }

    public function isTerlambat(): bool
    {
        return $this->status === 'dipinjam' && now()->isAfter($this->tanggal_kembali);
    }
}
