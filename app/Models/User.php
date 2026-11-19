<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nik',
        'no_hp',
        'alamat',
        'foto_ktp',
        'jenis_peminjam',
        'priority_score',
        'is_active',
        'dikecualikan_rekap',
        'catatan_pengecualian',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
            'dikecualikan_rekap' => 'boolean',
            'priority_score'    => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Role Helpers
    // -------------------------------------------------------------------------

    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }

    public function isPengelola(): bool
    {
        return $this->role === 'pengelola';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /** Peminjaman yang diajukan user ini (role: peminjam) */
    public function peminjamans(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'peminjam_id');
    }

    /** Peminjaman yang diverifikasi oleh user ini (role: pengelola) */
    public function peminjamansVerified(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'diverifikasi_oleh');
    }

    /** Peminjaman yang disetujui oleh user ini (role: admin) */
    public function peminjamansApproved(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'disetujui_oleh');
    }

    /** Notifikasi milik user ini */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    /** Keluhan yang diajukan user ini (role: peminjam) */
    public function keluhanss(): HasMany
    {
        return $this->hasMany(Keluhan::class);
    }

    /** Keluhan yang ditangani user ini (role: pengelola) */
    public function keluhanssDitangani(): HasMany
    {
        return $this->hasMany(Keluhan::class, 'ditangani_oleh');
    }

    /** Penilaian kondisi aset yang dilakukan user ini (role: pengelola) */
    public function kondisiAsets(): HasMany
    {
        return $this->hasMany(KondisiAset::class, 'pengelola_id');
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    public function scopePeminjam($query)
    {
        return $query->where('role', 'peminjam');
    }

    /** Akun yang aktivitasnya dihitung dalam rekap bulanan */
    public function scopeMasukRekap($query)
    {
        return $query->where('dikecualikan_rekap', false);
    }

    /** Akun yang ditandai admin agar TIDAK dihitung dalam rekap bulanan */
    public function scopeDikecualikanRekap($query)
    {
        return $query->where('dikecualikan_rekap', true);
    }

    public function scopePengelola($query)
    {
        return $query->where('role', 'pengelola');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }
}
