<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KondisiAset extends Model
{
    protected $table = 'kondisi_asets';

    protected $fillable = [
        'aset_id',
        'pengelola_id',
        'kondisi_fisik',
        'usia_aset',
        'riwayat_perawatan_rutin',
        'frekuensi_penggunaan',
        'masih_bisa_diperbaiki',
        'hasil_decision_tree',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'riwayat_perawatan_rutin' => 'boolean',
            'masih_bisa_diperbaiki'   => 'boolean',
            'kondisi_fisik'           => 'integer',
            'usia_aset'               => 'integer',
            'frekuensi_penggunaan'    => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function aset(): BelongsTo
    {
        return $this->belongsTo(Aset::class, 'aset_id');
    }

    public function pengelola(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengelola_id');
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    public function getHasilLabelAttribute(): string
    {
        return match ($this->hasil_decision_tree) {
            'layak_pakai'     => 'Layak Pakai',
            'perlu_perawatan' => 'Perlu Perawatan',
            'layak_lelang'    => 'Layak Lelang',
            default           => '-',
        };
    }
}
