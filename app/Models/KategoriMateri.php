<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KategoriMateri extends Model
{
    protected $table = 'kategori_materis';

    protected $fillable = [
        'nama',
        'slug',
        'deskripsi',
        'warna_hex',
        'icon_name',
        'urutan',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Relasi ke seluruh materi pembelajaran dengan kategori ini.
     */
    public function materis(): HasMany
    {
        return $this->hasMany(Materi::class, 'kategori', 'nama');
    }

    /**
     * Auto generate slug saat simpan nama.
     */
    protected static function booted(): void
    {
        static::saving(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }
}
