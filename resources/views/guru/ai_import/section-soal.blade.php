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
        
        let typeSelector = '';
        let tfWrapper = '';
        let readingField = '';
        if (isQuizType) {
            typeSelector = `
                <select name="soals[${idx}][tipe_soal]"
                        onchange="toggleAiQuestionType(this, '${idx}')"
                        class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-[11px] font-semibold text-slate-700 outline-none cursor-pointer">
                    <option value="multiple_choice" selected>📝 Pilihan Ganda (A-D)</option>
                    <option value="reading">📖 Reading (Teks + A-D)</option>
                    <option value="true_false">✓✗ True / False (Tabel Pernyataan)</option>
                </select>
            `;
            readingField = `
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-500">Teks Bacaan Terkait (Reading Passage - Opsional)</label>
                    <textarea name="soals[${idx}][reading_passage]" rows="2" placeholder="Isi jika soal ini mengacu pada suatu teks cerita/dialog..." class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 outline-none"></textarea>
                </div>
            `;
            tfWrapper = `
                <div id="ai-tf-wrapper-${idx}" class="hidden p-4 rounded-2xl bg-[#EAF5EF]/60 border border-[#D7EDE1] space-y-3">
                    <div class="flex items-center justify-between gap-2 flex-wrap">
                        <div>
                            <p class="text-[11px] font-bold text-[#2E7352]">✓✗ Tabel Pernyataan True / False</p>
                            <p class="text-[10px] text-slate-500">Isi pernyataan di setiap baris dan tentukan kunci Benar (True) atau Salah (False).</p>
                        </div>
                        <button type="button" onclick="addAiTrueFalseRow('${idx}')" class="px-3 py-1.5 rounded-xl bg-white hover:bg-[#EAF5EF] text-[#2E7352] border border-[#C6E5D4] font-semibold text-[11px] transition-colors cursor-pointer">+ Tambah Baris</button>
                    </div>
                    <div class="overflow-x-auto rounded-xl border border-slate-200/80 bg-white">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/90 border-b border-slate-200/70 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                    <th class="py-2 px-3 w-10 text-center">No</th>
                                    <th class="py-2 px-3">Pernyataan</th>
                                    <th class="py-2 px-3 w-40">Kunci Jawaban</th>
                                    <th class="py-2 px-2 w-10 text-center">Hapus</th>
                                </tr>
                            </thead>
                            <tbody id="ai-tf-tbody-${idx}" data-next-row="2" class="divide-y divide-slate-100 text-xs">
                                ${buildAiTfRowHtml(idx, 0, 1)}
                                ${buildAiTfRowHtml(idx, 1, 2)}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        div.innerHTML = `
            <div class="flex items-center justify-between gap-2 flex-wrap border-b border-slate-200/60 pb-3">
                <span class="px-3 py-1 rounded-xl text-xs font-black bg-white text-slate-800 border border-slate-200/80 shadow-2xs soal-index-badge">
                    Soal #${idx + 1}
                </span>
                <div class="flex items-center gap-3">
                    ${typeSelector}
                    <button type="button" onclick="removeQuestion(this)" class="text-xs text-rose-500 hover:text-rose-700 font-semibold flex items-center gap-1 cursor-pointer">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Soal
                    </button>
                </div>
            </div>
            ${readingField}
            <div class="space-y-1">
                <label id="ai-pertanyaan-label-${idx}" class="block text-xs font-bold text-slate-700">Kalimat Pertanyaan <span class="text-rose-500">*</span></label>
                <textarea id="ai-pertanyaan-input-${idx}" name="soals[${idx}][pertanyaan]" rows="2" required placeholder="Tuliskan pertanyaan dalam bahasa Inggris..." class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none"></textarea>
            </div>
            ${tfWrapper}
            <div id="ai-abcd-wrapper-${idx}" class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan A</label>
                    <input type="text" name="soals[${idx}][pilihan_a]" required class="ai-abcd-input-${idx} w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan B</label>
                    <input type="text" name="soals[${idx}][pilihan_b]" required class="ai-abcd-input-${idx} w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan C</label>
                    <input type="text" name="soals[${idx}][pilihan_c]" required class="ai-abcd-input-${idx} w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-[11px] font-bold text-slate-600 uppercase">Pilihan D</label>
                    <input type="text" name="soals[${idx}][pilihan_d]" required class="ai-abcd-input-${idx} w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 border-t border-slate-200/60">
                <div id="ai-kunci-wrapper-${idx}">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kunci Jawaban <span class="text-rose-500">*</span></label>
                    <select name="soals[${idx}][kunci_jawaban]" class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-800 focus:border-[#1E6BFF] outline-none cursor-pointer">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                <div id="ai-pembahasan-wrapper-${idx}" class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Penjelasan / Pembahasan Singkat</label>
                    <input type="text" name="soals[${idx}][pembahasan]" placeholder="Alasan mengapa jawaban tersebut benar..." class="w-full px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs text-slate-800 focus:border-[#1E6BFF] outline-none">
                </div>
            </div>
        `;
        container.appendChild(div);
        updateQuestionBadges();
    }

    function buildAiTfRowHtml(soalIdx, rowIdx, displayNum) {
        return `
            <tr class="ai-tf-row hover:bg-slate-50/60">
                <td class="py-2 px-3 text-center font-bold text-slate-400 ai-tf-row-num">${displayNum}</td>
                <td class="py-2 px-3">
                    <input type="text" name="soals[${soalIdx}][pernyataan_items][${rowIdx}][teks]" placeholder="Tuliskan pernyataan ke-${displayNum}..." class="w-full px-3 py-1.5 rounded-lg bg-slate-50 border border-slate-200/80 text-xs text-slate-800 focus:bg-white focus:border-[#4EA87D] outline-none">
                </td>
                <td class="py-2 px-3">
                    <select name="soals[${soalIdx}][pernyataan_items][${rowIdx}][kunci]" class="w-full px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-xs font-bold text-emerald-700 outline-none cursor-pointer" onchange="this.classList.toggle('text-emerald-700', this.value === 'true'); this.classList.toggle('text-rose-600', this.value === 'false');">
                        <option value="true" selected>✓ True (Benar)</option>
                        <option value="false">✗ False (Salah)</option>
                    </select>
                </td>
                <td class="py-2 px-2 text-center">
                    <button type="button" onclick="removeAiTrueFalseRow(this)" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" title="Hapus Baris">×</button>
                </td>
            </tr>
        `;
    }

    function toggleAiQuestionType(select, idx) {
        const isTf = select.value === 'true_false';
        const tfWrap = document.getElementById(`ai-tf-wrapper-${idx}`);
        const abcdWrap = document.getElementById(`ai-abcd-wrapper-${idx}`);
        const kunciWrap = document.getElementById(`ai-kunci-wrapper-${idx}`);
        const pembahasanWrap = document.getElementById(`ai-pembahasan-wrapper-${idx}`);
        const pertanyaanLabel = document.getElementById(`ai-pertanyaan-label-${idx}`);
        const pertanyaanInput = document.getElementById(`ai-pertanyaan-input-${idx}`);
        const abcdInputs = document.querySelectorAll(`.ai-abcd-input-${idx}`);

        if (tfWrap) tfWrap.classList.toggle('hidden', !isTf);
        if (abcdWrap) abcdWrap.classList.toggle('hidden', isTf);
        if (kunciWrap) kunciWrap.classList.toggle('hidden', isTf);
        if (pembahasanWrap) {
            pembahasanWrap.classList.toggle('sm:col-span-3', isTf);
            pembahasanWrap.classList.toggle('sm:col-span-2', !isTf);
        }
        abcdInputs.forEach((inp) => {
            if (isTf) inp.removeAttribute('required');
            else inp.setAttribute('required', 'required');
        });
        if (pertanyaanLabel) {
            pertanyaanLabel.innerHTML = isTf
                ? 'Instruksi / Teks Pengantar Tabel (Opsional)'
                : 'Kalimat Pertanyaan <span class="text-rose-500">*</span>';
        }
        if (pertanyaanInput) {
            if (isTf) pertanyaanInput.removeAttribute('required');
            else pertanyaanInput.setAttribute('required', 'required');
        }
    }

    function addAiTrueFalseRow(idx) {
        const tbody = document.getElementById(`ai-tf-tbody-${idx}`);
        if (!tbody) return;
        const rowIdx = parseInt(tbody.dataset.nextRow || tbody.children.length, 10);
        tbody.dataset.nextRow = rowIdx + 1;
        const displayNum = tbody.querySelectorAll('.ai-tf-row').length + 1;
        tbody.insertAdjacentHTML('beforeend', buildAiTfRowHtml(idx, rowIdx, displayNum));
    }

    function removeAiTrueFalseRow(btn) {
        const tbody = btn.closest('tbody');
        const row = btn.closest('.ai-tf-row');
        if (!tbody || !row) return;
        if (tbody.querySelectorAll('.ai-tf-row').length <= 1) {
            alert('Soal True / False minimal memiliki 1 baris pernyataan.');
            return;
        }
        row.remove();
        tbody.querySelectorAll('.ai-tf-row').forEach((r, idx) => {
            const num = r.querySelector('.ai-tf-row-num');
            if (num) num.textContent = idx + 1;
        });
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
