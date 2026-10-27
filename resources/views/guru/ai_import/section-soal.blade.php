<!-- SECTION 2: BUTIR SOAL LATIHAN / KUIS -->
<div class="bg-white rounded-3xl border border-slate-200/80 p-6 lg:p-8 shadow-xs space-y-6">
    <x-section-header 
        number="02" 
        :title="$aiImport->tipe_target === 'quiz' ? 'Daftar Butir Soal Kuis' : 'Latihan Soal Pemahaman'" 
        color="purple"
    >
        <x-slot:subtitle>
            Hasil kurasi AI menemukan <span id="soalCountBadge" class="font-bold text-slate-700">{{ count($hasil['soals'] ?? []) }}</span> butir soal
        </x-slot:subtitle>

        <!-- Tombol Tambah Soal Manual -->
        <button type="button" 
                onclick="addEmptyQuestion()" 
                class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors flex items-center gap-1.5 cursor-pointer active:scale-95">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Tambah Soal
        </button>
    </x-section-header>

    <!-- Kontainer Kartu Soal -->
    <div id="soalContainer" class="space-y-5">
        @php $soals = $hasil['soals'] ?? []; @endphp
        @forelse($soals as $idx => $s)
            <x-ai-soal-item :index="$idx" :soal="$s" :isQuiz="$aiImport->tipe_target === 'quiz'" />
        @empty
            <div id="soalEmptyNotice" class="text-center py-8 text-xs text-slate-400">
                Tidak ada butir soal yang terdeteksi dari dokumen ini. Klik tombol <strong>Tambah Soal</strong> di atas jika ingin menambahkan latihan.
            </div>
        @endforelse
    </div>
</div>

<!-- Script Interaktif Penambahan & Penghapusan Butir Soal -->
<script>
    let questionCounter = {{ count($hasil['soals'] ?? []) }};
    const isQuizType = {{ $aiImport->tipe_target === 'quiz' ? 'true' : 'false' }};

    function addEmptyQuestion() {
        const container = document.getElementById('soalContainer');
        const emptyNotice = document.getElementById('soalEmptyNotice');
        if (emptyNotice) emptyNotice.remove();

        const idx = questionCounter++;
        const div = document.createElement('div');
        div.className = 'soal-item p-5 rounded-2xl border border-slate-200/90 bg-slate-50/50 space-y-4 relative transition-all hover:border-slate-300';
        
        let readingField = '';
        if (isQuizType) {
            readingField = `
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-500">Teks Bacaan Terkait (Reading Passage - Opsional)</label>
                    <textarea name="soals[${idx}][reading_passage]" rows="2" placeholder="Isi jika soal ini mengacu pada suatu teks cerita/dialog..." class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 outline-none"></textarea>
                </div>
            `;
        }

        div.innerHTML = `
            <div class="flex items-center justify-between border-b border-slate-200/60 pb-3">
                <span class="px-3 py-1 rounded-xl text-xs font-black bg-white text-slate-800 border border-slate-200/80 shadow-2xs soal-index-badge">
                    Soal #${idx + 1}
                </span>
                <button type="button" onclick="removeQuestion(this)" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Hapus Soal
                </button>
            </div>
            ${readingField}
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-700">Kalimat Pertanyaan <span class="text-rose-500">*</span></label>
                <textarea name="soals[${idx}][pertanyaan]" rows="2" required placeholder="Tuliskan pertanyaan dalam bahasa Inggris..." class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan A</label>
                    <input type="text" name="soals[${idx}][pilihan_a]" required class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan B</label>
                    <input type="text" name="soals[${idx}][pilihan_b]" required class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan C</label>
                    <input type="text" name="soals[${idx}][pilihan_c]" required class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan D</label>
                    <input type="text" name="soals[${idx}][pilihan_d]" required class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-200/60">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-rose-500">*</span></label>
                    <select name="soals[${idx}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 focus:border-[#1E6BFF] outline-none cursor-pointer">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan / Pembahasan Singkat</label>
                    <input type="text" name="soals[${idx}][pembahasan]" placeholder="Alasan mengapa jawaban tersebut benar..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
            </div>
        `;
        container.appendChild(div);
        updateQuestionBadges();
    }

    function removeQuestion(btn) {
        const item = btn.closest('.soal-item');
        if (item) {
            item.remove();
            updateQuestionBadges();
        }
    }

    function updateQuestionBadges() {
        const items = document.querySelectorAll('.soal-item');
        items.forEach((item, index) => {
            const badge = item.querySelector('.soal-index-badge');
            if (badge) badge.innerText = `Soal #${index + 1}`;
        });
        const badgeCount = document.getElementById('soalCountBadge');
        if (badgeCount) badgeCount.innerText = items.length;
    }
</script>
