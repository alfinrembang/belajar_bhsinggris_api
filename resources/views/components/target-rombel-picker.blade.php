@props([
    'daftarRombelGrouped' => [],
    'selected' => null,
    'tingkatKelas' => null,
    'defaultTingkat' => null,
])

@php
    $hasStudents = ! empty($daftarRombelGrouped);
    $oldMulti = old('target_rombels');
    $selectedRaw = old('target_rombel', $selected);

    $initialMultiList = [];
    if (is_array($oldMulti) && ! empty($oldMulti)) {
        $initialMultiList = array_values(array_filter(array_map('trim', $oldMulti)));
    } elseif (is_string($selectedRaw) && str_contains($selectedRaw, ',')) {
        $initialMultiList = array_values(array_filter(array_map('trim', explode(',', $selectedRaw))));
    }

    if ($selectedRaw === '__multi__' || count($initialMultiList) > 1) {
        $dropdownValue = '__multi__';
    } elseif (count($initialMultiList) === 1) {
        $dropdownValue = $initialMultiList[0];
    } elseif ($selectedRaw !== null && $selectedRaw !== '') {
        $dropdownValue = (string) $selectedRaw;
    } elseif ($tingkatKelas !== null && $tingkatKelas !== '') {
        $cleanedTingkat = str_ireplace('Kelas ', '', (string) $tingkatKelas);
        $dropdownValue = $cleanedTingkat === 'Semua Kelas' ? 'Semua Kelas' : 'tingkat:'.$cleanedTingkat;
    } elseif ($defaultTingkat !== null && $defaultTingkat !== '') {
        $tingkatCode = str_replace('tingkat:', '', (string) $defaultTingkat);
        $dropdownValue = isset($daftarRombelGrouped[$tingkatCode]) ? (string) $defaultTingkat : '';
    } else {
        $dropdownValue = '';
    }
@endphp

<div data-rombel-picker>
    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
        Target Kelas &amp; Rombel Siswa <span class="text-rose-500">*</span>
    </label>

    @if($hasStudents)
        <select name="target_rombel"
                required
                onchange="window.toggleMultiRombelPanel && window.toggleMultiRombelPanel(this)"
                class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#4EA87D] outline-none">
            <option value="" disabled {{ $dropdownValue === '' ? 'selected' : '' }}>
                -- Pilih Target Kelas &amp; Rombel --
            </option>
            <option value="Semua Kelas" {{ $dropdownValue === 'Semua Kelas' ? 'selected' : '' }}>
                Semua Kelas (Umum / Bisa Diakses Seluruh Siswa)
            </option>
            <option value="__multi__" {{ $dropdownValue === '__multi__' ? 'selected' : '' }}>
                Pilih Beberapa Jurusan dan Kelas Sekaligus
            </option>
            @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                <optgroup label="Tingkat Kelas {{ $tingkat }}">
                    <option value="tingkat:{{ $tingkat }}" {{ $dropdownValue === 'tingkat:'.$tingkat ? 'selected' : '' }}>
                        Semua Kelas {{ $tingkat }} (Seluruh Jurusan)
                    </option>
                    @foreach($rombelList as $rombel)
                        <option value="{{ $rombel }}" {{ $dropdownValue === $rombel ? 'selected' : '' }}>
                            Khusus Jurusan {{ $rombel }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>

        <div data-multi-rombel-panel class="{{ $dropdownValue === '__multi__' ? '' : 'hidden' }} mt-2.5 p-3.5 rounded-2xl bg-slate-50 border border-emerald-200 space-y-2.5">
            <div class="flex items-center justify-between gap-2">
                <span class="text-[11px] font-bold text-[#2E7352]">Centang satu atau lebih jurusan dan kelas tujuan:</span>
                <button type="button"
                        onclick="window.clearMultiRombelCheckboxes && window.clearMultiRombelCheckboxes(this)"
                        class="text-[11px] font-semibold text-rose-600 hover:underline cursor-pointer">
                    Reset Pilihan
                </button>
            </div>
            <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Tingkat Kelas {{ $tingkat }}</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-1.5">
                            @foreach($rombelList as $rombel)
                                <label class="flex items-center gap-2 px-2.5 py-1.5 rounded-xl bg-white border border-slate-200/80 hover:border-[#4EA87D] text-[11px] text-slate-700 font-semibold cursor-pointer transition-colors">
                                    <input type="checkbox"
                                           name="target_rombels[]"
                                           value="{{ $rombel }}"
                                           {{ in_array($rombel, $initialMultiList, true) ? 'checked' : '' }}
                                           class="rounded text-[#4EA87D] focus:ring-[#4EA87D] multi-rombel-checkbox">
                                    <span class="truncate">{{ $rombel }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <select name="target_rombel"
                required
                class="w-full px-4 py-2.5 rounded-2xl bg-slate-100 border border-amber-300 text-xs text-slate-500 font-medium outline-none cursor-not-allowed">
        </select>
        <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-[11px] text-amber-800 flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                <p class="font-bold">Belum ada siswa terdaftar di sistem.</p>
                <p class="mt-0.5 text-amber-700">
                    Daftarkan atau import data siswa terlebih dahulu di menu
                    <a href="{{ route('guru.siswa.index') }}" class="underline font-bold text-amber-900 hover:text-amber-700">Manajemen Siswa &amp; Kelas</a>
                    agar pilihan kelas dan jurusan muncul.
                </p>
            </div>
        </div>
    @endif
</div>

@pushOnce('scripts')
<script>
    window.toggleMultiRombelPanel = function (selectEl) {
        const container = selectEl.closest('[data-rombel-picker]');
        if (!container) return;
        const panel = container.querySelector('[data-multi-rombel-panel]');
        if (!panel) return;

        const isMulti = selectEl.value === '__multi__';
        panel.classList.toggle('hidden', !isMulti);

        if (!isMulti) {
            panel.querySelectorAll('.multi-rombel-checkbox').forEach(cb => {
                cb.checked = false;
            });
        }
    };

    window.clearMultiRombelCheckboxes = function (btnEl) {
        const panel = btnEl.closest('[data-multi-rombel-panel]');
        if (!panel) return;
        panel.querySelectorAll('.multi-rombel-checkbox').forEach(cb => {
            cb.checked = false;
        });
    };
</script>
@endPushOnce
