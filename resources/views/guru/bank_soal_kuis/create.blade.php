<x-layout 
    title="Buat Paket Kuis Baru - Study English Prima" 
    pageTitle="Buat Paket Kuis Baru" 
    pageSubtitle="Rancang paket kuis evaluasi, soal listening/reading/tata bahasa, dan latihan interaktif untuk siswa." 
    active="quiz"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6">
        <x-button variant="back" href="{{ route('guru.quiz.index') }}">
            Kembali ke Bank Soal & Kuis
        </x-button>
    </div>

    <!-- Alert Error Validasi -->
    <x-alert-errors title="Mohon periksa kembali formulir kuis:" />

    <form action="{{ route('guru.quiz.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- SEKSI 1: INFORMASI KUIS -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="01" 
                title="Informasi Utama Kuis" 
                subtitle="Atur judul paket, kategori, durasi waktu pengerjaan, dan target kelas siswa" 
                color="blue" 
            />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Judul Kuis -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Judul Paket Kuis <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           value="{{ old('judul') }}"
                           placeholder="Contoh: Comprehensive Grammar & Reading Quiz Chapter 1" 
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>

                <!-- Kategori Kuis -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Kategori Soal <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#4EA87D] outline-none">
                        <option value="">Pilih Kategori...</option>
                        <option value="Grammar" {{ old('kategori') == 'Grammar' ? 'selected' : '' }}>Grammar Test</option>
                        <option value="Vocabulary" {{ old('kategori') == 'Vocabulary' ? 'selected' : '' }}>Vocabulary Challenge</option>
                        <option value="Reading" {{ old('kategori') == 'Reading' ? 'selected' : '' }}>Reading Comprehension</option>
                        <option value="Speaking" {{ old('kategori') == 'Speaking' ? 'selected' : '' }}>Speaking & Expression</option>
                        <option value="General" {{ old('kategori') == 'General' ? 'selected' : '' }}>General English</option>
                    </select>
                </div>

                <!-- Target Kelas & Rombel (Dinamis Satu Dropdown Terpadu) -->
                @php
                    $selectedRombel = old('target_rombel', '');
                @endphp
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Kelas & Rombel Siswa <span class="text-rose-500">*</span>
                    </label>
                    <select name="target_rombel" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#4EA87D] outline-none">
                        <option value="" {{ $selectedRombel === '' || $selectedRombel === 'Semua Kelas' ? 'selected' : '' }}>
                            Semua Kelas (Umum / Bisa Diakses Seluruh Siswa)
                        </option>
                        @if(isset($daftarRombelGrouped) && !empty($daftarRombelGrouped))
                            @foreach($daftarRombelGrouped as $tingkat => $rombelList)
                                <optgroup label="Tingkat Kelas {{ $tingkat }}">
                                    <option value="tingkat:{{ $tingkat }}" {{ $selectedRombel === 'tingkat:'.$tingkat ? 'selected' : '' }}>
                                        Semua Kelas {{ $tingkat }} (Seluruh Rombel)
                                    </option>
                                    @foreach($rombelList as $rombel)
                                        <option value="{{ $rombel }}" {{ $selectedRombel === $rombel ? 'selected' : '' }}>
                                            Khusus Rombel {{ $rombel }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        @endif
                    </select>
                </div>

                <!-- Simpan Kode Room Otomatis (Hidden) -->
                <input type="hidden" id="kode_room_input" name="kode_room" value="{{ old('kode_room', $defaultRoomCode ?? 'QUIZ01') }}">

                <!-- Durasi Waktu (Menit) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Durasi Pengerjaan (Menit) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" 
                           name="durasi_menit" 
                           value="{{ old('durasi_menit', 15) }}"
                           min="1"
                           max="180"
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                </div>

                <!-- Hadiah XP -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Hadiah XP (Leaderboard)
                    </label>
                    <input type="number" 
                           name="xp_reward" 
                           value="{{ old('xp_reward', 60) }}"
                           min="0"
                           max="1000"
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                </div>

                <!-- Status Publikasi -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Kuis <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#4EA87D] outline-none">
                        <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>🟢 Aktif (Siswa Dapat Mengerjakan)</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>⚪ Draft (Simpan Sementara)</option>
                    </select>
                </div>

                <!-- Deskripsi / Petunjuk Kuis -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Deskripsi & Petunjuk Pengerjaan
                    </label>
                    <textarea name="deskripsi" 
                              rows="3" 
                              placeholder="Tuliskan petunjuk kuis atau aturan pengerjaan untuk siswa..."
                              class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all resize-y">{{ old('deskripsi') }}</textarea>
                </div>
            </div>
        </div>

        <!-- SEKSI 2: PEMBUAT BUTIR SOAL KUIS (DYNAMIC QUESTION BUILDER) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="02" 
                title="Butir Pertanyaan Kuis" 
                subtitle="Dukung pilihan ganda biasa, teks bacaan (reading passage), dan audio listening" 
                color="purple"
            >
                <!-- Tombol Tambah Soal -->
                <button type="button" 
                        onclick="tambahSoal()"
                        class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-semibold text-xs flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Soal
                </button>
            </x-section-header>

            <!-- Wadah Kartu Soal -->
            <div id="soal-container" class="space-y-5">
                @for ($i = 0; $i < 5; $i++)
                    <x-soal-card :index="$i" :with-reading="true" :with-audio="true" :with-audio-title="true" />
                @endfor
            </div>
        </div>

        <!-- Tombol Aksi Simpan & Batal -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <x-button variant="cancel" href="{{ route('guru.quiz.index') }}">
                Batal
            </x-button>
            <x-button variant="submit">
                Simpan & Terbitkan Paket Kuis
            </x-button>
        </div>
    </form>

    <!-- Template Kartu Soal untuk Penambahan Dinamis -->
    <template id="soal-template">
        <x-soal-card index="__INDEX__" number="__NUMBER__" :with-reading="true" :with-audio="true" :with-audio-title="true" />
    </template>

    <!-- Script Interaktif JS -->
    <script>
        function acakKodeRoom() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let res = '';
            for (let i = 0; i < 6; i++) {
                res += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('kode_room_input').value = res;
        }

        function gantiTipeSoal(select, index) {
            const readingWrapper = document.getElementById(`reading-passage-wrapper-${index}`);
            const listeningWrapper = document.getElementById(`listening-audio-wrapper-${index}`);

            if (readingWrapper) {
                if (select.value === 'reading') {
                    readingWrapper.classList.remove('hidden');
                } else {
                    readingWrapper.classList.add('hidden');
                }
            }

            if (listeningWrapper) {
                if (select.value === 'listening') {
                    listeningWrapper.classList.remove('hidden');
                } else {
                    listeningWrapper.classList.add('hidden');
                }
            }
        }

        function previewAudioSoal(input, index) {
            const audio = document.getElementById(`audio-preview-${index}`);
            if (audio && input.files && input.files[0]) {
                audio.src = URL.createObjectURL(input.files[0]);
                audio.classList.remove('hidden');
            }
        }

        let soalCounter = 5;

        function updateNomorSoal() {
            const container = document.getElementById('soal-container');
            const cards = container.querySelectorAll('.soal-card');
            cards.forEach((card, idx) => {
                const numBadge = card.querySelector('.soal-number');
                if (numBadge) {
                    numBadge.textContent = idx + 1;
                }
            });
        }

        function hapusSoal(button) {
            const card = button.closest('.soal-card');
            const container = document.getElementById('soal-container');
            if (container.querySelectorAll('.soal-card').length <= 1) {
                alert('Minimal kuis harus memiliki 1 butir pertanyaan!');
                return;
            }
            if (card) {
                card.remove();
                updateNomorSoal();
            }
        }

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const template = document.getElementById('soal-template');
            if (!template) return;
            const newIndex = soalCounter++;
            const newNum = container.querySelectorAll('.soal-card').length + 1;
            const html = template.innerHTML.replaceAll('__INDEX__', newIndex).replaceAll('__NUMBER__', newNum);
            container.insertAdjacentHTML('beforeend', html);
        }
    </script>
</x-layout>
