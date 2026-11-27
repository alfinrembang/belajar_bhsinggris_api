<?php

namespace App\Http\Controllers\Api\Concerns;

use Carbon\CarbonInterface;

trait HasUploadMetadata
{
    /**
     * Format waktu upload ke WIB dan tentukan status baru (<= 3 hari dan belum selesai).
     *
     * @return array{created_at: ?string, is_baru: bool}
     */
    protected function formatUploadMetadata(?CarbonInterface $createdAt, bool $isCompleted): array
    {
        $wibTime = $createdAt?->copy()->timezone('Asia/Jakarta');

        return [
            'created_at' => $wibTime?->format('d M Y • H:i'),
            'is_baru' => ! $isCompleted && ($createdAt?->greaterThanOrEqualTo(now()->subDays(3)) ?? false),
        ];
    }
}
