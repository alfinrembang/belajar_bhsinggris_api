@props([
    'action',
    'checkboxClass',
    'itemLabel' => 'item',
    'totalPageCount' => 0,
    'inputName' => 'ids[]',
    'warningText' => 'Data yang dipilih beserta seluruh relasi terkait akan dihapus secara permanen dari sistem.',
])

<!-- Floating Bar Aksi Massal (Muncul saat ada item yang dicentang) -->
<div id="bulkActionBar" 
     class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 bg-slate-900/95 backdrop-blur-md text-white px-5 py-3 rounded-2xl shadow-2xl border border-slate-700/80 flex items-center gap-4 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
    
    <div class="flex items-center gap-3">
        <!-- Master Checkbox Toggle Cepat -->
        <button type="button" 
                onclick="togglePilihSemuaSemua()" 
                id="btnSelectAllFloating"
                title="Pilih seluruh {{ $itemLabel }} di halaman ini"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold text-slate-200 border border-slate-700 transition-colors cursor-pointer">
            <span id="floatingCheckIcon" class="w-4 h-4 rounded-md border border-slate-400 flex items-center justify-center text-[11px] font-bold"></span>
            <span id="floatingCheckText">Pilih Semua ({{ $totalPageCount }})</span>
        </button>

        <div class="h-5 w-px bg-slate-700"></div>

        <div class="flex items-center gap-2 text-xs">
            <span class="w-2 h-2 rounded-full bg-[#4EA87D] animate-pulse"></span>
            <span class="font-bold text-white text-sm" id="selectedCountText">0</span>
            <span class="text-slate-300">{{ $itemLabel }} dipilih</span>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button type="button" 
                onclick="batalPilihSemua()" 
                class="px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-300 hover:text-white hover:bg-slate-800 transition-colors cursor-pointer">
            Batal
        </button>

        <button type="button" 
                onclick="bukaModalBulkDelete()" 
                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md shadow-rose-600/30 flex items-center gap-2 transition-all active:scale-95 cursor-pointer">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="3 6 5 6 21 6"></polyline>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                <line x1="10" y1="11" x2="10" y2="17"></line>
                <line x1="14" y1="11" x2="14" y2="17"></line>
            </svg>
            <span>Hapus Terpilih</span>
        </button>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Massal -->
<div id="modalBulkDelete" 
     class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all duration-200 opacity-0 pointer-events-none">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 transform scale-95 transition-all duration-200" id="modalBulkDeleteBox">
        <div class="flex items-center gap-3.5 mb-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Hapus {{ ucwords($itemLabel) }} Terpilih</h3>
                <p class="text-xs text-slate-400 mt-0.5">Penghapusan massal {{ $itemLabel }} yang dicentang</p>
            </div>
        </div>

        <form id="formBulkDelete" method="POST" action="{{ $action }}" class="space-y-4">
            @csrf
            <!-- Container input hidden ID terpilih diisi via Javascript -->
            <div id="bulkDeleteInputsContainer"></div>

            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus <strong id="modalBulkCountText" class="text-rose-600 font-extrabold text-sm">0</strong> {{ $itemLabel }} yang telah dipilih?
            </p>

            <div class="p-3.5 rounded-2xl bg-rose-50/80 border border-rose-200/70 text-xs text-rose-700 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-rose-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    <line x1="12" y1="9" x2="12" y2="13"></line>
                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                </svg>
                <p class="leading-relaxed">
                    {{ $warningText }}
                </p>
            </div>

            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <x-button variant="cancel" type="button" onclick="tutupModalBulkDelete()" class="!rounded-xl">
                    Batal
                </x-button>
                <x-button variant="delete" type="submit" class="!rounded-xl !bg-rose-600 hover:!bg-rose-700 !text-white !border-transparent !shadow-xs">
                    Ya, Hapus Sekarang
                </x-button>
            </div>
        </form>
    </div>
</div>

