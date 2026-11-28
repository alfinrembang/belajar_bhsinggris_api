<!-- ============================================================== -->
<!-- 1. MODAL: Tambah Soal Kosakata Game Tangkap Ikan               -->
<!-- ============================================================== -->
<div id="modalTambahSoalIkan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 border border-sky-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Tambah Soal Tangkap Ikan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Disinkronkan otomatis ke game Tangkap Ikan di mobile.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalTambahSoalIkan')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('guru.game.fishing.store') }}" class="space-y-4">
            @csrf

            <!-- Level & Kesulitan -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Akuarium (1-9)</label>
                    <select name="level" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                        @for($lvl = 1; $lvl <= 9; $lvl++)
                            <option value="{{ $lvl }}">⭐ Level {{ $lvl }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                        <option value="mudah">🟢 Mudah (Easy)</option>
                        <option value="sedang">🟡 Sedang (Medium)</option>
                        <option value="sulit">🔴 Sulit (Hard)</option>
                    </select>
                </div>
            </div>

            <!-- Kosakata (Word) & Jenis Kata -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kosakata (English Word)</label>
                    <input type="text" 
                           name="word" 
                           required 
                           placeholder="Contoh: GENEROUS" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-800 uppercase tracking-wider focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kata (Part of Speech)</label>
                    <select name="part_of_speech" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                        <option value="Adjective (Kata Sifat)">🏷️ Adjective (Kata Sifat)</option>
                        <option value="Verb (Kata Kerja)">⚡ Verb (Kata Kerja)</option>
                        <option value="Noun (Kata Benda)">📦 Noun (Kata Benda)</option>
                        <option value="Adverb (Kata Keterangan)">✨ Adverb (Kata Keterangan)</option>
                    </select>
                </div>
            </div>

            <!-- Arti Kata / Kunci Jawaban Benar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kata / Kunci Jawaban (Bahasa Indonesia)</label>
                <input type="text" 
                       name="correct_answer" 
                       id="correctAnswerFishing"
                       required 
                       placeholder="Contoh: Dermawan / Suka Memberi" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-emerald-50/70 border border-emerald-300 text-xs font-bold text-emerald-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 outline-none transition-all">
                <input type="hidden" name="meaning" id="hiddenMeaningInput" value="">
            </div>

            <!-- 3 Pilihan Pengecoh -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">3 Pilihan Pengecoh (Pilihan Jawaban Salah)</label>
                <div class="space-y-2">
                    <input type="text" name="option_2" required placeholder="Pilihan Salah 1 (contoh: Pelit / Kikir)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                    <input type="text" name="option_3" required placeholder="Pilihan Salah 2 (contoh: Pemarah)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                    <input type="text" name="option_4" required placeholder="Pilihan Salah 3 (contoh: Penakut)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                </div>
            </div>

            <!-- Contoh Kalimat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Contoh Kalimat Penggunaan (Opsional)</label>
                <input type="text" 
                       name="example_sentence" 
                       placeholder="Contoh: Mr. Jack is a generous teacher." 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
            </div>

            <!-- Penjelasan Edukasi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan Edukasi (Opsional)</label>
                <textarea name="explanation" 
                          rows="2" 
                          placeholder="Penjelasan edukatif makna kata..."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all"></textarea>
            </div>

            <!-- Footer -->
            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalTambahSoalIkan')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" onclick="document.getElementById('hiddenMeaningInput').value = document.getElementById('correctAnswerFishing').value" class="!rounded-xl !shadow-xs">
                    Simpan Soal
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 2. MODAL: Import Excel Game Tangkap Ikan (Anti-Duplikat)       -->
<!-- ============================================================== -->
<div id="modalImportFishing" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Import Excel / CSV Tangkap Ikan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mendukung file .xlsx dan .csv dengan sistem cerdas Anti-Duplikat.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalImportFishing')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Banner Info Anti-Duplikat -->
        <div class="p-3.5 mb-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Perlindungan Anti-Duplikasi Otomatis</span>
            </div>
            <p class="text-[11px] text-amber-700 leading-relaxed pl-6">
                Jika Anda mengupload file yang berisi gabungan soal lama dan soal baru, sistem hanya akan memasukkan <b>soal baru saja</b>. Soal yang sudah ada akan otomatis dilewati agar database tidak bengkak.
            </p>
        </div>

        <form method="POST" action="{{ route('guru.game.fishing.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel (.xlsx, .xls, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.xls,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF5EF] file:text-[#2E7352] hover:file:bg-[#d7ede1] file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.fishing.template') }}" class="text-[#2E7352] font-bold underline hover:text-[#1d4c36] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Excel (.xlsx)</span>
                    </a>
                </div>
                <p class="text-[11px] text-slate-500">
                    Kolom wajib: <b>Level</b> (1-9), <b>Difficulty</b> (mudah/sedang/sulit), <b>Word</b> (Kosakata Inggris), <b>Part of Speech</b>, <b>Arti / Jawaban Benar</b>, <b>Pilihan Pengecoh 1, 2, 3</b>.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalImportFishing')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!rounded-xl !shadow-xs">
                    Upload & Proses Import
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 3. MODAL: Tambah Pasangan Kata Game Tebak Serigala (Werewolf)  -->
<!-- ============================================================== -->
<div id="modalTambahSoalWerewolf" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 border border-indigo-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Kelola 2 Jawaban Kartu (Tebak Serigala)</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tentukan Kata Domba (Kata A) vs Kata Serigala (Kata B) beserta ciri-cirinya.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalTambahSoalWerewolf')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('guru.game.werewolf.store') }}" class="space-y-4">
            @csrf

            <!-- Kategori / Tema -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori / Tema Kosakata</label>
                <input type="text" 
                       name="theme" 
                       required 
                       placeholder="Contoh: Nature, Animals, Food & Drinks, Technology, Occupations" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all">
            </div>

            <!-- Bagian 1: KARTU DOMBA (KATA A) -->
            <div class="p-4 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">🐑</span>
                    <h4 class="font-extrabold text-emerald-900 text-xs uppercase tracking-wide">Kartu 1: Kata Domba (Mayoritas 9 Pemain)</h4>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-800 mb-1">Kata Bahasa Inggris (Kata A)</label>
                        <input type="text" name="domba_word" required placeholder="Contoh: FOREST" class="w-full px-3 py-2 rounded-xl bg-white border border-emerald-300 text-xs font-bold uppercase text-emerald-950 focus:border-emerald-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-emerald-800 mb-1">Arti Kata Domba</label>
                        <input type="text" name="domba_meaning" required placeholder="Contoh: Hutan Lebat" class="w-full px-3 py-2 rounded-xl bg-white border border-emerald-300 text-xs text-emerald-950 focus:border-emerald-600 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-emerald-800 mb-1">Ciri-Ciri / Clues Bahasa Inggris Domba (Minimal 2)</label>
                    <div class="space-y-1.5">
                        <input type="text" name="domba_clue_1" required placeholder="Clue 1: It has tall green trees and shaded canopy." class="w-full px-3 py-1.5 rounded-lg bg-white border border-emerald-200 text-xs text-slate-800">
                        <input type="text" name="domba_clue_2" required placeholder="Clue 2: Cooler weather and home to bears." class="w-full px-3 py-1.5 rounded-lg bg-white border border-emerald-200 text-xs text-slate-800">
                        <input type="text" name="domba_clue_3" placeholder="Clue 3 (opsional): Covered with oak and pine trees." class="w-full px-3 py-1.5 rounded-lg bg-white border border-emerald-200 text-xs text-slate-800">
                    </div>
                </div>
            </div>

            <!-- Bagian 2: KARTU SERIGALA (KATA B) -->
            <div class="p-4 rounded-2xl bg-red-50/50 border border-red-200/80 space-y-3">
                <div class="flex items-center gap-2">
                    <span class="text-base">🐺</span>
                    <h4 class="font-extrabold text-red-900 text-xs uppercase tracking-wide">Kartu 2: Kata Serigala (Penyamar 1 Pemain)</h4>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-bold text-red-800 mb-1">Kata Bahasa Inggris (Kata B)</label>
                        <input type="text" name="serigala_word" required placeholder="Contoh: JUNGLE" class="w-full px-3 py-2 rounded-xl bg-white border border-red-300 text-xs font-bold uppercase text-red-950 focus:border-red-600 outline-none">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-red-800 mb-1">Arti Kata Serigala</label>
                        <input type="text" name="serigala_meaning" required placeholder="Contoh: Rimba Liar" class="w-full px-3 py-2 rounded-xl bg-white border border-red-300 text-xs text-red-950 focus:border-red-600 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-red-800 mb-1">Ciri-Ciri / Clues Bahasa Inggris Serigala (Minimal 2)</label>
                    <div class="space-y-1.5">
                        <input type="text" name="serigala_clue_1" required placeholder="Clue 1: Dense tangled tropical vegetation." class="w-full px-3 py-1.5 rounded-lg bg-white border border-red-200 text-xs text-slate-800">
                        <input type="text" name="serigala_clue_2" required placeholder="Clue 2: High humidity with vines everywhere." class="w-full px-3 py-1.5 rounded-lg bg-white border border-red-200 text-xs text-slate-800">
                        <input type="text" name="serigala_clue_3" placeholder="Clue 3 (opsional): Inhabited by monkeys and leopards." class="w-full px-3 py-1.5 rounded-lg bg-white border border-red-200 text-xs text-slate-800">
                    </div>
                </div>
            </div>

            <!-- Penjelasan Perbedaan Edukatif -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan Perbedaan Kosakata (English Difference)</label>
                <textarea name="english_difference" 
                          rows="2" 
                          required
                          placeholder="Contoh: Forest refers to temperate woodlands with open floors, while Jungle is a dense, tropical rainforest."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-indigo-500 outline-none transition-all"></textarea>
            </div>

            <!-- Footer -->
            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalTambahSoalWerewolf')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-indigo-600 hover:!bg-indigo-700 !rounded-xl !shadow-xs">
                    Simpan Pasangan Kata
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 4. MODAL: Import Excel Game Tebak Serigala (Anti-Duplikat)     -->
<!-- ============================================================== -->
<div id="modalImportWerewolf" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center shrink-0 border border-indigo-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Import Excel / CSV Tebak Serigala</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mendukung file .xlsx dan .csv dengan sistem cerdas Anti-Duplikat.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalImportWerewolf')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Banner Info Anti-Duplikat -->
        <div class="p-3.5 mb-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Perlindungan Anti-Duplikasi Otomatis</span>
            </div>
            <p class="text-[11px] text-amber-700 leading-relaxed pl-6">
                Pasangan kata yang sudah ada sebelumnya akan <b>otomatis dilewati</b>. Hanya pasangan kata baru yang dimasukkan ke sistem, sehingga data tidak ganda dan database tetap ringan.
            </p>
        </div>

        <form method="POST" action="{{ route('guru.game.werewolf.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel (.xlsx, .xls, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.xls,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.werewolf.template') }}" class="text-indigo-600 font-bold underline hover:text-indigo-800 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Excel (.xlsx)</span>
                    </a>
                </div>
                <p class="text-[11px] text-slate-500">
                    Kolom: <b>Kategori/Tema</b>, <b>Kata Domba</b>, <b>Arti Domba</b>, <b>Petunjuk Domba</b> (pisahkan dengan titik koma ;), <b>Kata Serigala</b>, <b>Arti Serigala</b>, <b>Petunjuk Serigala</b>, <b>Penjelasan Perbedaan</b>.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalImportWerewolf')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-indigo-600 hover:!bg-indigo-700 !rounded-xl !shadow-xs">
                    Upload & Proses Import
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- Script Modal Open / Close & Audio Player -->
<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    // Audio Player Native untuk Guru (Web Speech Synthesis Instan & Bebas File Server)
    function playTeacherWordAudio(text) {
        if (!text || text.trim() === '') {
            alert('Silakan ketik atau isi kosakata terlebih dahulu!');
            return;
        }
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            const utter = new SpeechSynthesisUtterance(text);
            utter.lang = 'en-US';
            utter.rate = 0.88;
            window.speechSynthesis.speak(utter);
        } else {
            alert('Browser Anda tidak mendukung Speech Synthesis.');
        }
    }

    // Handler Pengisian Form Edit Kosakata Pelafalan
    function editPronounceWord(item) {
        if (!item) return;
        document.getElementById('editPronounceForm').action = '/games/pronounce/word/' + item.id + '/update';
        document.getElementById('editPronounceWordInput').value = item.word || '';
        document.getElementById('editPronouncePhonetic').value = item.phonetic || '';
        document.getElementById('editPronouncePos').value = item.part_of_speech || 'Noun (Kata Benda)';
        document.getElementById('editPronounceMeaning').value = item.meaning || '';
        document.getElementById('editPronounceDiff').value = item.difficulty || 'mudah';
        document.getElementById('editPronounceTips').value = item.tips || '';
        document.getElementById('editPronounceExample').value = item.example_sentence || '';
        openModal('modalEditPronounceWord');
    }

    // Handler Pengisian Form Edit Soal Susun Kalimat
    function editSentenceModal(item) {
        if (!item) return;
        document.getElementById('editSentenceForm').action = '/games/sentence/soal/' + item.id + '/update';
        document.getElementById('editSentenceInput').value = item.sentence || '';
        document.getElementById('editSentenceMeaning').value = item.meaning || '';
        document.getElementById('editSentenceLevel').value = item.level || 1;
        document.getElementById('editSentenceDiff').value = item.difficulty || 'mudah';
        document.getElementById('editSentenceTenses').value = item.tenses_category || 'Simple Present Tense';
        document.getElementById('editSentenceTips').value = item.grammar_tips || '';
        openModal('modalEditSentence');
    }
