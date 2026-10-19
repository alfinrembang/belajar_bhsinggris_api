<!-- Modal Pop-up Konfirmasi Hapus Reusable & Modern -->
<div id="globalDeleteModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-200" 
     role="dialog" 
     aria-modal="true"
     aria-labelledby="modalDeleteTitle">
    
    <!-- Background Backdrop Closer -->
    <div class="fixed inset-0" onclick="tutupModalHapus()"></div>

    <!-- Modal Card Container -->
    <div id="globalDeleteCard" 
         class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10">
        
        <!-- Header Modal -->
        <div class="px-6 pt-6 pb-4 flex items-start justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </div>
                <div>
                    <h3 id="modalDeleteTitle" class="text-base font-bold text-slate-800">Konfirmasi Hapus Data</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tindakan ini tidak dapat dibatalkan</p>
                </div>
            </div>
            <button type="button" 
                    onclick="tutupModalHapus()" 
                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors"
                    title="Tutup">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Form Eksekusi Hapus -->
        <form id="globalDeleteForm" method="POST" action="" class="px-6 pb-6 space-y-4">
            @csrf
            <input type="hidden" name="_method" id="globalDeleteMethod" value="DELETE">

            <!-- Pesan Konfirmasi -->
            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus <strong id="modalDeleteItemName" class="text-slate-900 font-bold">item ini</strong>?
            </p>

            <!-- Alert Peringatan Tambahan -->
            <div class="p-3.5 rounded-xl bg-rose-50/80 border border-rose-200/70 text-xs text-rose-700 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <p id="modalDeleteWarning" class="leading-relaxed">
                    Data yang sudah dihapus akan hilang dari server dan tidak dapat dikembalikan lagi.
                </p>
            </div>

            <!-- Tombol Aksi Batal & Ya Hapus -->
            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" 
                        onclick="tutupModalHapus()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-800 text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        id="modalDeleteSubmitBtn"
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-[0_4px_14px_rgba(225,29,72,0.25)] transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    <span>Ya, Hapus Data</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Membuka modal konfirmasi hapus data secara universal.
     * @param {string} actionUrl - URL target penghapusan
     * @param {string} itemName - Nama item atau judul yang akan dihapus
     * @param {string|null} customWarning - Pesan peringatan opsional
     * @param {string} method - HTTP method ('DELETE' atau 'POST')
     */
    function konfirmasiHapus(actionUrl, itemName = 'data ini', customWarning = null, method = 'DELETE') {
        const modal = document.getElementById('globalDeleteModal');
        const card = document.getElementById('globalDeleteCard');
        const form = document.getElementById('globalDeleteForm');
        const itemNameEl = document.getElementById('modalDeleteItemName');
        const warningEl = document.getElementById('modalDeleteWarning');
        const methodInput = document.getElementById('globalDeleteMethod');

        if (!modal || !card || !form) return;

        form.action = actionUrl;
        if (itemNameEl) itemNameEl.textContent = itemName;
        if (warningEl) {
            warningEl.textContent = customWarning || 'Data yang sudah dihapus akan hilang dari server dan tidak dapat dikembalikan lagi.';
        }
        if (methodInput) {
            methodInput.value = method;
        }

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    /**
     * Menutup modal konfirmasi hapus.
     */
    function tutupModalHapus() {
        const modal = document.getElementById('globalDeleteModal');
        const card = document.getElementById('globalDeleteCard');

        if (!modal || !card) return;

        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        card.classList.add('scale-95');
        card.classList.remove('scale-100');
        document.body.classList.remove('overflow-hidden');
    }

    // Listener tombol ESC keyboard
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            tutupModalHapus();
        }
    });
</script>
