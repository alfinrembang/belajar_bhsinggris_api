<x-layout 
    title="Edit Kategori Materi - Study English Prima" 
    pageTitle="Edit Kategori Materi" 
    pageSubtitle="Perbarui informasi kategori, warna swatch, dan ikon penanda kategori." 
    active="kategori"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('guru.kategori.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Kategori
        </a>
    </div>

    <!-- Tampilan Error Validasi -->
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs shadow-xs">
            <div class="flex items-center gap-2 font-bold mb-1">
                <span>⚠️ Mohon periksa kembali formulir:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-[11px] text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Edit (Eksplisit POST ke route update) -->
    <form action="{{ route('guru.kategori.update', $kategori->id) }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Kolom Kiri: Form Input -->
            <div class="lg:col-span-2 space-y-6">
                <!-- 1. Informasi Dasar -->
                <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-sm">
                            01
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Informasi Utama Kategori</h3>
                            <p class="text-xs text-slate-400">Nama topik kebahasaan dan penjelasan ringkas</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Nama Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nama Kategori <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   id="input-nama"
                                   name="nama" 
                                   value="{{ old('nama', $kategori->nama) }}"
                                   placeholder="Contoh: Reading, Grammar, Idioms" 
                                   required
                                   oninput="updatePreview()"
                                   class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                        </div>

                        <!-- Deskripsi Kategori -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Deskripsi Pembelajaran
                            </label>
                            <textarea id="input-deskripsi"
                                      name="deskripsi" 
                                      rows="3" 
                                      placeholder="Tuliskan tujuan dan cakupan materi dalam kategori ini..." 
                                      oninput="updatePreview()"
                                      class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none transition-all leading-relaxed">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Urutan Tampil -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Urutan Tampilan (Nomor Urut di Menu)
                                </label>
                                <input type="number" 
                                       name="urutan" 
                                       value="{{ old('urutan', $kategori->urutan) }}"
                                       min="1"
                                       class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                            </div>

                            <!-- Status Aktif -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Status Publikasi
                                </label>
                                <label class="flex items-center gap-3 p-2.5 rounded-2xl border border-slate-200/80 bg-slate-50 cursor-pointer hover:bg-slate-100 transition-all">
                                    <input type="checkbox" 
                                           name="is_aktif" 
                                           value="1" 
                                           {{ old('is_aktif', $kategori->is_aktif) ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-[#1E6BFF] focus:ring-[#1E6BFF]">
                                    <span class="text-xs font-medium text-slate-700">Tampilkan kategori ini ke siswa</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Pemilihan Warna Visual (Sistem Klik Swatch) -->
                <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                            02
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Pilih Warna Tema Visual</h3>
                            <p class="text-xs text-slate-400">Cukup klik salah satu warna di bawah untuk tema kartu dan badge</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($presetColors as $color)
                            <label class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition-all hover:bg-slate-50 border-slate-200/80 has-checked:border-[#1E6BFF] has-checked:bg-blue-50/40 has-checked:ring-2 has-checked:ring-[#1E6BFF]/20">
                                <input type="radio" 
                                       name="warna_hex" 
                                       value="{{ $color['hex'] }}" 
                                       {{ old('warna_hex', $kategori->warna_hex) == $color['hex'] ? 'checked' : '' }}
                                       onchange="selectColor('{{ $color['hex'] }}')"
                                       class="sr-only">
                                <span class="w-7 h-7 rounded-full shrink-0 shadow-xs border border-white" style="background-color: {{ $color['hex'] }};"></span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $color['name'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $color['hex'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- 3. Pemilihan Ikon Kategori (Sistem Klik Swatch) -->
                <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
                    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                            03
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800 text-base">Pilih Ikon Penanda Kategori</h3>
                            <p class="text-xs text-slate-400">Klik gambar ikon yang mewakili materi pembelajaran ini</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($presetIcons as $icon)
                            <label class="relative flex items-center gap-3 p-3 rounded-2xl border cursor-pointer transition-all hover:bg-slate-50 border-slate-200/80 has-checked:border-[#1E6BFF] has-checked:bg-blue-50/40 has-checked:ring-2 has-checked:ring-[#1E6BFF]/20">
                                <input type="radio" 
                                       name="icon_name" 
                                       value="{{ $icon['name'] }}" 
                                       data-emoji="{{ $icon['emoji'] }}"
                                       {{ old('icon_name', $kategori->icon_name) == $icon['name'] ? 'checked' : '' }}
                                       onchange="selectIcon('{{ $icon['name'] }}', '{{ $icon['emoji'] }}')"
                                       class="sr-only">
                                <span class="text-2xl shrink-0">{{ $icon['emoji'] }}</span>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $icon['label'] }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $icon['name'] }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Pratinjau Interaktif (Live Card Preview) -->
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs sticky top-8 space-y-4">
                    <h4 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                        <span>👁️</span> Pratinjau Tampilan di Siswa
                    </h4>
                    <p class="text-[11px] text-slate-400">
                        Ini adalah simulasi visual bagaimana kategori ini akan tampil di aplikasi Flutter siswa:
                    </p>

                    <!-- Kartu Preview Siswa -->
                    <div class="p-5 rounded-2xl border border-slate-200/80 bg-white shadow-xs space-y-3">
                        <div class="flex items-center gap-3">
                            <!-- Thumbnail Kotak Preview -->
                            <div id="preview-thumbnail" class="w-12 h-12 rounded-2xl flex items-center justify-center text-white text-xl shadow-xs transition-all" style="background-color: {{ $kategori->warna_hex }};">
                                <span id="preview-emoji">📚</span>
                            </div>

                            <div class="min-w-0 flex-1">
                                <!-- Badge Preview -->
                                <span id="preview-badge" class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold text-white transition-all" style="background-color: {{ $kategori->warna_hex }};">
                                    {{ $kategori->nama }}
                                </span>
                                <h5 id="preview-title" class="font-bold text-slate-800 text-sm mt-1 truncate">
                                    {{ $kategori->nama }}
                                </h5>
                            </div>
                        </div>

                        <!-- Deskripsi Preview -->
                        <p id="preview-desc" class="text-xs text-slate-500 line-clamp-2">
                            {{ $kategori->deskripsi ?? 'Deskripsi ringkas materi akan muncul di sini.' }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-slate-100">
                        <button type="submit" 
                                class="w-full py-3 rounded-2xl bg-[#1E6BFF] hover:bg-blue-600 text-white font-bold text-xs transition-all shadow-[0_6px_20px_rgba(30,107,255,0.28)] cursor-pointer">
                            Simpan Perubahan Kategori
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @php
        $iconMap = [
            'auto_stories' => '📚',
            'spellcheck' => '✍️',
            'forum' => '🗣️',
            'translate' => '📖',
            'lightbulb' => '💡',
            'headphones' => '🎧',
            'business_center' => '💼',
            'track_changes' => '🎯',
        ];
        $initialEmoji = $iconMap[$kategori->icon_name] ?? '📚';
    @endphp

    <script>
        let currentColor = "{{ old('warna_hex', $kategori->warna_hex) }}";
        let currentEmoji = "{{ $initialEmoji }}";

        function selectColor(hex) {
            currentColor = hex;
            updatePreview();
        }

        function selectIcon(name, emoji) {
            currentEmoji = emoji;
            updatePreview();
        }

        function updatePreview() {
            const nama = document.getElementById('input-nama').value.trim() || 'Nama Kategori';
            const desc = document.getElementById('input-deskripsi').value.trim() || 'Deskripsi ringkas materi akan muncul di sini.';

            document.getElementById('preview-title').innerText = nama;
            document.getElementById('preview-badge').innerText = nama;
            document.getElementById('preview-desc').innerText = desc;
            document.getElementById('preview-emoji').innerText = currentEmoji;

            document.getElementById('preview-thumbnail').style.backgroundColor = currentColor;
            document.getElementById('preview-badge').style.backgroundColor = currentColor;
        }

        document.addEventListener('DOMContentLoaded', updatePreview);
    </script>
</x-layout>
