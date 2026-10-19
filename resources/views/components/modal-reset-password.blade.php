<!-- Modal Pop-up Konfirmasi Reset Kata Sandi Siswa -->
<div id="globalResetPasswordModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-xs opacity-0 pointer-events-none transition-all duration-200" 
     role="dialog" 
     aria-modal="true"
     aria-labelledby="modalResetPasswordTitle">
    
    <!-- Background Backdrop Closer -->
    <div class="fixed inset-0" onclick="tutupModalResetPassword()"></div>

    <!-- Modal Card Container -->
    <div id="globalResetPasswordCard" 
         class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200/80 overflow-hidden transform scale-95 transition-all duration-200 z-10">
        
        <!-- Header Modal -->
        <div class="px-6 pt-6 pb-4 flex items-start justify-between">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-600 border border-amber-100 flex items-center justify-center shrink-0 shadow-xs">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        <circle cx="12" cy="16" r="1"></circle>
                    </svg>
                </div>
                <div>
                    <h3 id="modalResetPasswordTitle" class="text-base font-bold text-slate-800">Reset Kata Sandi Siswa</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kembalikan sandi akun ke pengaturan default</p>
                </div>
            </div>
            <button type="button" 
                    onclick="tutupModalResetPassword()" 
                    class="p-1.5 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors"
                    title="Tutup">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <!-- Form Eksekusi Reset Password -->
        <form id="globalResetPasswordForm" method="POST" action="" class="px-6 pb-6 space-y-4">
            @csrf

            <!-- Pesan Konfirmasi -->
            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin mereset kata sandi akun untuk siswa <strong id="modalResetStudentName" class="text-slate-900 font-bold">siswa ini</strong>?
            </p>

            <!-- Card Informasi Kata Sandi Default -->
            <div class="p-3.5 rounded-xl bg-amber-50/80 border border-amber-200/70 text-xs text-amber-800 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div class="space-y-1">
                    <p class="font-bold text-amber-950">
                        Kata sandi baru default: 
                        <code class="px-1.5 py-0.5 rounded-md bg-amber-200/80 text-amber-950 font-mono font-bold tracking-wider">12345678</code>
                    </p>
                    <p class="text-amber-700 leading-relaxed">
                        Siswa dapat langsung login ke aplikasi menggunakan sandi default ini dan memperbaruinya melalui menu profil.
                    </p>
                </div>
            </div>

            <!-- Tombol Aksi Batal & Ya Reset -->
            <div class="pt-2 flex items-center justify-end gap-2.5 border-t border-slate-100">
                <button type="button" 
                        onclick="tutupModalResetPassword()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-800 text-xs font-semibold transition-all cursor-pointer">
                    Batal
                </button>
                <button type="submit" 
                        id="modalResetSubmitBtn"
                        class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-[0_4px_14px_rgba(245,158,11,0.28)] transition-all flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Ya, Reset Sandi</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Membuka modal konfirmasi reset kata sandi siswa.
     * @param {string} actionUrl - URL target reset password (POST /siswa/{id}/reset-password)
     * @param {string} studentName - Nama lengkap siswa
     */
    function konfirmasiResetPassword(actionUrl, studentName = 'siswa ini') {
        const modal = document.getElementById('globalResetPasswordModal');
        const card = document.getElementById('globalResetPasswordCard');
        const form = document.getElementById('globalResetPasswordForm');
        const studentNameEl = document.getElementById('modalResetStudentName');

        if (!modal || !card || !form) return;

        form.action = actionUrl;
        if (studentNameEl) studentNameEl.textContent = studentName;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    /**
     * Menutup modal konfirmasi reset password.
     */
    function tutupModalResetPassword() {
        const modal = document.getElementById('globalResetPasswordModal');
        const card = document.getElementById('globalResetPasswordCard');

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
            tutupModalResetPassword();
        }
    });
</script>
