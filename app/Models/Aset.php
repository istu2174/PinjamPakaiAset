<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aset extends Model
{
    protected $table = 'asets';

    protected $fillable = [
        'kategori_id',
        'kode_aset',
        'nama_aset',
        'merk',
        'deskripsi',
        'tahun_pengadaan',
        'lokasi',
        'jumlah',
        'kondisi',
        'status',
        'bisa_dipinjam',
        'foto',
    ];

    protected $casts = [
        'bisa_dipinjam'   => 'boolean',
        'jumlah'          => 'integer',
        'tahun_pengadaan' => 'integer',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriAset::class, 'kategori_id');
    }

    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'aset_id');
    }

    public function kondisiAsets(): HasMany
    {
        return $this->hasMany(KondisiAset::class, 'aset_id');
    }

    /** Kondisi terbaru dari aset ini (hasil Decision Tree terakhir) */
    public function kondisiTerbaru(): HasOne
    {
        return $this->hasOne(KondisiAset::class, 'aset_id')->latestOfMany();
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia')
                     ->where('kondisi', 'layak_pakai');
    }

    public function scopeLayakPakai($query)
    {
        return $query->where('kondisi', 'layak_pakai');
    }

    /** Scope untuk aset yang benar-benar bisa dipinjam oleh peminjam publik */
    public function scopeBisaDipakai($query)
    {
        return $query->where('bisa_dipinjam', true)
                     ->where('status', 'tersedia')
                     ->where('kondisi', 'layak_pakai');
    }

    // -------------------------------------------------------------------------
    // Accessors & Helpers
    // -------------------------------------------------------------------------

    /** Hitung unit aset yang sedang aktif dipinjam */
    public function getStokDipinjamAttribute(): int
    {
        return (int) $this->peminjamans()
            ->whereIn('status', ['diverifikasi', 'disetujui', 'dipinjam'])
            ->sum('jumlah_pinjam');
    }

    /** Hitung sisa stok unit yang tersedia untuk dipinjam */
    public function getStokTersediaAttribute(): int
    {
        return max(0, (int) $this->jumlah - $this->stok_dipinjam);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function isAvailable(): bool
    {
        return $this->bisa_dipinjam && $this->status === 'tersedia' && $this->kondisi === 'layak_pakai' && $this->stok_tersedia > 0;
    }

    public function getKondisiBadgeAttribute(): string
    {
        return match ($this->kondisi) {
            'layak_pakai'     => 'success',
            'perlu_perawatan' => 'warning',
            'layak_lelang'    => 'danger',
            default           => 'secondary',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'tersedia'    => 'success',
            'dipinjam'    => 'primary',
            'maintenance' => 'warning',
            'tidak_aktif' => 'danger',
            default       => 'secondary',
        };
    }
}
