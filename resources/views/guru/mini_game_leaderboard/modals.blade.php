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
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel / CSV (.xlsx, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#EAF5EF] file:text-[#2E7352] hover:file:bg-[#d7ede1] file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.fishing.template') }}" class="text-[#2E7352] font-bold underline hover:text-[#1d4c36] flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Contoh</span>
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
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilih File Excel / CSV (.xlsx, .csv)</label>
                <input type="file" 
                       name="file_excel" 
                       required 
                       accept=".xlsx,.csv,.txt"
                       class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer p-2 rounded-2xl border border-slate-200 bg-slate-50">
            </div>

            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800">Format Kolom Excel:</span>
                    <a href="{{ route('guru.game.werewolf.template') }}" class="text-indigo-600 font-bold underline hover:text-indigo-800 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                        <span>Unduh Template Contoh</span>
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

<!-- Script Modal Open / Close -->
<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }
</script>
