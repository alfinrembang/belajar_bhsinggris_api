<!-- MODAL: Tambah Soal Kosakata Game Tangkap Ikan (Pop-up Form) -->
<div id="modalTambahSoalIkan" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl p-6 lg:p-7 shadow-2xl max-h-[90vh] overflow-y-auto border border-slate-100">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-50 text-sky-700 flex items-center justify-center shrink-0 border border-sky-200/70">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base tracking-tight">Tambah Butir Soal Kosakata</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Disinkronkan otomatis ke game Tangkap Ikan di mobile.</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeModal('modalTambahSoalIkan')" 
                    class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-700 flex items-center justify-center text-sm font-bold transition-colors cursor-pointer">
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
                            <option value="{{ $lvl }}">Level {{ $lvl }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kesulitan</label>
                    <select name="difficulty" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#4EA87D] focus:ring-2 focus:ring-[#4EA87D]/15 outline-none transition-all">
                        <option value="mudah">Mudah</option>
                        <option value="sedang">Sedang</option>
                        <option value="sulit">Sulit</option>
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
                        <option value="Adjective (Kata Sifat)">Adjective (Kata Sifat)</option>
                        <option value="Verb (Kata Kerja)">Verb (Kata Kerja)</option>
                        <option value="Noun (Kata Benda)">Noun (Kata Benda)</option>
                        <option value="Adverb (Kata Keterangan)">Adverb (Kata Keterangan)</option>
                    </select>
                </div>
            </div>

            <!-- Arti Kata / Kunci Jawaban Benar -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Arti Kata / Kunci Jawaban (Bahasa Indonesia)</label>
                <div class="relative">
                    <input type="text" 
                           name="correct_answer" 
                           required 
                           placeholder="Contoh: Dermawan / Suka Memberi" 
                           class="w-full px-3.5 py-2.5 rounded-xl bg-emerald-50/70 border border-emerald-300 text-xs font-bold text-emerald-900 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/15 outline-none transition-all">
                    <input type="hidden" name="meaning" id="hiddenMeaningInput" value="">
                </div>
            </div>

            <!-- 3 Pilihan Pengecoh (Distractors) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">3 Pilihan Pengecoh (Pilihan Jawaban Salah)</label>
                <div class="space-y-2">
                    <input type="text" name="option_2" required placeholder="Pilihan Salah 1 (contoh: Pelit / Kikir)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                    <input type="text" name="option_3" required placeholder="Pilihan Salah 2 (contoh: Pemarah)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                    <input type="text" name="option_4" required placeholder="Pilihan Salah 3 (contoh: Penakut)" class="w-full px-3.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
                </div>
            </div>

            <!-- Contoh Kalimat Praktis -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Contoh Kalimat Penggunaan (Opsional)</label>
                <input type="text" 
                       name="example_sentence" 
                       placeholder="Contoh: Mr. Jack is a generous teacher." 
                       class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all">
            </div>

            <!-- Penjelasan Edukasi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan Edukasi Kontekstual (Opsional)</label>
                <textarea name="explanation" 
                          rows="2" 
                          placeholder="Penjelasan mengapa kata tersebut memiliki makna demikian atau konteks penggunaannya..."
                          class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none transition-all"></textarea>
            </div>

            <!-- Footer Aksi Tombol -->
            <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="closeModal('modalTambahSoalIkan')" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="submit" type="submit" onclick="document.getElementById('hiddenMeaningInput').value = document.querySelector('input[name=correct_answer]').value" class="!rounded-xl !shadow-xs">
                    Simpan Butir Soal
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- Script Modal & Accordion Buka-Tutup Bank Soal -->
<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.remove('hidden');
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    }

    // Buka / Tutup Panel Bank Soal Tangkap Ikan
    function toggleBankSoal() {
        const panel = document.getElementById('panelBankSoalIkan');
        const btnText = document.getElementById('textToggleBankSoal');
        const btnIcon = document.getElementById('iconToggleBankSoal');

        if (!panel) return;

        if (panel.classList.contains('hidden')) {
            panel.classList.remove('hidden');
            if (btnText) btnText.textContent = 'Tutup Bank Soal';
            if (btnIcon) btnIcon.classList.add('rotate-180');
        } else {
            panel.classList.add('hidden');
            if (btnText) btnText.textContent = 'Kelola Bank Soal';
            if (btnIcon) btnIcon.classList.remove('rotate-180');
        }
    }
</script>
