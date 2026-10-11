<x-layout 
    title="Edit Modul Listening: {{ $listening->judul }} - Study English Prima" 
    pageTitle="Edit Modul Audio Listening" 
    pageSubtitle="Perbarui rekaman audio percakapan, pengaturan kelas, dan butir latihan soal listening." 
    active="listening"
>
    <!-- Navigasi Kembali -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <x-button variant="back" href="{{ route('guru.listening.index') }}">
            Kembali ke Daftar Modul Listening
        </x-button>
        <x-button variant="show" href="{{ route('guru.listening.show', $listening->id) }}">
            Pratinjau Modul
        </x-button>
    </div>

    <!-- Alert Error Validasi -->
    <x-alert-errors title="Mohon periksa kembali input formulir:" />

    <form action="{{ route('guru.listening.update', $listening->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- 1. INFORMASI DASAR MODUL -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="01" 
                title="Informasi Utama Modul" 
                subtitle="Judul, target kelas, dan tingkat kesulitan listening" 
                color="blue" 
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Judul Percakapan / Topik Listening <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="judul" 
                           value="{{ old('judul', $listening->judul) }}"
                           placeholder="Contoh: Conversation at the Airport: Booking a Ticket" 
                           required
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none transition-all">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Deskripsi atau Arahan untuk Siswa
                    </label>
                    <textarea name="deskripsi" 
                              rows="2" 
                              placeholder="Deskripsi singkat materi listening..." 
                              class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none transition-all">{{ old('deskripsi', $listening->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Tingkat Kesulitan <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_kesulitan" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="Beginner" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Beginner' ? 'selected' : '' }}>🟢 Beginner (Mudah - Pemula)</option>
                        <option value="Intermediate" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Intermediate' ? 'selected' : '' }}>🟡 Intermediate (Menengah)</option>
                        <option value="Advanced" {{ old('tingkat_kesulitan', $listening->tingkat_kesulitan) == 'Advanced' ? 'selected' : '' }}>🔴 Advanced (Tingkat Lanjut)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Target Kelas <span class="text-rose-500">*</span>
                    </label>
                    <select name="tingkat_kelas" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                        <option value="Semua Kelas" {{ old('tingkat_kelas', $listening->tingkat_kelas) == 'Semua Kelas' ? 'selected' : '' }}>Semua Kelas (Umum)</option>
                        <option value="Kelas 10" {{ old('tingkat_kelas', $listening->tingkat_kelas) == 'Kelas 10' ? 'selected' : '' }}>Kelas 10 SMA/SMK</option>
                        <option value="Kelas 11" {{ old('tingkat_kelas', $listening->tingkat_kelas) == 'Kelas 11' ? 'selected' : '' }}>Kelas 11 SMA/SMK</option>
                        <option value="Kelas 12" {{ old('tingkat_kelas', $listening->tingkat_kelas) == 'Kelas 12' ? 'selected' : '' }}>Kelas 12 SMA/SMK</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Reward Poin XP Penyelesaian
                    </label>
                    <input type="number" 
                           name="xp_reward" 
                           value="{{ old('xp_reward', $listening->xp_reward) }}" 
                           min="10" 
                           step="5"
                           class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Status Publikasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2.5 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#1E6BFF] outline-none font-semibold">
                        <option value="aktif" {{ old('status', $listening->status) == 'aktif' ? 'selected' : '' }}>Aktif (Tampil di Aplikasi Siswa)</option>
                        <option value="draft" {{ old('status', $listening->status) == 'draft' ? 'selected' : '' }}>Draft (Simpan Sementara)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 2. DAFTAR FILE AUDIO PERCAKAPAN (MULTI-TRACK AUDIO) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="02" 
                title="Daftar File Audio / Rekaman Percakapan" 
                subtitle="Satu audio bisa digunakan untuk beberapa soal. Kamu bisa menambahkan atau menghapus rekaman audio." 
                color="purple"
            >
                <button type="button" 
                        onclick="tambahAudioTrack()" 
                        class="px-4 py-2 rounded-2xl bg-purple-50 text-purple-700 hover:bg-purple-100 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <span>+</span> Tambah Track Audio Baru
                </button>
            </x-section-header>

            <!-- Wadah Kartu Audio Tracks -->
            <div id="audio-tracks-container" class="space-y-4">
                @forelse($listening->audios as $idx => $audio)
                    <div class="audio-track-card p-5 rounded-3xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative" data-track="{{ $audio->nomor_track }}">
                        <input type="hidden" name="audios[{{ $idx }}][id]" value="{{ $audio->id }}">
                        <input type="hidden" name="audios[{{ $idx }}][is_deleted]" class="track-deleted-input" value="0">
                        <input type="hidden" name="audios[{{ $idx }}][existing_audio_file]" value="{{ $audio->audio_file }}">

                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-purple-600 text-white font-bold text-xs track-badge">
                                🎧 Audio Track #{{ $audio->nomor_track }}
                            </span>
                            <button type="button" onclick="hapusAudioTrack(this)" class="tombol-hapus-track text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1 cursor-pointer">
                                🗑️ Hapus Track
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Judul / Nama Rekaman Audio
                                </label>
                                <input type="text" 
                                       name="audios[{{ $idx }}][judul_audio]" 
                                       value="{{ old('audios.'.$idx.'.judul_audio', $audio->judul_audio) }}"
                                       placeholder="Contoh: Percakapan 1: Di Bandara" 
                                       oninput="syncAudioDropdowns()"
                                       class="track-title-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Perkiraan Durasi
                                </label>
                                <input type="text" 
                                       name="audios[{{ $idx }}][durasi]" 
                                       value="{{ old('audios.'.$idx.'.durasi', $audio->durasi) }}"
                                       placeholder="Contoh: 01:45" 
                                       class="track-durasi-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                            </div>

                            <!-- Pemutar Audio Saat Ini -->
                            <div class="md:col-span-2 p-3 rounded-2xl bg-white border border-slate-200/90 space-y-2">
                                <div class="flex items-center justify-between text-xs font-semibold text-slate-700">
                                    <span class="flex items-center gap-1.5 text-purple-700 font-bold">
                                        <span>🔊</span> Audio Tersimpan Saat Ini:
                                    </span>
                                </div>
                                <audio controls class="w-full h-8" src="{{ $audio->audio_url }}"></audio>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                    Ganti File Audio (Kosongkan jika tidak ingin mengganti)
                                </label>
                                <input type="file" 
                                       name="audios[{{ $idx }}][audio_file]" 
                                       accept="audio/*,.mp3,.wav,.m4a,.aac,.ogg,.opus,.flac" 
                                       onchange="handleTrackAudioPreview(this)"
                                       class="w-full px-4 py-2 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                            </div>
                        </div>

                        <div class="track-preview-container hidden p-3 rounded-2xl bg-blue-50 border border-blue-200">
                            <div class="flex items-center justify-between mb-1.5">
                                <p class="text-[11px] font-bold text-blue-700 flex items-center gap-1.5">
                                    <span>🔊</span> File Audio Baru Pengganti:
                                </p>
                                <button type="button" onclick="batalPilihTrackAudio(this)" class="text-rose-600 hover:text-rose-700 text-[11px] font-bold cursor-pointer">
                                    ✕ Batal Ganti File
                                </button>
                            </div>
                            <audio controls class="w-full h-8 track-player"></audio>
                        </div>
                    </div>
                @empty
                    <!-- Fallback jika belum ada data audio di relasi audios -->
                    <div class="audio-track-card p-5 rounded-3xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative" data-track="1">
                        <input type="hidden" name="audios[0][is_deleted]" class="track-deleted-input" value="0">
                        <input type="hidden" name="audios[0][existing_audio_file]" value="{{ $listening->audio_file }}">

                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-purple-600 text-white font-bold text-xs track-badge">
                                🎧 Audio Track #1
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Audio</label>
                                <input type="text" name="audios[0][judul_audio]" value="{{ $listening->audio_title ?: 'Audio Utama' }}" oninput="syncAudioDropdowns()" class="track-title-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Durasi</label>
                                <input type="text" name="audios[0][durasi]" value="{{ $listening->durasi }}" class="track-durasi-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200 text-xs">
                            </div>
                            <div class="md:col-span-2">
                                <input type="file" name="audios[0][audio_file]" accept="audio/*,.mp3,.wav,.m4a,.aac,.ogg,.opus,.flac" onchange="handleTrackAudioPreview(this)" class="w-full px-4 py-2 rounded-2xl bg-white border border-slate-200 text-xs">
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Sasaran & Tujuan Belajar -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <label class="block text-xs font-semibold text-slate-700">
                    🎯 Sasaran & Tujuan Belajar (Tampil di Langkah 1 Aplikasi Siswa)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input type="text" 
                           name="tujuan_belajar[]" 
                           value="{{ old('tujuan_belajar.0', $listening->tujuan_belajar[0] ?? 'Memahami konteks utama percakapan audio') }}"
                           placeholder="Poin sasaran 1" 
                           class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                    <input type="text" 
                           name="tujuan_belajar[]" 
                           value="{{ old('tujuan_belajar.1', $listening->tujuan_belajar[1] ?? 'Mengidentifikasi detail informasi penting dalam dialog') }}"
                           placeholder="Poin sasaran 2" 
                           class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                    <input type="text" 
                           name="tujuan_belajar[]" 
                           value="{{ old('tujuan_belajar.2', $listening->tujuan_belajar[2] ?? 'Menjawab butir latihan soal pemahaman listening') }}"
                           placeholder="Poin sasaran 3" 
                           class="w-full px-4 py-2 rounded-2xl bg-slate-50 border border-slate-200/80 text-xs text-slate-800 outline-none">
                </div>
            </div>
        </div>

        <!-- 3. BUILDER LATIHAN SOAL PEMAHAMAN LISTENING (STEP 2 - 5) -->
        <div class="bg-white p-6 lg:p-7 rounded-3xl border border-slate-100 shadow-xs space-y-5">
            <x-section-header 
                number="03" 
                title="Butir Soal Latihan Pemahaman Audio" 
                subtitle="Pilih audio yang sesuai untuk tiap butir pertanyaan di bawah ini" 
                color="emerald"
            >
                <button type="button" 
                        onclick="tambahSoal()" 
                        class="px-4 py-2 rounded-2xl bg-blue-50 text-[#1E6BFF] hover:bg-blue-100 text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer shadow-xs">
                    <span>+</span> Tambah Pertanyaan
                </button>
            </x-section-header>

            <!-- Live Peta Sebaran Audio Soal & Deteksi Nomor Loncat (Anti-Teledor) -->
            <div id="audio-mapping-summary" class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2"></div>

            <!-- Wadah Kartu Butir Soal -->
            <div id="soal-container" class="space-y-5">
                @forelse($listening->soals as $index => $soal)
                    @php
                        $selectedTrackNo = $soal->listeningAudio?->nomor_track ?? 1;
                    @endphp
                    <div class="soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                                Pertanyaan #{{ $soal->nomor_soal }}
                            </span>
                            <button type="button" onclick="hapusSoal(this)" class="text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1 cursor-pointer">
                                Hapus Soal
                            </button>
                        </div>

                        <!-- Dropdown Pemilihan Track Audio -->
                        <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div class="flex items-center gap-2.5">
                                <span class="text-xl">🎧</span>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-800">Gunakan Audio untuk Pertanyaan Ini:</label>
                                    <p class="text-[10px] text-slate-500">Audio yang akan didengarkan siswa saat menjawab nomor ini</p>
                                </div>
                            </div>
                            <div class="w-full sm:w-72">
                                <select name="soals[{{ $index }}][audio_track_nomor]" data-selected="{{ $selectedTrackNo }}" onchange="updateAudioMappingSummary()" class="select-audio-track w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 outline-none focus:border-[#1E6BFF]">
                                    @foreach($listening->audios as $aud)
                                        <option value="{{ $aud->nomor_track }}" {{ $selectedTrackNo == $aud->nomor_track ? 'selected' : '' }}>
                                            🎧 Track #{{ $aud->nomor_track }}: {{ $aud->judul_audio ?: 'Audio Track #'.$aud->nomor_track }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
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
                    <!-- Default jika belum ada soal -->
                    <div class="soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                                Pertanyaan #1
                            </span>
                        </div>
                        <input type="text" name="soals[0][pertanyaan]" required placeholder="Pertanyaan listening..." class="w-full px-4 py-2.5 rounded-2xl bg-white border text-xs">
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tombol Aksi Submit Form -->
        <div class="flex items-center justify-end gap-3 pt-3">
            <x-button variant="cancel" href="{{ route('guru.listening.index') }}">
                Batalkan
            </x-button>
            <x-button variant="submit">
                Simpan Perubahan Modul Listening
            </x-button>
        </div>
    </form>

    <script>
        let trackIndexCounter = {{ count($listening->audios) + 10 }};
        let soalIndexCounter = {{ count($listening->soals) + 10 }};

        function getActiveTracks() {
            const trackCards = document.querySelectorAll('.audio-track-card:not(.hidden)');
            const tracks = [];
            trackCards.forEach((card, idx) => {
                const trackNomor = idx + 1;
                const titleInput = card.querySelector('.track-title-input');
                const title = (titleInput && titleInput.value.trim()) ? titleInput.value.trim() : `Audio Track #${trackNomor}`;
                tracks.push({ nomor: trackNomor, title: title });
            });
            return tracks;
        }

        function syncAudioDropdowns() {
            const tracks = getActiveTracks();
            const selects = document.querySelectorAll('.select-audio-track');

            selects.forEach(sel => {
                const currentVal = sel.value || sel.dataset.selected;
                sel.innerHTML = '';
                tracks.forEach(t => {
                    const opt = document.createElement('option');
                    opt.value = t.nomor;
                    opt.textContent = `🎧 Track #${t.nomor}: ${t.title}`;
                    sel.appendChild(opt);
                });
                if (tracks.some(t => t.nomor == currentVal)) {
                    sel.value = currentVal;
                } else if (tracks.length > 0) {
                    sel.value = tracks[0].nomor;
                }
            });
            updateAudioMappingSummary();
        }

        function tambahAudioTrack() {
            const container = document.getElementById('audio-tracks-container');
            const activeCards = container.querySelectorAll('.audio-track-card:not(.hidden)');
            const newTrackNomor = activeCards.length + 1;
            const newIndex = trackIndexCounter++;

            const div = document.createElement('div');
            div.className = 'audio-track-card p-5 rounded-3xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative';
            div.dataset.track = newTrackNomor;

            div.innerHTML = `
                <input type="hidden" name="audios[${newIndex}][is_deleted]" class="track-deleted-input" value="0">
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-purple-600 text-white font-bold text-xs track-badge">
                        🎧 Audio Track #${newTrackNomor}
                    </span>
                    <button type="button" onclick="hapusAudioTrack(this)" class="tombol-hapus-track text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1 cursor-pointer">
                        🗑️ Hapus Track
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Judul / Nama Rekaman Audio
                        </label>
                        <input type="text" 
                               name="audios[${newIndex}][judul_audio]" 
                               value="Audio ${newTrackNomor}: Sesi ${newTrackNomor}"
                               placeholder="Contoh: Percakapan 2: Pengumuman Stasiun" 
                               oninput="syncAudioDropdowns()"
                               class="track-title-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Perkiraan Durasi
                        </label>
                        <input type="text" 
                               name="audios[${newIndex}][durasi]" 
                               placeholder="Otomatis saat file dipilih" 
                               class="track-durasi-input w-full px-4 py-2.5 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Unggah File Audio (MP3 / WAV / M4A) <span class="text-rose-500">*</span>
                        </label>
                        <input type="file" 
                               name="audios[${newIndex}][audio_file]" 
                               accept="audio/*,.mp3,.wav,.m4a,.aac,.ogg,.opus,.flac" 
                               onchange="handleTrackAudioPreview(this)"
                               class="w-full px-4 py-2 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-600 file:mr-3 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200 cursor-pointer">
                    </div>
                </div>

                <div class="track-preview-container hidden p-3 rounded-2xl bg-purple-50/70 border border-purple-200/70">
                    <div class="flex items-center justify-between mb-1.5">
                        <p class="text-[11px] font-bold text-purple-700 flex items-center gap-1.5">
                            <span>🔊</span> Pratinjau Audio Track Baru:
                        </p>
                        <button type="button" onclick="batalPilihTrackAudio(this)" class="text-rose-600 hover:text-rose-700 text-[11px] font-bold cursor-pointer">
                            ✕ Batal / Reset File
                        </button>
                    </div>
                    <audio controls class="w-full h-8 track-player"></audio>
                </div>
            `;

            container.appendChild(div);
            updateTrackBadges();
            syncAudioDropdowns();
        }

        function hapusAudioTrack(btn) {
            const card = btn.closest('.audio-track-card');
            const container = document.getElementById('audio-tracks-container');
            const activeCards = container.querySelectorAll('.audio-track-card:not(.hidden)');
            
            if (activeCards.length > 1) {
                const deletedInput = card.querySelector('.track-deleted-input');
                if (deletedInput) {
                    deletedInput.value = '1';
                    card.classList.add('hidden');
                } else {
                    card.remove();
                }
                updateTrackBadges();
                syncAudioDropdowns();
            } else {
                alert('Setidaknya harus ada minimal 1 file audio track!');
            }
        }

        function updateTrackBadges() {
            const cards = document.querySelectorAll('.audio-track-card:not(.hidden)');
            cards.forEach((card, idx) => {
                const trackNomor = idx + 1;
                card.dataset.track = trackNomor;
                const badge = card.querySelector('.track-badge');
                if (badge) badge.innerText = `🎧 Audio Track #${trackNomor}`;
            });
        }

        function handleTrackAudioPreview(input) {
            const card = input.closest('.audio-track-card');
            const previewContainer = card.querySelector('.track-preview-container');
            const player = card.querySelector('.track-player');
            const durasiInput = card.querySelector('.track-durasi-input');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileUrl = URL.createObjectURL(file);
                if (player) player.src = fileUrl;
                if (previewContainer) previewContainer.classList.remove('hidden');

                if (player) {
                    player.onloadedmetadata = function() {
                        const m = Math.floor(player.duration / 60).toString().padStart(2, '0');
                        const s = Math.floor(player.duration % 60).toString().padStart(2, '0');
                        if (durasiInput) durasiInput.value = `${m}:${s}`;
                    };
                }
            }
        }

        function batalPilihTrackAudio(btn) {
            const card = btn.closest('.audio-track-card');
            const fileInput = card.querySelector('input[type="file"]');
            const previewContainer = card.querySelector('.track-preview-container');
            const player = card.querySelector('.track-player');

            if (fileInput) fileInput.value = '';
            if (player) {
                player.pause();
                player.src = '';
            }
            if (previewContainer) previewContainer.classList.add('hidden');
        }

        function tambahSoal() {
            const container = document.getElementById('soal-container');
            const currentIndex = soalIndexCounter++;
            const currentTotal = container.children.length + 1;
            const tracks = getActiveTracks();

            // Smart Default: otomatis ikuti track audio dari soal sebelumnya
            const lastSelect = container.querySelector('.soal-card:last-child .select-audio-track');
            const defaultTrack = lastSelect ? lastSelect.value : (tracks.length > 0 ? tracks[0].nomor : 1);

            let optionsHtml = '';
            tracks.forEach(t => {
                const isSelected = (t.nomor == defaultTrack) ? 'selected' : '';
                optionsHtml += `<option value="${t.nomor}" ${isSelected}>🎧 Track #${t.nomor}: ${t.title}</option>`;
            });

            const div = document.createElement('div');
            div.className = 'soal-card p-5 lg:p-6 rounded-3xl border border-slate-200/80 bg-slate-50/40 space-y-4 relative';
            div.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="px-3 py-1 rounded-xl bg-[#1E6BFF] text-white font-bold text-xs nomor-label">
                        Pertanyaan #${currentTotal}
                    </span>
                    <button type="button" onclick="hapusSoal(this)" class="text-slate-400 hover:text-rose-600 font-semibold text-xs transition-colors p-1 cursor-pointer">
                        Hapus Soal
                    </button>
                </div>

                <!-- Dropdown Pemilihan Track Audio -->
                <div class="p-3.5 rounded-2xl bg-blue-50/60 border border-blue-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-2.5">
                        <span class="text-xl">🎧</span>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-800">Gunakan Audio untuk Pertanyaan Ini:</label>
                            <p class="text-[10px] text-slate-500">Audio yang akan didengarkan siswa saat menjawab nomor ini</p>
                        </div>
                    </div>
                    <div class="w-full sm:w-72">
                        <select name="soals[${currentIndex}][audio_track_nomor]" onchange="updateAudioMappingSummary()" class="select-audio-track w-full px-3.5 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 outline-none focus:border-[#1E6BFF]">
                            ${optionsHtml}
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">
                        Kalimat Pertanyaan Listening <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="soals[${currentIndex}][pertanyaan]" 
                           required 
                           placeholder="Contoh: What does the woman imply about the schedule?" 
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
            updateAudioMappingSummary();
        }

        function hapusSoal(btn) {
            const card = btn.closest('.soal-card');
            const container = document.getElementById('soal-container');
            if (container.children.length > 1) {
                card.remove();
                updateLabels();
                updateAudioMappingSummary();
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

        // Hitung dan tampilkan ringkasan peta sebaran audio (Anti-Teledor)
        function updateAudioMappingSummary() {
            const summaryBox = document.getElementById('audio-mapping-summary');
            if (!summaryBox) return;

            const container = document.getElementById('soal-container');
            const cards = container.querySelectorAll('.soal-card');
            const tracks = getActiveTracks();

            const trackMap = {};
            tracks.forEach(t => { trackMap[t.nomor] = []; });

            cards.forEach((card, idx) => {
                const soalNo = idx + 1;
                const sel = card.querySelector('.select-audio-track');
                const trackNo = sel ? parseInt(sel.value) : 1;
                if (!trackMap[trackNo]) trackMap[trackNo] = [];
                trackMap[trackNo].push(soalNo);
            });

            let chipsHtml = '';
            let warnings = [];

            tracks.forEach(t => {
                const nomors = trackMap[t.nomor] || [];
                let rangeText = 'Belum ada soal';
                let isNonContiguous = false;

                if (nomors.length > 0) {
                    const ranges = [];
                    let start = nomors[0];
                    let prev = start;
                    for (let i = 1; i < nomors.length; i++) {
                        let curr = nomors[i];
                        if (curr === prev + 1) {
                            prev = curr;
                        } else {
                            ranges.push(start === prev ? `${start}` : `${start}-${prev}`);
                            start = curr;
                            prev = curr;
                            isNonContiguous = true;
                        }
                    }
                    ranges.push(start === prev ? `${start}` : `${start}-${prev}`);
                    rangeText = 'Soal No. ' + ranges.join(', ');

                    if (isNonContiguous) {
                        warnings.push(`Track #${t.nomor} (${t.title}) melompati nomor lain (dipakai pada ${rangeText}).`);
                    }
                }

                chipsHtml += `
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-semibold text-slate-700 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-purple-600"></span>
                        <span class="font-bold text-slate-800">Track #${t.nomor}:</span>
                        <span class="text-[#1E6BFF] font-bold">${rangeText}</span>
                        <span class="text-[10px] text-slate-400">(${nomors.length} soal)</span>
                    </div>
                `;
            });

            let warningHtml = '';
            if (warnings.length > 0) {
                warningHtml = `
                    <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800 font-medium flex items-start gap-2">
                        <span class="text-base shrink-0">⚠️</span>
                        <div>
                            <strong class="font-bold text-amber-900">Perhatian Urutan Audio Melompat:</strong>
                            <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-[11px] text-amber-800">
                                ${warnings.map(w => `<li>${w}</li>`).join('')}
                            </ul>
                            <p class="text-[10.5px] text-amber-700 mt-1">Pastikan susunan nomor audio yang berselang-seling ini memang disengaja.</p>
                        </div>
                    </div>
                `;
            }

            summaryBox.innerHTML = `
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                        <span>📊</span> Peta Sebaran Audio Soal:
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">Otomatis dihitung real-time</span>
                </div>
                <div class="flex flex-wrap gap-2 pt-1">
                    ${chipsHtml}
                </div>
                ${warningHtml}
            `;
        }

        // Jalankan sinkronisasi dropdown dan ringkasan saat pertama dimuat
        document.addEventListener('DOMContentLoaded', () => {
            syncAudioDropdowns();
        });
    </script>
</x-layout>
