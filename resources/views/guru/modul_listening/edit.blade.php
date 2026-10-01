<x-layout 
    title="Edit Modul Listening - Study English Prima" 
    pageTitle="Edit Modul Listening" 
    pageSubtitle="Perbarui informasi percakapan, file audio rekaman, transkrip dialog, atau latihan soal." 
    active="listening"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('guru.listening.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-[#1E6BFF] transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Kembali ke Daftar Listening
        </a>

        <a href="{{ route('guru.listening.show', $listening->id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#1E6BFF] hover:underline">
            Lihat Pratinjau Player &rarr;
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
    <form action="{{ route('guru.listening.update', $listening->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. INFORMASI UTAMA & SKENARIO -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-sm">
                    01
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">Informasi Utama Percakapan</h3>
                    <p class="text-xs text-slate-400">Judul skenario dialog, tingkat kesulitan audio, dan target kelas siswa</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Judul Listening -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Judul Percakapan / Topik Listening <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           value="{{ old('judul', $listening->judul) }}"
                           placeholder="Contoh: At the Airport & Checking In" 
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] focus:ring-2 focus:ring-[#1E6BFF]/15 outline-none transition-all">
                </div>

                <!-- Tingkat Kesulitan -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tingkat Kesulitan Audio <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2.5">
                        <label class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition-all hover:bg-slate-50 border-slate-200/80 has-checked:border-[#1E6BFF] has-checked:bg-blue-50/40 has-checked:ring-2 has-checked:ring-[#1E6BFF]/20 text-center">
                            <input type="radio" name="tingkat_kesulitan" value="Beginner" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Beginner' ? 'checked' : '' }} class="sr-only">
                            <span class="text-base mb-0.5">🔵</span>
                            <span class="text-xs font-bold text-slate-800">Beginner</span>
                            <span class="text-[10px] text-slate-400">Pemula</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition-all hover:bg-slate-50 border-slate-200/80 has-checked:border-purple-600 has-checked:bg-purple-50/40 has-checked:ring-2 has-checked:ring-purple-600/20 text-center">
                            <input type="radio" name="tingkat_kesulitan" value="Intermediate" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Intermediate' ? 'checked' : '' }} class="sr-only">
                            <span class="text-base mb-0.5">🟣</span>
                            <span class="text-xs font-bold text-slate-800">Intermediate</span>
                            <span class="text-[10px] text-slate-400">Menengah</span>
                        </label>
                        <label class="flex flex-col items-center justify-center p-3 rounded-2xl border cursor-pointer transition-all hover:bg-slate-50 border-slate-200/80 has-checked:border-rose-600 has-checked:bg-rose-50/40 has-checked:ring-2 has-checked:ring-rose-600/20 text-center">
                            <input type="radio" name="tingkat_kesulitan" value="Advanced" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Advanced' ? 'checked' : '' }} class="sr-only">
                            <span class="text-base mb-0.5">🔴</span>
                            <span class="text-xs font-bold text-slate-800">Advanced</span>
                            <span class="text-[10px] text-slate-400">Lanjutan</span>
                        </label>
                    </div>
                </div>

                <!-- Target Tingkat Kelas -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Tingkat Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_kelas" required class="w-full px-4 py-3 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="Semua Kelas" {{ old('tingkat_kelas', $listening->tingkat_kelas) == 'Semua Kelas' ? 'selected' : '' }}>Semua Tingkat Kelas (Umum)</option>
                        <option value="10" {{ old('tingkat_kelas', $listening->tingkat_kelas) == '10' ? 'selected' : '' }}>Khusus Kelas 10</option>
                        <option value="11" {{ old('tingkat_kelas', $listening->tingkat_kelas) == '11' ? 'selected' : '' }}>Khusus Kelas 11</option>
                        <option value="12" {{ old('tingkat_kelas', $listening->tingkat_kelas) == '12' ? 'selected' : '' }}>Khusus Kelas 12</option>
                    </select>
                </div>

                <!-- Ringkasan Konteks Percakapan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Ringkasan Skenario Percakapan
                    </label>
                    <textarea name="deskripsi" 
                              rows="2" 
                              placeholder="Tuliskan latar belakang singkat skenario audio ini..." 
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none transition-all leading-relaxed">{{ old('deskripsi', $listening->deskripsi) }}</textarea>
                </div>

                <!-- XP Reward & Status -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Hadiah XP Belajar Siswa
                    </label>
                    <input type="number" 
                           name="xp_reward" 
                           value="{{ old('xp_reward', $listening->xp_reward) }}"
                           min="0"
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Tayang Modul
                    </label>
                    <select name="status" class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 font-medium focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="aktif" {{ old('status', $listening->status) == 'aktif' ? 'selected' : '' }}>Aktif (Langsung Tayang di Aplikasi Siswa)</option>
                        <option value="draft" {{ old('status', $listening->status) == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. FILE AUDIO, PRATINJAU PLAYER & TRANSKRIP -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    02
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-base">File Audio & Transkrip Percakapan</h3>
                    <p class="text-xs text-slate-400">Unggah file audio baru jika ingin mengganti rekaman sebelumnya</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Pemutar Audio Saat Ini -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        File Audio Saat Ini
                    </label>
                    @if($listening->audio_url)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 mb-3 space-y-2">
                            <p class="text-xs font-bold text-slate-700 flex items-center gap-2">
                                <span>🎧</span> {{ $listening->audio_title ?? 'Audio Track Percakapan' }}
                            </p>
                            <audio controls class="w-full h-8" src="{{ $listening->audio_url }}"></audio>
                        </div>
                    @endif

                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Ganti File Audio (MP3 / WAV / M4A)
                    </label>
                    <input type="file" 
                           id="audio-file-input"
                           name="audio_file" 
                           accept="audio/mp3,audio/wav,audio/m4a,audio/aac,audio/ogg" 
                           onchange="handleAudioPreview(this)"
                           class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                    <p class="text-[11px] text-slate-400 mt-1">Kosongkan jika tidak ingin mengganti audio.</p>

                    <!-- Pemutar Audio Preview Pengganti -->
                    <div id="audio-preview-container" class="mt-3 hidden p-3 rounded-2xl bg-blue-50 border border-blue-200">
                        <p class="text-[11px] font-bold text-blue-700 mb-1.5 flex items-center gap-1.5">
                            <span>🔊</span> Audio Baru yang Dipilih:
                        </p>
                        <audio id="audio-preview-player" controls class="w-full h-8"></audio>
                    </div>
                </div>

                <!-- Input Durasi & Label Audio -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Perkiraan Durasi Audio (Contoh: 02:15, 03:00)
                        </label>
                        <input type="text" 
                               id="durasi-input"
                               name="durasi" 
                               value="{{ old('durasi', $listening->durasi) }}"
                               placeholder="Contoh: 02:15"
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Atau Masukkan URL Audio Eksternal (Opsional)
                        </label>
                        <input type="url" 
                               name="audio_url_input" 
                               value="{{ old('audio_url_input') }}"
                               placeholder="https://domain.com/percakapan.mp3"
                               class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                    </div>
                </div>

                <!-- Transkrip Naskah Percakapan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Transkrip Naskah Percakapan Lengkap
                    </label>
                    <textarea name="transkrip" 
                              rows="6" 
                              placeholder="Tuliskan naskah dialog percakapan di sini..." 
                              class="w-full p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none transition-all leading-relaxed font-mono text-[12px]">{{ old('transkrip', $listening->transkrip) }}</textarea>
                </div>

                <!-- Poin Sasaran Pembelajaran (Step 1 Checklist di Siswa) -->
                @php
                    $tujuan = $listening->tujuan_belajar ?? [];
                @endphp
                <div class="md:col-span-2 space-y-2">
                    <label class="block text-xs font-semibold text-slate-700">
                        Sasaran & Tujuan Belajar (Tampil di Langkah 1 Aplikasi Siswa)
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="text" 
                               name="tujuan_belajar[]" 
                               value="{{ old('tujuan_belajar.0', $tujuan[0] ?? 'Memahami konteks utama percakapan audio') }}"
                               placeholder="Poin sasaran 1" 
                               class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                        <input type="text" 
                               name="tujuan_belajar[]" 
                               value="{{ old('tujuan_belajar.1', $tujuan[1] ?? 'Mengidentifikasi detail informasi penting dalam dialog') }}"
                               placeholder="Poin sasaran 2" 
                               class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                        <input type="text" 
                               name="tujuan_belajar[]" 
                               value="{{ old('tujuan_belajar.2', $tujuan[2] ?? 'Menjawab butir latihan soal pemahaman listening') }}"
                               placeholder="Poin sasaran 3" 
                               class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. BUILDER LATIHAN SOAL PEMAHAMAN LISTENING (STEP 2 - 5) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100 flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                        03
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-base">Butir Soal Latihan Pemahaman Audio</h3>
                        <p class="text-xs text-slate-400">Pertanyaan interaktif yang dikerjakan siswa di Step 2 s/d Step 5</p>
                    </div>
                </div>

                <button type="button" 
                        onclick="tambahSoal()"
                        class="px-4 py-2 rounded-2xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-semibold text-xs transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Tambah Pertanyaan
                </button>
            </div>

            <!-- Wadah Kartu Butir Soal -->
            <div id="soal-container" class="space-y-5">
                @forelse($listening->soals as $index => $soal)
                    <div class="soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                                Pertanyaan #{{ $index + 1 }}
                            </span>
                            <button type="button" onclick="hapusSoal(this)" class="text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1">
                                Hapus Soal
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Kalimat Pertanyaan Listening <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   name="soals[{{ $index }}][pertanyaan]" 
                                   value="{{ old('soals.'.$index.'.pertanyaan', $soal->pertanyaan) }}"
                                   placeholder="Contoh: What is the main topic of the conversation?" 
                                   required
                                   class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 outline-none focus:border-[#1E6BFF]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan A</label>
                                <input type="text" name="soals[{{ $index }}][pilihan_a]" value="{{ old('soals.'.$index.'.pilihan_a', $soal->pilihan_a) }}" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan B</label>
                                <input type="text" name="soals[{{ $index }}][pilihan_b]" value="{{ old('soals.'.$index.'.pilihan_b', $soal->pilihan_b) }}" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan C</label>
                                <input type="text" name="soals[{{ $index }}][pilihan_c]" value="{{ old('soals.'.$index.'.pilihan_c', $soal->pilihan_c) }}" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            </div>
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan D</label>
                                <input type="text" name="soals[{{ $index }}][pilihan_d]" value="{{ old('soals.'.$index.'.pilihan_d', $soal->pilihan_d) }}" required class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                                    Kunci Jawaban Benar <span class="text-rose-500">*</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    @foreach(['A', 'B', 'C', 'D'] as $opt)
                                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                            <input type="radio" 
                                                   name="soals[{{ $index }}][jawaban_benar]" 
                                                   value="{{ $opt }}" 
                                                   {{ old('soals.'.$index.'.jawaban_benar', $soal->jawaban_benar) == $opt ? 'checked' : '' }} 
                                                   class="text-[#1E6BFF]">
                                            {{ $opt }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                                    Pembahasan / Catatan Penjelasan (Opsional)
                                </label>
                                <input type="text" 
                                       name="soals[{{ $index }}][pembahasan]" 
                                       value="{{ old('soals.'.$index.'.pembahasan', $soal->pembahasan) }}"
                                       placeholder="Alasan mengapa opsi ini yang tepat..." 
                                       class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback jika belum ada soal -->
                    <div class="soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                                Pertanyaan #1
                            </span>
                            <button type="button" onclick="hapusSoal(this)" class="text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1">
                                Hapus Soal
                            </button>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1">
                                Kalimat Pertanyaan Listening <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="soals[0][pertanyaan]" required placeholder="Contoh: What is the main topic?" class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" name="soals[0][pilihan_a]" required placeholder="Pilihan A" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            <input type="text" name="soals[0][pilihan_b]" required placeholder="Pilihan B" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            <input type="text" name="soals[0][pilihan_c]" required placeholder="Pilihan C" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                            <input type="text" name="soals[0][pilihan_d]" required placeholder="Pilihan D" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <span class="text-[11px] font-semibold text-slate-700">Kunci Jawaban:</span>
                            @foreach(['A', 'B', 'C', 'D'] as $opt)
                                <label class="flex items-center gap-1 text-xs font-bold cursor-pointer">
                                    <input type="radio" name="soals[0][jawaban_benar]" value="{{ $opt }}" {{ $opt === 'A' ? 'checked' : '' }}>
                                    {{ $opt }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tombol Aksi Simpan -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('guru.listening.index') }}" class="px-6 py-3 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-8 py-3 rounded-2xl bg-[#1E6BFF] hover:bg-blue-600 text-white font-bold text-xs shadow-[0_6px_20px_rgba(30,107,255,0.28)] transition-all cursor-pointer">
                Simpan Perubahan Modul
            </button>
        </div>
    </form>

    <script>
        let soalIndex = {{ count($listening->soals) > 0 ? count($listening->soals) : 1 }};

        function handleAudioPreview(input) {
            const file = input.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                const player = document.getElementById('audio-preview-player');
                const container = document.getElementById('audio-preview-container');
                player.src = url;
                container.classList.remove('hidden');

                player.onloadedmetadata = function() {
                    const minutes = Math.floor(player.duration / 60);
                    const seconds = Math.floor(player.duration % 60);
                    const formatted = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                    const durasiInput = document.getElementById('durasi-input');
                    if (durasiInput) {
                        durasiInput.value = formatted;
                    }
                };
            }
        }

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const currentIndex = soalIndex++;
            const nomorDisplay = container.children.length + 1;

            const div = document.createElement('div');
            div.className = 'soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                        Pertanyaan #${nomorDisplay}
                    </span>
                    <button type="button" onclick="hapusSoal(this)" class="text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1">
                        Hapus Soal
                    </button>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Kalimat Pertanyaan Listening <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="soals[${currentIndex}][pertanyaan]" 
                           placeholder="Contoh: What is the speaker talking about?" 
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 outline-none focus:border-[#1E6BFF]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan A</label>
                        <input type="text" name="soals[${currentIndex}][pilihan_a]" required placeholder="Pilihan jawaban A" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan B</label>
                        <input type="text" name="soals[${currentIndex}][pilihan_b]" required placeholder="Pilihan jawaban B" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan C</label>
                        <input type="text" name="soals[${currentIndex}][pilihan_c]" required placeholder="Pilihan jawaban C" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Pilihan D</label>
                        <input type="text" name="soals[${currentIndex}][pilihan_d]" required placeholder="Pilihan jawaban D" class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Kunci Jawaban Benar <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-3">
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="radio" name="soals[${currentIndex}][jawaban_benar]" value="A" checked class="text-[#1E6BFF]">
                                A
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="radio" name="soals[${currentIndex}][jawaban_benar]" value="B" class="text-[#1E6BFF]">
                                B
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="radio" name="soals[${currentIndex}][jawaban_benar]" value="C" class="text-[#1E6BFF]">
                                C
                            </label>
                            <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 cursor-pointer">
                                <input type="radio" name="soals[${currentIndex}][jawaban_benar]" value="D" class="text-[#1E6BFF]">
                                D
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">
                            Pembahasan / Catatan Penjelasan (Opsional)
                        </label>
                        <input type="text" 
                               name="soals[${currentIndex}][pembahasan]" 
                               placeholder="Alasan mengapa opsi ini yang tepat..." 
                               class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs outline-none">
                    </div>
                </div>
            `;
            container.appendChild(div);
            updateLabels();
        }

        function hapusSoal(btn) {
            const card = btn.closest('.soal-card');
            const container = document.getElementById('soal-container');
            if (container.children.length > 1) {
                card.remove();
                updateLabels();
            } else {
                alert('Setidaknya harus ada minimal 1 pertanyaan listening!');
            }
        }

        function updateLabels() {
            const labels = document.querySelectorAll('.nomor-label');
            labels.forEach((label, idx) => {
                label.innerText = `Pertanyaan #${idx + 1}`;
            });
        }
    </script>
</x-layout>
