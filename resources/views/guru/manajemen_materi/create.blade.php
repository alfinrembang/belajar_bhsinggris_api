<x-layout 
    title="Tambah Materi Baru - Study English Prima" 
    pageTitle="Tambah Materi Pembelajaran" 
    pageSubtitle="Buat modul pembelajaran baru lengkap dengan penjelasan teori, audio listening, dan latihan soal." 
    active="materi"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6">
        <a href="{{ route('guru.materi.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Materi
        </a>
    </div>

    <!-- Alert Error Validasi -->
    @if ($errors->any())
        <div class="mb-6 p-4.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs">
            <p class="font-bold mb-1.5 flex items-center gap-1.5">
                <span>⚠️</span> Mohon lengkapi formulir dengan benar:
            </p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guru.materi.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- SEKSI 1: INFORMASI MODUL & TEORI -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-sm">
                    01
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Informasi Utama & Penjelasan Teori</h3>
                    <p class="text-xs text-slate-400">Pengaturan judul modul, kategori, kelas target, dan konsep tata bahasa</p>
                </div>
            </div>

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

        <!-- SEKSI 2: CONTOH TEKS BACAAN & MEDIA (GAMBAR & AUDIO) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    02
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Contoh Teks Bacaan & Media (Gambar & Audio)</h3>
                    <p class="text-xs text-slate-400">Contoh penerapan bacaan nyata dilengkapi rekaman audio pengucapan native speaker</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Contoh Teks Bacaan / Dialog -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Contoh Teks Bacaan / Paragraf / Percakapan
                    </label>
                    <textarea name="contoh_teks" 
                              rows="5" 
                              placeholder="Tuliskan contoh teks bacaan dalam bahasa Inggris yang berkaitan dengan materi di atas..." 
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all leading-relaxed">{{ old('contoh_teks') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                    <!-- Upload Gambar Ilustrasi Teks -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            🖼️ Gambar Ilustrasi / Cover Materi
                        </label>
                        <p class="text-[11px] text-slate-400 mb-3">Format: JPG, PNG, WEBP (Maks 3MB)</p>
                        <input type="file" 
                               name="gambar_materi" 
                               accept="image/*"
                               onchange="previewImage(this)"
                               class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-[#1E6BFF] hover:file:bg-blue-100 cursor-pointer">
                        <div id="image-preview-container" class="hidden mt-3">
                            <img id="image-preview" src="#" alt="Preview Gambar" class="h-32 rounded-xl object-cover border border-slate-200">
                        </div>
                    </div>

                    <!-- Upload Audio Pelafalan Contoh Teks -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/70">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">
                            🎧 File Audio Pelafalan Teks (Listening)
                        </label>
                        <p class="text-[11px] text-slate-400 mb-3">Format: MP3, WAV, M4A (Maks 15MB)</p>
                        <input type="file" 
                               name="audio_materi" 
                               accept="audio/*"
                               onchange="previewAudio(this)"
                               class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-600 hover:file:bg-emerald-100 cursor-pointer">
                        <div id="audio-preview-container" class="hidden mt-3">
                            <audio id="audio-preview" controls class="w-full h-9"></audio>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SEKSI 3: LATIHAN SOAL PEMAHAMAN (CAMPURAN PG & LISTENING) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                        03
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Latihan Soal Pemahaman (Mini Quiz)</h3>
                        <p class="text-xs text-slate-400">Buat butir soal evaluasi pemahaman (bisa campuran Pilihan Ganda & Listening)</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="tambahSoal()"
                        class="px-4 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold text-xs transition-colors flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    + Tambah Soal
                </button>
            </div>

            <!-- Wadah Butir Soal -->
            <div id="soal-container" class="space-y-4">
                <!-- 5 Butir Soal Default di-generate secara rapi -->
                @for ($i = 0; $i < 5; $i++)
                    <div class="soal-card p-5 rounded-2xl border border-slate-200/80 bg-slate-50/40 space-y-4" data-index="{{ $i }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center soal-number">
                                    {{ $i + 1 }}
                                </span>
                                <span class="font-bold text-slate-800 text-xs">Butir Pertanyaan</span>
                            </div>

                            <div class="flex items-center gap-3">
                                <!-- Pilihan Tipe Soal -->
                                <select name="soals[{{ $i }}][tipe_soal]" 
                                        onchange="toggleAudioSoal(this, {{ $i }})"
                                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 outline-none cursor-pointer">
                                    <option value="pilgan" selected>Pilihan Ganda (Teks)</option>
                                    <option value="listening">Listening (Ada Audio)</option>
                                </select>

                                <!-- Tombol Hapus Butir -->
                                <button type="button" onclick="hapusSoal(this)" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Butir Soal">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Pertanyaan -->
                        <div>
                            <input type="text" 
                                   name="soals[{{ $i }}][pertanyaan]" 
                                   placeholder="Ketikkan teks pertanyaan di sini..." 
                                   class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                        </div>

                        <!-- Input File Audio khusus tipe listening (tersembunyi default) -->
                        <div id="audio-soal-wrapper-{{ $i }}" class="hidden p-3 rounded-xl bg-purple-50/60 border border-purple-100">
                            <label class="block text-[11px] font-semibold text-purple-800 mb-1">🎧 Upload File Audio Pertanyaan Listening (MP3):</label>
                            <input type="file" 
                                   name="soals[{{ $i }}][audio_soal]" 
                                   accept="audio/*" 
                                   class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-purple-100 file:text-purple-700 cursor-pointer">
                        </div>

                        <!-- 4 Pilihan Jawaban A, B, C, D -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="font-bold text-xs text-slate-400 w-4">A.</span>
                                <input type="text" name="soals[{{ $i }}][pilihan_a]" placeholder="Pilihan jawaban A" class="w-full text-xs text-slate-800 outline-none">
                            </div>
                            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="font-bold text-xs text-slate-400 w-4">B.</span>
                                <input type="text" name="soals[{{ $i }}][pilihan_b]" placeholder="Pilihan jawaban B" class="w-full text-xs text-slate-800 outline-none">
                            </div>
                            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="font-bold text-xs text-slate-400 w-4">C.</span>
                                <input type="text" name="soals[{{ $i }}][pilihan_c]" placeholder="Pilihan jawaban C" class="w-full text-xs text-slate-800 outline-none">
                            </div>
                            <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                                <span class="font-bold text-xs text-slate-400 w-4">D.</span>
                                <input type="text" name="soals[{{ $i }}][pilihan_d]" placeholder="Pilihan jawaban D" class="w-full text-xs text-slate-800 outline-none">
                            </div>
                        </div>

                        <!-- Kunci Jawaban & Pembahasan -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kunci Jawaban Benar:</label>
                                <select name="soals[{{ $i }}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-[#1E6BFF] outline-none">
                                    <option value="A">Kunci Jawaban: A</option>
                                    <option value="B">Kunci Jawaban: B</option>
                                    <option value="C">Kunci Jawaban: C</option>
                                    <option value="D">Kunci Jawaban: D</option>
                                </select>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pembahasan Singkat (Opsional):</label>
                                <input type="text" name="soals[{{ $i }}][pembahasan]" placeholder="Penjelasan kenapa jawaban tersebut benar..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-700 outline-none">
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <a href="{{ route('guru.materi.index') }}" class="px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold text-xs transition-colors">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-2xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white font-semibold text-xs shadow-[0_6px_16px_rgba(30,107,255,0.28)] transition-all cursor-pointer">
                Simpan & Publikasikan Modul
            </button>
        </div>
    </form>

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

        function previewAudio(input) {
            const preview = document.getElementById('audio-preview');
            const container = document.getElementById('audio-preview-container');
            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                container.classList.remove('hidden');
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

        let soalCount = 5;

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const newIndex = soalCount;
            const newNum = container.children.length + 1;

            const html = `
                <div class="soal-card p-5 rounded-2xl border border-slate-200/80 bg-slate-50/40 space-y-4" data-index="${newIndex}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-lg bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center soal-number">
                                ${newNum}
                            </span>
                            <span class="font-bold text-slate-800 text-xs">Butir Pertanyaan</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <select name="soals[${newIndex}][tipe_soal]" 
                                    onchange="toggleAudioSoal(this, ${newIndex})"
                                    class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 outline-none cursor-pointer">
                                <option value="pilgan" selected>Pilihan Ganda (Teks)</option>
                                <option value="listening">Listening (Ada Audio)</option>
                            </select>
                            <button type="button" onclick="hapusSoal(this)" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Butir Soal">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <input type="text" name="soals[${newIndex}][pertanyaan]" placeholder="Ketikkan teks pertanyaan di sini..." class="w-full px-4 py-2.5 rounded-xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                    </div>
                    <div id="audio-soal-wrapper-${newIndex}" class="hidden p-3 rounded-xl bg-purple-50/60 border border-purple-100">
                        <label class="block text-[11px] font-semibold text-purple-800 mb-1">🎧 Upload File Audio Pertanyaan Listening (MP3):</label>
                        <input type="file" name="soals[${newIndex}][audio_soal]" accept="audio/*" class="text-xs text-slate-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-purple-100 file:text-purple-700 cursor-pointer">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                            <span class="font-bold text-xs text-slate-400 w-4">A.</span>
                            <input type="text" name="soals[${newIndex}][pilihan_a]" placeholder="Pilihan jawaban A" class="w-full text-xs text-slate-800 outline-none">
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                            <span class="font-bold text-xs text-slate-400 w-4">B.</span>
                            <input type="text" name="soals[${newIndex}][pilihan_b]" placeholder="Pilihan jawaban B" class="w-full text-xs text-slate-800 outline-none">
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                            <span class="font-bold text-xs text-slate-400 w-4">C.</span>
                            <input type="text" name="soals[${newIndex}][pilihan_c]" placeholder="Pilihan jawaban C" class="w-full text-xs text-slate-800 outline-none">
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-xl border border-slate-200">
                            <span class="font-bold text-xs text-slate-400 w-4">D.</span>
                            <input type="text" name="soals[${newIndex}][pilihan_d]" placeholder="Pilihan jawaban D" class="w-full text-xs text-slate-800 outline-none">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Kunci Jawaban Benar:</label>
                            <select name="soals[${newIndex}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-[#1E6BFF] outline-none">
                                <option value="A">Kunci Jawaban: A</option>
                                <option value="B">Kunci Jawaban: B</option>
                                <option value="C">Kunci Jawaban: C</option>
                                <option value="D">Kunci Jawaban: D</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pembahasan Singkat (Opsional):</label>
                            <input type="text" name="soals[${newIndex}][pembahasan]" placeholder="Penjelasan kenapa jawaban tersebut benar..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-700 outline-none">
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            soalCount++;
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
