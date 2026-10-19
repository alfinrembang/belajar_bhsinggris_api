<!-- ========================================== -->
<!-- 1. MODAL TAMBAH AKUN GURU BARU            -->
<!-- ========================================== -->
<div id="tambahGuruModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300" 
     role="dialog" 
     aria-modal="true">
    <div id="tambahGuruCard" 
         class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tambah Akun Guru Baru</h3>
                    <p class="text-[11px] text-slate-400">Daftarkan akun pendidik mapel Bahasa Inggris</p>
                </div>
            </div>
            <button type="button" onclick="closeTambahGuruModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                ✕
            </button>
        </div>

        <!-- Modal Form Body -->
        <form action="{{ route('admin.guru.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Input Nama Lengkap -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap Guru <span class="text-rose-500">*</span></label>
                <input type="text" 
                       name="name" 
                       required 
                       placeholder="Contoh: Budi Santoso, S.Pd" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Login <span class="text-rose-500">*</span></label>
                <input type="email" 
                       name="email" 
                       required 
                       placeholder="Contoh: budi.santoso@sekolah.sch.id" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input NIP (Opsional) -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" 
                       name="nip" 
                       placeholder="Contoh: 198501152010011002" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input Kelas & Rombel yang Diampu -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Kelas & Rombel yang Diampu <span class="text-slate-400 font-normal">(Pilih satu atau lebih)</span>
                </label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl max-h-36 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @if(isset($daftarRombel))
                        @foreach($daftarRombel as $rombel)
                            <label class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-white text-xs text-slate-700 cursor-pointer transition-colors">
                                <input type="checkbox" name="kelas_diampu[]" value="{{ $rombel }}" class="rounded text-[#1E6BFF] focus:ring-[#1E6BFF]">
                                <span class="font-medium text-[11px]">{{ $rombel }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Siswa di kelas ini hanya akan menerima materi yang dibuat oleh guru ini.</p>
            </div>

            <!-- Input Kata Sandi -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Awal <span class="text-rose-500">*</span></label>
                <input type="password" 
                       name="password" 
                       required 
                       minlength="6" 
                       placeholder="Minimal 6 karakter" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Beritahukan kata sandi ini kepada guru bersangkutan untuk login pertama kali.</p>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeTambahGuruModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white text-xs font-semibold shadow-sm transition-all">
                    Simpan Akun Guru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 2. MODAL EDIT AKUN GURU                   -->
<!-- ========================================== -->
<div id="editGuruModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300" 
     role="dialog" 
     aria-modal="true">
    <div id="editGuruCard" 
         class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Edit Data Akun Guru</h3>
                    <p class="text-[11px] text-slate-400">Perbarui informasi profil dan NIP pengajar</p>
                </div>
            </div>
            <button type="button" onclick="closeEditGuruModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                ✕
            </button>
        </div>

        <!-- Modal Form Body -->
        <form id="formEditGuru" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Input Nama Lengkap -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap Guru <span class="text-rose-500">*</span></label>
                <input type="text" 
                       id="editGuruName" 
                       name="name" 
                       required 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input Email -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email Login <span class="text-rose-500">*</span></label>
                <input type="email" 
                       id="editGuruEmail" 
                       name="email" 
                       required 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input NIP -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <input type="text" 
                       id="editGuruNip" 
                       name="nip" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
            </div>

            <!-- Input Kelas & Rombel yang Diampu -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Kelas & Rombel yang Diampu <span class="text-slate-400 font-normal">(Pilih satu atau lebih)</span>
                </label>
                <div class="p-3 bg-slate-50 border border-slate-200 rounded-2xl max-h-36 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @if(isset($daftarRombel))
                        @foreach($daftarRombel as $rombel)
                            <label class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-white text-xs text-slate-700 cursor-pointer transition-colors">
                                <input type="checkbox" name="kelas_diampu[]" value="{{ $rombel }}" class="edit-kelas-checkbox rounded text-[#1E6BFF] focus:ring-[#1E6BFF]">
                                <span class="font-medium text-[11px]">{{ $rombel }}</span>
                            </label>
                        @endforeach
                    @endif
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Perubahan kelas akan langsung menyinkronkan materi ke aplikasi mobile siswa.</p>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeEditGuruModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white text-xs font-semibold shadow-sm transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 3. MODAL RESET PASSWORD GURU              -->
<!-- ========================================== -->
<div id="resetPasswordModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300" 
     role="dialog" 
     aria-modal="true">
    <div id="resetPasswordCard" 
         class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-amber-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Reset Kata Sandi Guru</h3>
                    <p class="text-[11px] text-slate-400">Atur kata sandi baru untuk akun pengajar</p>
                </div>
            </div>
            <button type="button" onclick="closeResetPasswordModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                ✕
            </button>
        </div>

        <!-- Modal Form Body -->
        <form id="formResetPassword" method="POST" class="p-6 space-y-4">
            @csrf

            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs text-slate-600">
                Mereset kata sandi untuk akun: <br>
                <strong id="resetGuruNameText" class="text-slate-800 font-bold text-sm">Nama Guru</strong>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                <input type="password" 
                       name="new_password" 
                       required 
                       minlength="6" 
                       placeholder="Minimal 6 karakter" 
                       class="w-full px-4 py-2.5 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-amber-500 focus:bg-white transition-all">
                <p class="text-[11px] text-slate-400 mt-1">Setelah disimpan, guru akan masuk menggunakan kata sandi baru ini.</p>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeResetPasswordModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold shadow-sm transition-all">
                    Simpan Sandi Baru
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 4. MODAL KONFIRMASI HAPUS GURU            -->
<!-- ========================================== -->
<div id="hapusGuruModal" 
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300" 
     role="dialog" 
     aria-modal="true">
    <div id="hapusGuruCard" 
         class="relative w-full max-w-md bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden transform scale-95 transition-all duration-300">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-rose-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Hapus Akun Guru</h3>
                    <p class="text-[11px] text-slate-400">Konfirmasi pencabutan hak akses guru</p>
                </div>
            </div>
            <button type="button" onclick="closeHapusGuruModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-xl hover:bg-slate-100 transition-colors">
                ✕
            </button>
        </div>

        <!-- Modal Form Body -->
        <form id="formHapusGuru" method="POST" class="p-6 space-y-4">
            @csrf

            <p class="text-xs text-slate-600 leading-relaxed">
                Apakah Anda yakin ingin menghapus akun guru <strong id="hapusGuruNameText" class="text-slate-800 font-bold">Nama Guru</strong>?
            </p>

            <div class="p-3.5 rounded-2xl bg-rose-50/80 border border-rose-200/60 text-xs text-rose-700 flex items-start gap-2.5">
                <span class="text-base leading-none">⚠️</span>
                <p class="leading-relaxed">
                    Akun yang dihapus tidak akan dapat login lagi ke portal guru. Pastikan guru ini sudah tidak aktif mengajar.
                </p>
            </div>

            <!-- Modal Footer Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeHapusGuruModal()" 
                        class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition-all">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold shadow-sm transition-all">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </div>
</div>

<!-- SCRIPT LOGIKA INTERAKTIF MODAL ADMIN -->
<script>
    // 1. Logika Modal Tambah Guru
    function openTambahGuruModal() {
        const modal = document.getElementById('tambahGuruModal');
        const card = document.getElementById('tambahGuruCard');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    function closeTambahGuruModal() {
        const modal = document.getElementById('tambahGuruModal');
        const card = document.getElementById('tambahGuruCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.classList.remove('overflow-hidden');
    }

    // 2. Logika Modal Edit Guru
    function openEditGuruModal(guru) {
        const modal = document.getElementById('editGuruModal');
        const card = document.getElementById('editGuruCard');
        const form = document.getElementById('formEditGuru');

        form.action = `/admin/guru/${guru.id}/update`;
        document.getElementById('editGuruName').value = guru.name || '';
        document.getElementById('editGuruEmail').value = guru.email || '';
        document.getElementById('editGuruNip').value = guru.nip || '';

        const assigned = Array.isArray(guru.kelas_diampu) ? guru.kelas_diampu : [];
        document.querySelectorAll('.edit-kelas-checkbox').forEach(cb => {
            cb.checked = assigned.includes(cb.value);
        });

        modal.classList.remove('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    function closeEditGuruModal() {
        const modal = document.getElementById('editGuruModal');
        const card = document.getElementById('editGuruCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.classList.remove('overflow-hidden');
    }

    // 3. Logika Modal Reset Password
    function openResetPasswordModal(id, name) {
        const modal = document.getElementById('resetPasswordModal');
        const card = document.getElementById('resetPasswordCard');
        const form = document.getElementById('formResetPassword');

        form.action = `/admin/guru/${id}/reset-password`;
        document.getElementById('resetGuruNameText').innerText = name;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('resetPasswordModal');
        const card = document.getElementById('resetPasswordCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.classList.remove('overflow-hidden');
    }

    // 4. Logika Modal Hapus Guru
    function openHapusGuruModal(id, name) {
        const modal = document.getElementById('hapusGuruModal');
        const card = document.getElementById('hapusGuruCard');
        const form = document.getElementById('formHapusGuru');

        form.action = `/admin/guru/${id}/destroy`;
        document.getElementById('hapusGuruNameText').innerText = name;

        modal.classList.remove('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-95');
        card.classList.add('scale-100');
        document.body.classList.add('overflow-hidden');
    }

    function closeHapusGuruModal() {
        const modal = document.getElementById('hapusGuruModal');
        const card = document.getElementById('hapusGuruCard');
        modal.classList.add('opacity-0', 'pointer-events-none');
        card.classList.remove('scale-100');
        card.classList.add('scale-95');
        document.body.classList.remove('overflow-hidden');
    }

    // Menutup modal dengan tombol ESC atau klik di luar card
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeTambahGuruModal();
            closeEditGuruModal();
            closeResetPasswordModal();
            closeHapusGuruModal();
        }
    });

    ['tambahGuruModal', 'editGuruModal', 'resetPasswordModal', 'hapusGuruModal'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeTambahGuruModal();
                    closeEditGuruModal();
                    closeResetPasswordModal();
                    closeHapusGuruModal();
                }
            });
        }
    });
</script>
