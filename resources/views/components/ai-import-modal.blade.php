<!-- Modal Import Dokumen Otomatis via AI (Gemini) -->
<div id="aiImportModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-200" 
     role="dialog" 
     aria-modal="true"
     aria-labelledby="aiImportModalTitle">
    
    <!-- Backdrop closer -->
    <div class="fixed inset-0" onclick="closeAiImportModal()"></div>

    <!-- Modal Card Container -->
    <div id="aiImportModalCard" 
         class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10">
        
        <!-- Header Modal -->
        <div class="px-6 pt-6 pb-4 flex items-start justify-between border-b border-slate-100">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-indigo-600 via-[#1E6BFF] to-sky-400 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-500/20">
                    <svg class="w-5 h-5 animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 id="aiImportModalTitle" class="text-base font-bold text-slate-800">Import Dokumen via AI</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-200/60">Gemini AI</span>
                    </div>
                    <p id="aiImportSubtitle" class="text-xs text-slate-400 mt-0.5">Ekstrak otomatis teks & butir soal dari file dokumen</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeAiImportModal()" 
                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors cursor-pointer"
                    title="Tutup">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Form Body (Tampilan Awal Pemilihan File) -->
        <div id="aiImportInitialView" class="p-6 space-y-4">
            <!-- Target Tipe Badge -->
            <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-200/80">
                <div class="flex items-center gap-2.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-slate-600">Target Konversi:</span>
                    <span id="aiImportTargetLabel" class="text-xs font-bold text-slate-800 uppercase tracking-wider">MODUL MATERI</span>
                </div>
                <span class="text-[11px] text-slate-400">PowerPoint / Word / PDF</span>
            </div>

            <!-- Area Drag and Drop -->
            <div id="aiDropZone" 
                 onclick="document.getElementById('aiFileInput').click()"
                 class="relative border-2 border-dashed border-slate-200 hover:border-[#1E6BFF] hover:bg-blue-50/40 rounded-2xl p-6 text-center cursor-pointer transition-all group">
                <input type="file" 
                       id="aiFileInput" 
                       accept=".pptx,.docx,.pdf,.txt" 
                       class="hidden" 
                       onchange="handleAiFileSelect(event)">
                
                <div class="flex flex-col items-center justify-center gap-2">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 group-hover:scale-110 group-hover:bg-[#1E6BFF] group-hover:text-white transition-all flex items-center justify-center">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-700 group-hover:text-[#1E6BFF]">
                            Klik untuk memilih file <span class="font-normal text-slate-400">atau seret ke sini</span>
                        </p>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Mendukung PPTX, DOCX, PDF, dan TXT (Maks. 15 MB)
                        </p>
                    </div>
                </div>

                <!-- Info File Terpilih -->
                <div id="aiFileSelectedInfo" class="hidden mt-3 pt-3 border-t border-slate-100 flex items-center justify-between text-left">
                    <div class="flex items-center gap-2.5 truncate max-w-[85%]">
                        <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 font-bold text-xs">
                            ✓
                        </div>
                        <div class="truncate">
                            <p id="aiSelectedFileName" class="text-xs font-bold text-slate-800 truncate"></p>
                            <p id="aiSelectedFileSize" class="text-[10px] text-slate-400"></p>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="resetAiFile(event)" 
                            class="text-xs text-rose-500 hover:text-rose-700 font-semibold p-1">
                        Ganti
                    </button>
                </div>
            </div>

            <!-- Tips & Bantuan Cepat -->
            <div class="p-3.5 rounded-2xl bg-amber-50/70 border border-amber-200/60 text-amber-800 text-xs flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                <p class="text-[11px] leading-relaxed">
                    <strong>Tips Cerdas:</strong> Dokumen yang memuat teks ketikan digital (bukan hasil foto blur/scan miring) akan menghasilkan kurasi materi dan butir soal yang jauh lebih akurat.
                </p>
            </div>

            <!-- Footer Action -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" 
                        onclick="closeAiImportModal()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="button" 
                        id="aiStartProcessBtn"
                        onclick="startAiUploadProcess()" 
                        disabled
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-[#1E6BFF] to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                    </svg>
                    Mulai Ekstraksi AI
                </button>
            </div>
        </div>

        <!-- Progress State View (Tampil Saat Upload & Sedang Diproses AI) -->
        <div id="aiImportProgressView" class="hidden p-8 text-center space-y-5">
            <!-- Animasi Spinner & Icon -->
            <div class="relative w-20 h-20 mx-auto">
                <div class="absolute inset-0 rounded-full border-4 border-slate-100"></div>
                <div class="absolute inset-0 rounded-full border-4 border-[#1E6BFF] border-t-transparent animate-spin"></div>
                <div class="absolute inset-2 rounded-full bg-gradient-to-tr from-indigo-50 to-blue-50 flex items-center justify-center">
                    <svg class="w-7 h-7 text-[#1E6BFF] animate-pulse" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48l2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48l2.83-2.83"></path>
                    </svg>
                </div>
            </div>

            <!-- Teks Status -->
            <div>
                <h4 id="aiProgressTitle" class="text-sm font-bold text-slate-800">
                    Mengunggah Dokumen...
                </h4>
                <p id="aiProgressSubtitle" class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">
                    Dokumen sedang dikirim ke server untuk diproses di latar belakang.
                </p>
            </div>

            <!-- Progress Bar Visual -->
            <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden max-w-xs mx-auto">
                <div id="aiProgressBar" class="h-full bg-gradient-to-r from-[#1E6BFF] to-indigo-600 transition-all duration-500 rounded-full w-1/4 animate-pulse"></div>
            </div>

            <p class="text-[11px] text-slate-400">
                Proses ekstraksi membutuhkan waktu beberapa detik tergantung tebal dokumen.
            </p>
        </div>

        <!-- Error State View (Jika Terjadi Kendala) -->
        <div id="aiImportErrorView" class="hidden p-6 text-center space-y-4">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center">
                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-800">Ekstraksi Gagal</h4>
                <p id="aiErrorMessage" class="text-xs text-rose-600 mt-1 leading-relaxed bg-rose-50 p-3 rounded-xl border border-rose-200/60 max-w-sm mx-auto text-left"></p>
            </div>
            <div class="flex items-center justify-center gap-3 pt-2">
                <button type="button" 
                        onclick="resetAiModalState()" 
                        class="px-4 py-2.5 rounded-xl text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                    Ganti File
                </button>
                <button type="button" 
                        onclick="startAiUploadProcess()" 
                        class="px-5 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-[#1E6BFF] to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md shadow-blue-500/20 transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                    </svg>
                    Coba Lagi Sekarang
                </button>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript Logika Modal AI Importer -->
<script>
    let currentTargetType = 'materi';
    let selectedAiFile = null;
    let pollInterval = null;

    function openAiImportModal(targetType = 'materi') {
        currentTargetType = targetType;
        document.getElementById('aiImportTargetLabel').innerText = targetType === 'quiz' ? 'PAKET KUIS & SOAL' : 'MODUL MATERI BELAJAR';
        
        resetAiModalState();

        const modal = document.getElementById('aiImportModal');
        const card = document.getElementById('aiImportModalCard');
        
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
    }

    function closeAiImportModal() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }

        const modal = document.getElementById('aiImportModal');
        const card = document.getElementById('aiImportModalCard');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
    }

    function handleAiFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        selectedAiFile = file;
        document.getElementById('aiSelectedFileName').innerText = file.name;
        document.getElementById('aiSelectedFileSize').innerText = (file.size / 1024 / 1024).toFixed(2) + ' MB';
        
        document.getElementById('aiFileSelectedInfo').classList.remove('hidden');
        document.getElementById('aiStartProcessBtn').removeAttribute('disabled');
    }

    function resetAiFile(event) {
        if (event) event.stopPropagation();
        selectedAiFile = null;
        document.getElementById('aiFileInput').value = '';
        document.getElementById('aiFileSelectedInfo').classList.add('hidden');
        document.getElementById('aiStartProcessBtn').setAttribute('disabled', 'disabled');
    }

    function resetAiModalState() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }

        resetAiFile();
        document.getElementById('aiImportInitialView').classList.remove('hidden');
        document.getElementById('aiImportProgressView').classList.add('hidden');
        document.getElementById('aiImportErrorView').classList.add('hidden');
    }

    function startAiUploadProcess() {
        if (!selectedAiFile) return;

        const formData = new FormData();
        formData.append('file', selectedAiFile);
        formData.append('tipe_target', currentTargetType);
        formData.append('_token', '{{ csrf_token() }}');

        // Tampilkan State Progress
        document.getElementById('aiImportInitialView').classList.add('hidden');
        document.getElementById('aiImportProgressView').classList.remove('hidden');
        document.getElementById('aiImportErrorView').classList.add('hidden');

        document.getElementById('aiProgressTitle').innerText = 'Mengunggah Dokumen...';
        document.getElementById('aiProgressSubtitle').innerText = 'File sedang dikirim ke antrean server.';
        document.getElementById('aiProgressBar').style.width = '30%';

        fetch('{{ route("guru.ai-import.upload") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => { throw new Error(data.message || 'Gagal mengunggah dokumen.'); });
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.check_url) {
                document.getElementById('aiProgressTitle').innerText = 'AI Sedang Menganalisis Dokumen...';
                document.getElementById('aiProgressSubtitle').innerText = 'Gemini AI sedang membaca isi slide/paragraf dan merapikan materi serta butir soal.';
                document.getElementById('aiProgressBar').style.width = '65%';
                startPollingStatus(data.check_url);
            } else {
                throw new Error(data.message || 'Respons server tidak valid.');
            }
        })
        .catch(err => {
            showAiError(err.message);
        });
    }

    function startPollingStatus(checkUrl) {
        if (pollInterval) clearInterval(pollInterval);

        pollInterval = setInterval(() => {
            fetch(checkUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'completed') {
                    clearInterval(pollInterval);
                    pollInterval = null;
                    document.getElementById('aiProgressBar').style.width = '100%';
                    document.getElementById('aiProgressTitle').innerText = 'Ekstraksi Berhasil!';
                    document.getElementById('aiProgressSubtitle').innerText = 'Mengalihkan ke halaman review hasil AI...';
                    
                    setTimeout(() => {
                        window.location.href = data.review_url;
                    }, 800);
                } else if (data.status === 'failed') {
                    clearInterval(pollInterval);
                    pollInterval = null;
                    showAiError(data.error_message || 'Terjadi kesalahan saat AI memproses dokumen.');
                }
            })
            .catch(err => {
                console.error('Polling error:', err);
            });
        }, 2000);
    }

    function showAiError(msg) {
        document.getElementById('aiImportInitialView').classList.add('hidden');
        document.getElementById('aiImportProgressView').classList.add('hidden');
        document.getElementById('aiImportErrorView').classList.remove('hidden');
        document.getElementById('aiErrorMessage').innerText = msg;
    }

    // Drag and drop event listeners
    const dropZone = document.getElementById('aiDropZone');
    if (dropZone) {
        ['dragenter', 'dragover'].forEach(name => {
            dropZone.addEventListener(name, (e) => {
                e.preventDefault();
                dropZone.classList.add('border-[#1E6BFF]', 'bg-blue-50/50');
            }, false);
        });

        ['dragleave', 'drop'].forEach(name => {
            dropZone.addEventListener(name, (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-[#1E6BFF]', 'bg-blue-50/50');
            }, false);
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length > 0) {
                document.getElementById('aiFileInput').files = files;
                handleAiFileSelect({ target: { files: files } });
            }
        }, false);
    }
</script>
