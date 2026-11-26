<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasTargetRombel
{
    /**
     * Mengambil daftar rombel target sebagai array bersih.
     *
     * @return list<string>
     */
    public function getTargetRombelListAttribute(): array
    {
        if (empty($this->target_rombel)) {
            return [];
        }

        $items = array_map(
            fn (string $item): string => trim($item),
            explode(',', (string) $this->target_rombel)
        );

        return array_values(array_filter($items, fn (string $item): bool => $item !== ''));
    }

    /**
     * Label ringkas kelas / rombel target untuk ditampilkan pada UI tabel guru.
     */
    public function getTargetLabelAttribute(): string
    {
        $rombels = $this->target_rombel_list;
        if (! empty($rombels)) {
            return implode(', ', $rombels);
        }

        $tingkat = (string) ($this->tingkat_kelas ?? '');
        if ($tingkat === '' || $tingkat === 'Semua Kelas') {
            return 'Semua Kelas';
        }

        return 'Kelas '.str_ireplace('Kelas ', '', $tingkat);
    }

    /**
     * Scope filter halaman index web guru berdasarkan parameter rombel / kelas.
     */
    public function scopeFilterByRombelRequest(Builder $query, ?string $rombelInput, ?string $kelasInput = null): Builder
    {
        $rombel = trim((string) $rombelInput);
        $kelas = trim((string) $kelasInput);

        if ($rombel !== '') {
            if ($rombel === 'umum' || $rombel === 'Semua Kelas') {
                return $query->where('tingkat_kelas', 'Semua Kelas')
                    ->where(function (Builder $q): void {
                        $q->whereNull('target_rombel')
                            ->orWhere('target_rombel', '');
                    });
            }

            if (str_starts_with($rombel, 'tingkat:')) {
                $tingkat = trim(str_replace('tingkat:', '', $rombel));

                return $this->applyTingkatFilter($query, $tingkat);
            }

            $parts = explode(' ', $rombel);
            $tingkat = $parts[0] ?? '';

            return $query->where(function (Builder $q) use ($rombel, $tingkat): void {
                $this->applyExactTokenMatch($q, 'target_rombel', $rombel);

                if ($tingkat !== '') {
                    $q->orWhere(function (Builder $sub) use ($tingkat): void {
                        $sub->where(function (Builder $emptyRombel): void {
                            $emptyRombel->whereNull('target_rombel')
                                ->orWhere('target_rombel', '');
                        })->where(function (Builder $k) use ($tingkat): void {
                            $this->applyTingkatTokenMatch($k, $tingkat);
                        });
                    });
                }
            });
        }

        if ($kelas !== '') {
            if ($kelas === 'Semua Kelas' || $kelas === 'umum') {
                return $query->where('tingkat_kelas', 'Semua Kelas');
            }

            return $this->applyTingkatFilter($query, $kelas);
        }

        return $query;
    }

    /**
     * Scope visibilitas konten untuk aplikasi mobile siswa berdasarkan tingkat & rombel siswa.
     */
    public function scopeVisibleForStudent(Builder $query, ?string $rawKelas, ?string $tingkat, ?string $rombel): Builder
    {
        if (! $rombel && ! $tingkat && ($rawKelas === null || $rawKelas === '')) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($rawKelas, $tingkat, $rombel): void {
            // 1. Jika siswa memiliki rombel spesifik (misal '10 DKV 1' atau '12 RPL')
            if ($rombel) {
                // a. Konten yang secara eksplisit menyertakan rombel siswa di target_rombel
                $this->applyExactTokenMatch($q, 'target_rombel', $rombel);

                // b. Konten tanpa target_rombel spesifik yang ditujukan ke Semua Kelas atau tingkat kelas siswa
                $q->orWhere(function (Builder $general) use ($rawKelas, $tingkat): void {
                    $general->where(function (Builder $emptyRombel): void {
                        $emptyRombel->whereNull('target_rombel')
                            ->orWhere('target_rombel', '');
                    })->where(function (Builder $k) use ($rawKelas, $tingkat): void {
                        $k->where('tingkat_kelas', 'Semua Kelas');
                        if ($tingkat) {
                            $this->applyTingkatTokenMatch($k, $tingkat);
                        } elseif ($rawKelas) {
                            $k->orWhere('tingkat_kelas', $rawKelas);
                        }
                    });
                });

                return;
            }

            // 2. Fallback filter tingkat saja tanpa rombel spesifik (misal GET /api/materi?kelas=10)
            $q->where('tingkat_kelas', 'Semua Kelas');
            if ($tingkat) {
                $this->applyTingkatTokenMatch($q, $tingkat);
                $q->orWhere('target_rombel', 'like', "{$tingkat} %")
                    ->orWhere('target_rombel', 'like', "%, {$tingkat} %");
            }
            if ($rawKelas) {
                $q->orWhere('tingkat_kelas', $rawKelas);
            }
        });
    }

    /**
     * Memfilter berdasarkan tingkat kelas baik pada tingkat_kelas maupun awalan target_rombel.
     */
    protected function applyTingkatFilter(Builder $query, string $tingkat): Builder
    {
        return $query->where(function (Builder $q) use ($tingkat): void {
            $this->applyTingkatTokenMatch($q, $tingkat);
            $q->orWhere('target_rombel', 'like', "{$tingkat} %")
                ->orWhere('target_rombel', 'like', "%, {$tingkat} %");
        });
    }

    /**
     * Mencocokkan tingkat kelas (termasuk format lama 'Kelas 10' atau multi-tingkat '10, 11').
     */
    protected function applyTingkatTokenMatch(Builder $query, string $tingkat): void
    {
        $this->applyExactTokenMatch($query, 'tingkat_kelas', $tingkat);
        $query->orWhere('tingkat_kelas', 'Kelas '.$tingkat);
    }

    /**
     * Pencocokan token akurat pada kolom string tunggal maupun daftar dipisah koma (", ").
     */
    protected function applyExactTokenMatch(Builder $query, string $column, string $token): void
    {
        $query->orWhere($column, $token)
            ->orWhere($column, 'like', "{$token}, %")
            ->orWhere($column, 'like', "%, {$token}, %")
            ->orWhere($column, 'like', "%, {$token}");
    }
}
