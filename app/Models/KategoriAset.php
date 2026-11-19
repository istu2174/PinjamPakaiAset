<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAset extends Model
{
    protected $table = 'kategori_asets';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    public function asets(): HasMany
    {
        return $this->hasMany(Aset::class, 'kategori_id');
    }
}