</script>

<!-- ============================================================== -->
<!-- 5. MODAL: Tambah Kosakata Pelafalan (Pronounce Hero)           -->
<!-- ============================================================== -->
<div id="modalTambahPronounceWord" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 border border-sky-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                        <line x1="12" y1="19" x2="12" y2="22"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Tambah Kosakata Pelafalan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Otomatis dilafalkan native speaker di mobile & dapat dites suaranya.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalTambahPronounceWord')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('guru.game.pronounce.store') }}" class="space-y-4">
            @csrf

            <!-- Kosakata (Word) & Tombol Live Audio Preview -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kosakata Bahasa Inggris (English Word)</label>
                    <button type="button" 
                            onclick="playTeacherWordAudio(document.getElementById('inputWordTambahPronounce').value)" 
                            class="px-2.5 py-1 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 text-[11px] font-bold flex items-center gap-1 cursor-pointer transition-all">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                        <span>🔊 Tes Suara Bule</span>
                    </button>
                </div>
                <input type="text" 
                       id="inputWordTambahPronounce"
                       name="word" 
                       required 
                       placeholder="Contoh: Wednesday / Comfortable" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
            </div>

            <!-- Fonetik & Tingkat Kesulitan -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Fonetik IPA (Opsional)</label>
                    <input type="text" 
                           name="phonetic" 
                           placeholder="Contoh: /ˈwɛnzdeɪ/" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                        <option value="mudah">🟢 Mudah (Easy)</option>
                        <option value="sedang">🟡 Sedang (Medium)</option>
                        <option value="sulit">🔴 Sulit (Hard)</option>
                    </select>
                </div>
            </div>

            <!-- Jenis Kata & Arti Bahasa Indonesia -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kata (Part of Speech)</label>
                    <select name="part_of_speech" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                        <option value="Noun (Kata Benda)">📦 Noun (Kata Benda)</option>
                        <option value="Verb (Kata Kerja)">⚡ Verb (Kata Kerja)</option>
                        <option value="Adjective (Kata Sifat)">🏷️ Adjective (Kata Sifat)</option>
                        <option value="Adverb (Kata Keterangan)">✨ Adverb (Kata Keterangan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kata (Bahasa Indonesia)</label>
                    <input type="text" 
                           name="meaning" 
                           required 
                           placeholder="Contoh: Hari Rabu" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-emerald-50/60 border border-emerald-300 text-xs font-bold text-emerald-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 outline-none transition-all">
                </div>
            </div>

            <!-- Tips Pelafalan untuk Siswa -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tips Pelafalan / Catatan Khusus (Opsional)</label>
                <textarea name="tips" 
                          rows="2" 
                          placeholder="Contoh: Huruf 'd' pertama dan 'e' kedua bersifat silent (tidak dibaca). Lafalkan: WENZ-day."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all resize-none"></textarea>
            </div>

            <!-- Contoh Kalimat Bahasa Inggris -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Contoh Kalimat Sederhana (Opsional)</label>
                <input type="text" 
                       name="example_sentence" 
                       placeholder="Contoh: We have an English quiz every Wednesday." 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
            </div>

            <!-- Info Keunggulan Auto Voice -->
            <div class="p-3 rounded-2xl bg-sky-50/80 border border-sky-200/80 text-[11px] text-sky-800 flex items-start gap-2">
                <span class="text-sky-600 text-sm">💡</span>
                <p>
                    <b>Bebas Repot Rekam:</b> Sistem otomatis melafalkan kata ini dengan suara Native Speaker jernih di HP siswa tanpa perlu upload file audio apa pun.
                </p>
            </div>

            <!-- Tombol Aksi Simpan -->
            <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalTambahPronounceWord')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-sky-600 hover:!bg-sky-700 !rounded-xl !shadow-xs">
                    Simpan Kosakata Pelafalan
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 6. MODAL: Edit Kosakata Pelafalan                              -->
<!-- ============================================================== -->
<div id="modalEditPronounceWord" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 border border-sky-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Edit Kosakata Pelafalan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Perbarui teks kosakata, arti, dan tips pelafalan.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalEditPronounceWord')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form id="editPronounceForm" method="POST" action="" class="space-y-4">
            @csrf

            <!-- Kosakata (Word) & Live Audio Preview -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700">Kosakata Bahasa Inggris (English Word)</label>
                    <button type="button" 
                            onclick="playTeacherWordAudio(document.getElementById('editPronounceWordInput').value)" 
                            class="px-2.5 py-1 rounded-xl bg-sky-50 text-sky-700 hover:bg-sky-100 border border-sky-200 text-[11px] font-bold flex items-center gap-1 cursor-pointer transition-all">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                        <span>🔊 Tes Suara</span>
                    </button>
                </div>
                <input type="text" 
                       id="editPronounceWordInput"
                       name="word" 
                       required 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
            </div>

            <!-- Fonetik & Tingkat Kesulitan -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Fonetik IPA (Opsional)</label>
                    <input type="text" 
                           id="editPronouncePhonetic"
                           name="phonetic" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-mono text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan</label>
                    <select id="editPronounceDiff" name="difficulty" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                        <option value="mudah">🟢 Mudah (Easy)</option>
                        <option value="sedang">🟡 Sedang (Medium)</option>
                        <option value="sulit">🔴 Sulit (Hard)</option>
                    </select>
                </div>
            </div>

            <!-- Jenis Kata & Arti Bahasa Indonesia -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kata (Part of Speech)</label>
                    <select id="editPronouncePos" name="part_of_speech" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
                        <option value="Noun (Kata Benda)">📦 Noun (Kata Benda)</option>
                        <option value="Verb (Kata Kerja)">⚡ Verb (Kata Kerja)</option>
                        <option value="Adjective (Kata Sifat)">🏷️ Adjective (Kata Sifat)</option>
                        <option value="Adverb (Kata Keterangan)">✨ Adverb (Kata Keterangan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kata (Bahasa Indonesia)</label>
                    <input type="text" 
                           id="editPronounceMeaning"
                           name="meaning" 
                           required 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-emerald-50/60 border border-emerald-300 text-xs font-bold text-emerald-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 outline-none transition-all">
                </div>
            </div>

            <!-- Tips Pelafalan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tips Pelafalan (Opsional)</label>
                <textarea id="editPronounceTips"
                          name="tips" 
                          rows="2" 
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all resize-none"></textarea>
            </div>

            <!-- Contoh Kalimat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Contoh Kalimat (Opsional)</label>
                <input type="text" 
                       id="editPronounceExample"
                       name="example_sentence" 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-sky-500 focus:ring-2 focus:ring-sky-500/15 outline-none transition-all">
            </div>

            <!-- Tombol Aksi Simpan Perubahan -->
            <div class="pt-3 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalEditPronounceWord')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-sky-600 hover:!bg-sky-700 !rounded-xl !shadow-xs">
                    Simpan Perubahan
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 7. MODAL: Import Excel Kosakata Pelafalan (Anti-Duplikat)      -->
<!-- ============================================================== -->
<div id="modalImportPronounce" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 border border-sky-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Import Excel Kosakata Pelafalan</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Mendukung format .xlsx dan .csv dengan sistem cerdas Anti-Duplikat.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalImportPronounce')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Banner Info Anti-Duplikat -->
        <div class="p-3.5 mb-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Perlindungan Anti-Duplikasi Otomatis</span>
            </div>
            <p class="text-[11px] text-amber-700 leading-relaxed pl-6">
                Kosakata yang sudah ada sebelumnya di database akan <b>otomatis dilewati</b>. Hanya kosakata baru yang ditambahkan, sehingga data tidak dobel.
            </p>
        </div>

        <form method="POST" action="{{ route('guru.game.pronounce.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel (.xlsx, .xls, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.xls,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.pronounce.template') }}" class="text-sky-600 font-bold underline hover:text-sky-800 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Excel (.xlsx)</span>
                    </a>
                </div>
                <p class="text-[11px] text-slate-500">
                    Kolom: <b>Kosakata</b>, <b>Fonetik IPA</b>, <b>Jenis Kata</b>, <b>Arti</b>, <b>Tingkat Kesulitan</b> (mudah/sedang/sulit), <b>Tips Pelafalan</b>, <b>Contoh Kalimat</b>.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalImportPronounce')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-sky-600 hover:!bg-sky-700 !rounded-xl !shadow-xs">
                    Upload & Proses Import
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 7. MODAL: Tambah Soal Susun Kalimat (Sentence Builder - Solo)  -->
<!-- ============================================================== -->
<div id="modalTambahSentence" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-xl rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 border border-purple-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Tambah Soal Susun Kalimat (Solo)</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Tulis kalimat utuh, sistem otomatis memotong kata menjadi balok acak.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalTambahSentence')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" action="{{ route('guru.game.sentence.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kalimat Lengkap Bahasa Inggris <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="sentence" 
                       id="addSentenceInput"
                       required 
                       placeholder="Contoh: She bought a new laptop yesterday." 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/15 outline-none transition-all">
                <p class="text-[10px] text-slate-400 mt-1">Tulis kalimat dengan huruf besar/kecil dan tanda baca normal. Sistem akan memecah kata secara otomatis.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kalimat (Bahasa Indonesia) <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="meaning" 
                       required 
                       placeholder="Contoh: Kemarin dia membeli laptop baru." 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/15 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Permainan <span class="text-rose-500">*</span></label>
                    <select name="level" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-purple-500">
                        @for($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}">⭐ Level {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan <span class="text-rose-500">*</span></label>
                    <select name="difficulty" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-purple-500">
                        <option value="mudah">🟢 Tingkat Mudah (3-4 Kata)</option>
                        <option value="sedang">🟡 Tingkat Sedang (5-7 Kata)</option>
                        <option value="sulit">🔴 Tingkat Sulit (8+ Kata)</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Tenses / Grammar <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="tenses_category" 
                       required 
                       list="listTenses"
                       placeholder="Contoh: Simple Past Tense" 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/15 outline-none transition-all">
                <datalist id="listTenses">
                    <option value="Simple Present Tense"></option>
                    <option value="Simple Past Tense"></option>
                    <option value="Present Continuous"></option>
                    <option value="Simple Future Tense"></option>
                    <option value="Passive Voice"></option>
                    <option value="Conditional Sentence Type 1"></option>
                    <option value="Modal Verbs"></option>
                </datalist>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tips Tata Bahasa (Grammar Tips) - Opsional</label>
                <textarea name="grammar_tips" 
                          rows="2" 
                          placeholder="Contoh: Gunakan kata kerja bentuk kedua (bought) untuk aktivitas di waktu lampau."
                          class="w-full px-3.5 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-purple-500 outline-none transition-all"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalTambahSentence')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-purple-600 hover:!bg-purple-700 !rounded-xl !shadow-xs">
                    Simpan Soal Kalimat
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 8. MODAL: Edit Soal Susun Kalimat (Sentence Builder - Solo)    -->
<!-- ============================================================== -->
<div id="modalEditSentence" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-xl rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Edit Soal Susun Kalimat</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Perbarui struktur kalimat atau terjemahan soal.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalEditSentence')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <form method="POST" id="editSentenceForm" action="" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kalimat Lengkap Bahasa Inggris <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="sentence" 
                       id="editSentenceInput"
                       required 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-purple-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kalimat (Bahasa Indonesia) <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="meaning" 
                       id="editSentenceMeaning"
                       required 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-purple-500 outline-none transition-all">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Level Permainan <span class="text-rose-500">*</span></label>
                    <select name="level" id="editSentenceLevel" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-purple-500">
                        @for($i = 1; $i <= 9; $i++)
                            <option value="{{ $i }}">⭐ Level {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan <span class="text-rose-500">*</span></label>
                    <select name="difficulty" id="editSentenceDiff" required class="w-full px-3 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 outline-none focus:bg-white focus:border-purple-500">
                        <option value="mudah">🟢 Tingkat Mudah</option>
                        <option value="sedang">🟡 Tingkat Sedang</option>
                        <option value="sulit">🔴 Tingkat Sulit</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Tenses / Grammar <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="tenses_category" 
                       id="editSentenceTenses"
                       required 
                       class="w-full px-3.5 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-purple-500 outline-none transition-all">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tips Tata Bahasa (Grammar Tips) - Opsional</label>
                <textarea name="grammar_tips" 
                          id="editSentenceTips"
                          rows="2" 
                          class="w-full px-3.5 py-2 rounded-2xl bg-slate-50 border border-slate-200 text-xs font-medium text-slate-800 focus:bg-white focus:border-purple-500 outline-none transition-all"></textarea>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalEditSentence')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-purple-600 hover:!bg-purple-700 !rounded-xl !shadow-xs">
                    Perbarui Soal Kalimat
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 9. MODAL: Import Soal Susun Kalimat (Sentence Builder) (.xlsx) -->
<!-- ============================================================== -->
<div id="modalImportSentence" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0 border border-purple-200">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Import Soal Susun Kalimat</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Unggah banyak kalimat sekaligus menggunakan template Excel resmi (.xlsx).</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalImportSentence')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Banner Info Anti-Duplikat -->
        <div class="p-3.5 mb-4 rounded-2xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs space-y-1">
            <div class="flex items-center gap-2 font-bold text-amber-900">
                <svg class="w-4 h-4 text-amber-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span>Perlindungan Anti-Duplikasi Otomatis</span>
            </div>
            <p class="text-[11px] text-amber-700 leading-relaxed pl-6">
                Kalimat bahasa Inggris yang sudah terdaftar sebelumnya di database akan <b>otomatis dilewati</b>. Hanya kalimat baru yang dimasukkan ke sistem, sehingga data tidak ganda dan database tetap optimal.
            </p>
        </div>

        <form method="POST" action="{{ route('guru.game.sentence.import') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel (.xlsx, .xls, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.xls,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.sentence.template') }}" class="text-purple-700 font-bold underline hover:text-purple-900 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Excel (.xlsx)</span>
                    </a>
                </div>
                <p class="text-[11px] text-slate-500">
                    Kolom: <b>Level</b> (1-9), <b>Tingkat Kesulitan</b> (mudah/sedang/sulit), <b>Kalimat Bahasa Inggris</b>, <b>Arti Bahasa Indonesia</b>, <b>Kategori Tenses</b>, <b>Tips Grammar (Opsional)</b>.
                </p>
            </div>

            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalImportSentence')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" class="!bg-purple-600 hover:!bg-purple-700 !rounded-xl !shadow-xs">
                    Upload & Proses Import
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- 10. MODAL: Aturan & Cara Bermain Susun Kalimat (Icon ?)        -->
<!-- ============================================================== -->
<div id="modalRulesSentence" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-2xl rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        <!-- Header Modal Rules dengan Icon ? Menonjol -->
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white flex items-center justify-center shrink-0 shadow-md text-xl font-extrabold font-serif">
                    ?
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Aturan & Cara Bermain: Susun Kalimat</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Panduan gameplay mode Solo, mekanisme balok kata, dan sistem reward bintang.</p>
                </div>
            </div>
            <button type="button" onclick="closeModal('modalRulesSentence')" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
                &times;
            </button>
        </div>

        <!-- Konten 4 Langkah Aturan Bermain -->
        <div class="space-y-3.5">
            <!-- Langkah 1 -->
            <div class="p-4 rounded-2xl bg-purple-50/60 border border-purple-200/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-purple-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    1
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-slate-900 text-xs">Pahami Arti Kalimat & Petunjuk Tenses</h4>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Di setiap ronde, siswa membaca kalimat dalam bahasa Indonesia (contoh: <i>"Kemarin dia membeli laptop baru"</i>) serta petunjuk tenses (contoh: <i>Simple Past Tense</i>) sebagai panduan urutan kata dan keterangan waktu.
                    </p>
                </div>
            </div>

            <!-- Langkah 2 -->
            <div class="p-4 rounded-2xl bg-indigo-50/60 border border-indigo-200/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-indigo-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    2
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-slate-900 text-xs">Tap Balok Kata Berurutan Masuk ke Slot Rel</h4>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Siswa men-tap kata-kata acak di baki bawah satu per satu. Kata yang di-tap akan melompat naik ke slot kalimat di atas (sesuai urutan SPOK). Jika salah klik kata, siswa cukup men-tap kata di atas untuk mengembalikannya ke bawah atau klik tombol <b>"Reset"</b>.
                    </p>
                </div>
            </div>

            <!-- Langkah 3 -->
            <div class="p-4 rounded-2xl bg-sky-50/60 border border-sky-200/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-sky-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    3
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-slate-900 text-xs">Bantuan Audio Pengucapan Bule (Hint TTS)</h4>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Jika siswa merasa bingung atau ragu, siswa dapat menekan ikon speaker 🔊 untuk mendengarkan pelafalan kalimat asli secara utuh untuk melatih kepekaan telinga terhadap struktur kalimat bahasa Inggris.
                    </p>
                </div>
            </div>

            <!-- Langkah 4 -->
            <div class="p-4 rounded-2xl bg-amber-50/60 border border-amber-200/80 flex items-start gap-3.5">
                <div class="w-8 h-8 rounded-xl bg-amber-600 text-white font-extrabold text-xs flex items-center justify-center shrink-0 shadow-xs">
                    4
                </div>
                <div class="space-y-1">
                    <h4 class="font-bold text-slate-900 text-xs">Sistem Penilaian Bintang (⭐⭐⭐) & Hadiah Koin</h4>
                    <ul class="text-[11px] text-slate-600 space-y-1 list-disc list-inside">
                        <li><b>3 Bintang (⭐⭐⭐)</b>: Berhasil menyusun benar dengan cepat (&lt; 15 detik) ➡️ <b>+50 XP & +25 Koin</b>.</li>
                        <li><b>2 Bintang (⭐⭐)</b>: Berhasil menyusun benar (15 - 25 detik) ➡️ <b>+35 XP & +15 Koin</b>.</li>
                        <li><b>1 Bintang (⭐)</b>: Berhasil menyusun benar (&gt; 25 detik) ➡️ <b>+20 XP & +10 Koin</b>.</li>
                        <li>Menyelesaikan level saat ini akan <b>otomatis membuka level berikutnya</b> pada petualangan solo siswa!</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="pt-5 mt-5 flex items-center justify-end border-t border-slate-100">
            <x-button variant="create" type="button" onclick="closeModal('modalRulesSentence')" class="!bg-purple-600 hover:!bg-purple-700 !rounded-xl !shadow-xs">
                Saya Mengerti, Tutup Panduan
            </x-button>
        </div>
    </div>
</div>
