<!-- Card Sakelar Periode Kenaikan Kelas & Tahun Ajaran (Administrator Control) -->
<div class="bg-white rounded-2xl border {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'border-emerald-300 ring-2 ring-emerald-500/10' : 'border-slate-200/80' }} shadow-[0_1px_3px_rgba(0,0,0,0.03)] p-5 lg:p-6 transition-all">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        <div class="flex items-start gap-4">
            <!-- Icon Status Tanpa Emoji -->
            <div class="w-11 h-11 rounded-xl {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-200/60' }} flex items-center justify-center shrink-0">
                @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                    </svg>
                @else
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                @endif
            </div>

            <div>
                <div class="flex flex-wrap items-center gap-2.5">
                    <h2 class="font-bold text-slate-800 text-base">
                        Periode Kenaikan Kelas — Tahun Ajaran <span class="text-blue-600 font-semibold">{{ $pengaturanAkademik->tahun_ajaran }}</span>
                    </h2>
                    @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            PERIODE AKTIF (DIBUKA)
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600 border border-slate-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                            TERKUNCI (NORMAL)
                        </span>
                    @endif
                </div>

                <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
                    @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                        Siswa di aplikasi mobile saat ini dapat memilih rombel kelas dan nomor absen baru. Tutup periode jika proses kenaikan kelas telah selesai.
                    @else
                        Rombel siswa di aplikasi mobile saat ini terkunci aman. Siswa tidak dapat mengganti kelas secara mandiri tanpa otorisasi admin/guru.
                    @endif
                </p>

                <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-2.5 text-[11px] text-slate-400">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Otomatis berganti tiap Juli
                    </span>
                    @if($pengaturanAkademik->opened_at && $pengaturanAkademik->is_periode_kenaikan_aktif)
                        <span class="text-emerald-600 font-medium">• Dibuka: {{ $pengaturanAkademik->opened_at->diffForHumans() }}</span>
                    @elseif($pengaturanAkademik->closed_at)
                        <span>• Terakhir ditutup: {{ $pengaturanAkademik->closed_at->diffForHumans() }}</span>
                    @endif
                    @if($pengaturanAkademik->keterangan)
                        <span class="text-slate-500">• Catatan: "{{ $pengaturanAkademik->keterangan }}"</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Buka Modal Konfirmasi Sakelar -->
        <div class="shrink-0">
            @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                <button type="button" 
                        onclick="openAdminPromotionModal()" 
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200/80 font-semibold text-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4 text-rose-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Tutup Periode Kenaikan Kelas</span>
                </button>
            @else
                <button type="button" 
                        onclick="openAdminPromotionModal()" 
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-95">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                    </svg>
                    <span>Buka Periode Kenaikan Kelas</span>
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Buka/Tutup Periode Kenaikan Kelas (Khusus Administrator) -->
<div id="adminPromotionModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300"
     role="dialog" 
     aria-modal="true">
    
    <div id="adminPromotionModalCard" 
         class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-300">
        
        <!-- Tombol Close (X) -->
        <button type="button" 
                onclick="closeAdminPromotionModal()" 
                class="absolute top-4 right-4 w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors z-10 cursor-pointer">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <!-- Form Toggle Buka/Tutup Sakelar -->
        <form id="formAdminToggleKenaikan" action="{{ route('admin.kenaikan_kelas.toggle') }}" method="POST">
            @csrf

            <!-- Bagian Atas: Background Header & Ikon Dinamis -->
            <div class="p-6 {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-rose-50/50' : 'bg-emerald-50/50' }} border-b border-slate-100">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center shrink-0">
                        @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                            </svg>
                        @endif
                    </div>

                    <div class="flex-1 pr-6">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-[11px] font-semibold {{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }} mb-1.5">
                            <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>Tahun Ajaran {{ $pengaturanAkademik->tahun_ajaran }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-800 leading-snug">
                            @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                                Tutup Periode Kenaikan Kelas?
                            @else
                                Buka Periode Kenaikan Kelas?
                            @endif
                        </h3>

                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                            @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                                Data rombel di aplikasi siswa akan dikunci kembali (*read-only*). Siswa tidak dapat mengganti kelas secara sepihak.
                            @else
                                Siswa di aplikasi mobile dapat memperbarui tingkat kelas, rombel jurusan, dan nomor absen melalui profil akun.
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Poin Ringkas -->
                <div class="mt-4 p-3.5 rounded-xl bg-white border border-slate-200/70 space-y-2 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span>Seluruh riwayat nilai, kuis, dan XP siswa tetap aman 100%.</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>Hanya Administrator yang berwenang membuka/menutup sakelar ini.</span>
                    </div>
                </div>

                <!-- Input Catatan / Keterangan Tambahan -->
                <div class="mt-4">
                    <label for="adminPromotionKeterangan" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Catatan Administrator (Opsional)
                    </label>
                    <input type="text" 
                           id="adminPromotionKeterangan" 
                           name="keterangan" 
                           placeholder="{{ $pengaturanAkademik->is_periode_kenaikan_aktif ? 'Misal: Ditutup setelah pendaftaran rombel selesai' : 'Misal: Dibuka untuk periode semester genap' }}" 
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all bg-white"
                           maxlength="255">
                </div>
            </div>

            <!-- Tombol Aksi Bawah -->
            <div class="p-4 px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" 
                        onclick="closeAdminPromotionModal()" 
                        class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 font-semibold text-xs transition-colors cursor-pointer">
                    Batal
                </button>

                @if($pengaturanAkademik->is_periode_kenaikan_aktif)
                    <button type="submit" 
                            id="btnSubmitAdminPromotion"
                            class="px-5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Tutup Periode Sekarang</span>
                    </button>
                @else
                    <button type="submit" 
                            id="btnSubmitAdminPromotion"
                            class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-all flex items-center gap-2 cursor-pointer active:scale-95">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 9.9-1"></path>
                        </svg>
                        <span>Buka Periode Sekarang</span>
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Script Interaktif Modal Administrator -->
<script>
    function openAdminPromotionModal() {
        const modal = document.getElementById('adminPromotionModal');
        const card = document.getElementById('adminPromotionModalCard');
        if (!modal || !card) return;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');

        card.classList.remove('scale-95');
        card.classList.add('scale-100');

        document.body.classList.add('overflow-hidden');
    }

    function closeAdminPromotionModal() {
        const modal = document.getElementById('adminPromotionModal');
        const card = document.getElementById('adminPromotionModalCard');
        if (!modal || !card) return;

        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');

        card.classList.remove('scale-100');
        card.classList.add('scale-95');

        document.body.classList.remove('overflow-hidden');
    }

    document.getElementById('formAdminToggleKenaikan')?.addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitAdminPromotion');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span>Menyimpan...</span>
            `;
        }
    });

    document.getElementById('adminPromotionModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdminPromotionModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAdminPromotionModal();
        }
    });
</script>