<!-- Script Pengendali Centang Masal & Modal Hapus -->
<script>
    const BULK_CHECKBOX_CLASS = '{{ $checkboxClass }}';
    const BULK_INPUT_NAME = '{{ $inputName }}';

    function getSelectedCheckboxes() {
        return document.querySelectorAll('.' + BULK_CHECKBOX_CLASS + ':checked');
    }

    function getAllCheckboxes() {
        return document.querySelectorAll('.' + BULK_CHECKBOX_CLASS);
    }

    function onItemCheckboxChange() {
        const total = getAllCheckboxes().length;
        const selected = getSelectedCheckboxes().length;
        const selectAllCb = document.getElementById('selectAllCheckbox');
        const bulkBar = document.getElementById('bulkActionBar');
        const countText = document.getElementById('selectedCountText');
        const floatingCheckIcon = document.getElementById('floatingCheckIcon');
        const floatingCheckText = document.getElementById('floatingCheckText');

        if (selectAllCb) {
            selectAllCb.checked = selected > 0 && selected === total;
            selectAllCb.indeterminate = selected > 0 && selected < total;
        }

        getAllCheckboxes().forEach(cb => {
            const tr = cb.closest('tr');
            if (tr) {
                if (cb.checked) {
                    tr.classList.add('bg-[#EAF5EF]/60', 'hover:bg-[#EAF5EF]');
                } else {
                    tr.classList.remove('bg-[#EAF5EF]/60', 'hover:bg-[#EAF5EF]');
                }
            }
        });

        if (bulkBar) {
            if (selected > 0) {
                bulkBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
                bulkBar.classList.add('translate-y-0', 'opacity-100');
                if (countText) countText.textContent = selected;

                if (selected === total) {
                    if (floatingCheckIcon) floatingCheckIcon.innerHTML = '✓';
                    if (floatingCheckText) floatingCheckText.textContent = 'Batal Pilih Semua';
                } else {
                    if (floatingCheckIcon) floatingCheckIcon.innerHTML = '';
                    if (floatingCheckText) floatingCheckText.textContent = 'Pilih Semua (' + total + ')';
                }
            } else {
                bulkBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
                bulkBar.classList.remove('translate-y-0', 'opacity-100');
                if (floatingCheckIcon) floatingCheckIcon.innerHTML = '';
                if (floatingCheckText) floatingCheckText.textContent = 'Pilih Semua (' + total + ')';
            }
        }
    }

    function togglePilihSemua(masterCb) {
        const isChecked = masterCb.checked;
        getAllCheckboxes().forEach(cb => {
            cb.checked = isChecked;
        });
        onItemCheckboxChange();
    }

    function togglePilihSemuaSemua() {
        const total = getAllCheckboxes().length;
        const selected = getSelectedCheckboxes().length;
        const shouldCheck = selected < total;

        getAllCheckboxes().forEach(cb => {
            cb.checked = shouldCheck;
        });
        onItemCheckboxChange();
    }

    function batalPilihSemua() {
        getAllCheckboxes().forEach(cb => {
            cb.checked = false;
        });
        const selectAllCb = document.getElementById('selectAllCheckbox');
        if (selectAllCb) {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = false;
        }
        onItemCheckboxChange();
    }

    function bukaModalBulkDelete() {
        const selected = getSelectedCheckboxes();
        if (selected.length === 0) return;

        const container = document.getElementById('bulkDeleteInputsContainer');
        container.innerHTML = '';

        selected.forEach(cb => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = BULK_INPUT_NAME;
            hidden.value = cb.value;
            container.appendChild(hidden);
        });

        const modalCount = document.getElementById('modalBulkCountText');
        if (modalCount) modalCount.textContent = selected.length;

        const modal = document.getElementById('modalBulkDelete');
        const box = document.getElementById('modalBulkDeleteBox');
        if (modal && box) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.classList.add('opacity-100');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
        }
    }

    function tutupModalBulkDelete() {
        const modal = document.getElementById('modalBulkDelete');
        const box = document.getElementById('modalBulkDeleteBox');
        if (modal && box) {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100');
            box.classList.add('scale-95');
            box.classList.remove('scale-100');
        }
    }
</script>
