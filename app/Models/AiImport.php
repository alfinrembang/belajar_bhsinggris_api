<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tipe_target',
        'nama_file_asli',
        'file_path',
        'format_file',
        'ukuran_file',
        'status',
        'hasil_ekstraksi_json',
        'error_message',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hasil_ekstraksi_json' => 'array',
            'ukuran_file' => 'integer',
        ];
    }

    /**
     * Relasi ke guru pengunggah dokumen.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
