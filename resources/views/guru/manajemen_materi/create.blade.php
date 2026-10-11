<x-layout 
    title="Tambah Materi Baru - Study English Prima" 
    pageTitle="Tambah Materi Pembelajaran" 
    pageSubtitle="Buat modul pembelajaran baru lengkap dengan penjelasan teori, audio listening, dan latihan soal." 
    active="materi"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6">
        <x-button variant="back" href="{{ route('guru.materi.index') }}">
            Kembali ke Daftar Materi
        </x-button>
    </div>

    <!-- Alert Error Validasi -->
    <x-alert-errors />

    <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- SEKSI 1: INFORMASI MODUL & TEORI -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="01" 
                title="Informasi Utama & Penjelasan Teori" 
                subtitle="Pengaturan judul modul, kategori, kelas target, dan konsep tata bahasa" 
                color="blue" 
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Judul Materi -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Judul Materi Pembelajaran <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           value="{{ old('judul') }}"
                           placeholder="Contoh: Descriptive Text - Describing Historical Places" 
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                </div>

                <!-- Kategori Materi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori Pembelajaran <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="">Pilih Kategori...</option>
                        @if(isset($kategoriList) && $kategoriList->isNotEmpty())
                            @foreach($kategoriList as $kat)
                                <option value="{{ $kat->nama }}" {{ old('kategori') == $kat->nama ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        @else
                            <option value="Reading" {{ old('kategori') == 'Reading' ? 'selected' : '' }}>Reading</option>
                            <option value="Grammar" {{ old('kategori') == 'Grammar' ? 'selected' : '' }}>Grammar</option>
                            <option value="Conversation" {{ old('kategori') == 'Conversation' ? 'selected' : '' }}>Conversation</option>
                            <option value="Vocabulary" {{ old('kategori') == 'Vocabulary' ? 'selected' : '' }}>Vocabulary</option>
                        @endif
                    </select>
                </div>

                <!-- Tingkat Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Tingkat Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_kelas" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="">Pilih Tingkat Kelas...</option>
                        <option value="10" {{ old('tingkat_kelas') == '10' ? 'selected' : '' }}>Khusus Kelas 10</option>
                        <option value="11" {{ old('tingkat_kelas') == '11' ? 'selected' : '' }}>Khusus Kelas 11</option>
                        <option value="12" {{ old('tingkat_kelas') == '12' ? 'selected' : '' }}>Khusus Kelas 12</option>
                        <option value="Semua Kelas" {{ old('tingkat_kelas') == 'Semua Kelas' ? 'selected' : '' }}>Semua Kelas (Umum)</option>
                    </select>
                </div>

                <!-- XP Reward -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Hadiah XP Belajar (Leaderboard)
                    </label>
                    <input type="number" 
                           name="xp_reward" 
                           value="{{ old('xp_reward', 50) }}"
                           min="0"
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Tayang
                    </label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif (Langsung Tayang di Flutter)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    </select>
                </div>

                <!-- Ringkasan Singkat -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Ringkasan Singkat (Muncul pada Kartu Daftar Siswa)
                    </label>
                    <input type="text" 
                           name="deskripsi_singkat" 
                           value="{{ old('deskripsi_singkat') }}"
                           placeholder="Contoh: Mempelajari struktur teks deskriptif dan tata bahasa adjective clause" 
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                </div>

                <!-- Penjelasan Teori Lengkap -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Penjelasan Teori & Konsep Belajar <span class="text-rose-500">*</span>
                    </label>
                    <textarea name="penjelasan" 
                              rows="6" 
                              required
                              placeholder="Tuliskan materi penjelasan teori, rumus kalimat, aturan tata bahasa, dan tips belajar di sini..." 
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all leading-relaxed">{{ old('penjelasan') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEKSI 2: CONTOH TEKS / CERITA BACAAN & GAMBAR ILUSTRASI -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="02" 
                title="Contoh Teks / Cerita Bacaan" 
                subtitle="Penerapan materi dalam bentuk contoh teks, cerita pendek, dialog percakapan, dan gambar pendukung" 
                color="emerald" 
            />

            <div class="space-y-4">
                <!-- Contoh Teks Bacaan / Cerita -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Teks Contoh Cerita / Bacaan / Percakapan
                    </label>
                    <textarea name="contoh_teks" 
                              rows="6" 
                              placeholder="Tuliskan contoh teks cerita atau bacaan bahasa Inggris yang berkaitan dengan materi ini..." 
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all leading-relaxed">{{ old('contoh_teks') }}</textarea>
                </div>

                <!-- Upload Gambar Ilustrasi Cerita -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        🖼️ Gambar Ilustrasi Cerita / Cover Materi (Opsional)
                    </label>
                    <p class="text-[11px] text-slate-400 mb-3">Format: JPG, PNG, WEBP (Maks 3MB) - Ditampilkan pada halaman contoh cerita di aplikasi siswa</p>
                    <input type="file" 
                           name="gambar_materi" 
                           accept="image/*"
                           onchange="previewImage(this)"
                           class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E6BFF] hover:file:bg-blue-100 cursor-pointer">
                    <div id="image-preview-container" class="hidden mt-3">
                        <img id="image-preview" src="#" alt="Preview Gambar" class="h-32 rounded-xl object-cover border border-slate-200">
                    </div>
                </div>
            </div>
        </div>

        <!-- SEKSI 3: LATIHAN SOAL PEMAHAMAN (CAMPURAN PG & LISTENING) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="03" 
                title="Latihan Soal Pemahaman (Mini Quiz)" 
                subtitle="Buat butir soal evaluasi pemahaman (bisa campuran Pilihan Ganda & Listening)" 
                color="purple"
            >
                <button type="button" 
                        onclick="tambahSoal()"
                        class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold text-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    + Tambah Soal
                </button>
            </x-section-header>

            <!-- Wadah Butir Soal -->
            <div id="soal-container" class="space-y-4">
                @for ($i = 0; $i < 5; $i++)
                    <x-soal-card :index="$i" audio-field-name="audio_soal" :audio-wrapper-id="'audio-soal-wrapper-'.$i" :audio-preview-id="'audio-soal-preview-'.$i" on-type-change="toggleAudioSoal(this, '{{ $i }}')" on-audio-change="previewAudioSoal(this, '{{ $i }}')" />
                @endfor
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <x-button variant="cancel" href="{{ route('guru.materi.index') }}">
                Batal
            </x-button>
            <x-button variant="submit">
                Simpan & Publikasikan Modul
            </x-button>
        </div>
    </form>

    <!-- Template Kartu Soal untuk Penambahan Dinamis -->
    <template id="soal-template">
        <x-soal-card index="__INDEX__" number="__NUMBER__" audio-field-name="audio_soal" audio-wrapper-id="audio-soal-wrapper-__INDEX__" audio-preview-id="audio-soal-preview-__INDEX__" on-type-change="toggleAudioSoal(this, '__INDEX__')" on-audio-change="previewAudioSoal(this, '__INDEX__')" />
    </template>

    <!-- JavaScript Interaktif untuk Preview Media & Penambahan Soal Dinamis -->
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const container = document.getElementById('image-preview-container');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleAudioSoal(select, index) {
            const wrapper = document.getElementById(`audio-soal-wrapper-${index}`);
            if (wrapper) {
                if (select.value === 'listening') {
                    wrapper.classList.remove('hidden');
                } else {
                    wrapper.classList.add('hidden');
                }
            }
        }

        function previewAudioSoal(input, index) {
            const audio = document.getElementById(`audio-soal-preview-${index}`);
            if (audio && input.files && input.files[0]) {
                audio.src = URL.createObjectURL(input.files[0]);
                audio.classList.remove('hidden');
            }
        }

        let soalCount = 5;

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const template = document.getElementById('soal-template');
            if (!template) return;
            const newIndex = soalCount++;
            const newNum = container.querySelectorAll('.soal-card').length + 1;
            const html = template.innerHTML.replaceAll('__INDEX__', newIndex).replaceAll('__NUMBER__', newNum);
            container.insertAdjacentHTML('beforeend', html);
            renumberSoal();
        }

        function hapusSoal(button) {
            const card = button.closest('.soal-card');
            if (card) {
                card.remove();
                renumberSoal();
            }
        }

        function renumberSoal() {
            const numbers = document.querySelectorAll('.soal-number');
            numbers.forEach((el, idx) => {
                el.innerText = idx + 1;
            });
        }
    </script>
</x-layout>
