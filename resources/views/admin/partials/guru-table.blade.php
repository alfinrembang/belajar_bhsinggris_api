<!-- Panel Tabel: Manajemen Akun Guru -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
    
    <!-- Header Tabel & Filter Bar -->
    <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h3 class="text-lg font-bold text-slate-800">Daftar Akun Guru Pengajar</h3>
                <span class="px-2.5 py-0.5 rounded-full bg-blue-50 text-[#1E6BFF] text-xs font-bold">
                    {{ $gurus->total() }} Guru
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-0.5">
                Kelola data tenaga pendidik yang berhak mengakses portal pembelajaran dan konten siswa.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <!-- Form Pencarian Guru -->
            <form action="{{ route('admin.dashboard') }}" method="GET" class="relative flex items-center">
                <input type="text" 
                       name="q" 
                       value="{{ $search ?? '' }}" 
                       placeholder="Cari nama, email, NIP..." 
                       class="w-64 pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#1E6BFF] focus:bg-white transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 pointer-events-none" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                @if(!empty($search))
                    <a href="{{ route('admin.dashboard') }}" 
                       class="ml-2 text-xs text-rose-500 hover:text-rose-700 font-medium underline" 
                       title="Hapus filter pencarian">
                        Reset
                    </a>
                @endif
            </form>

            <!-- Tombol Tambah Guru Baru -->
            <button type="button" 
                    onclick="openTambahGuruModal()" 
                    class="px-4 py-2 rounded-xl bg-[#1E6BFF] hover:bg-[#155BE5] text-white text-xs font-semibold shadow-sm transition-all inline-flex items-center gap-1.5 cursor-pointer">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Tambah Guru</span>
            </button>
        </div>
    </div>

    <!-- Tabel Data Akun Guru -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/70 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    <th class="py-3.5 px-6 w-12 text-center">No</th>
                    <th class="py-3.5 px-6">Nama Guru</th>
                    <th class="py-3.5 px-6">Kelas Diampu</th>
                    <th class="py-3.5 px-6">Email Login</th>
                    <th class="py-3.5 px-6">NIP (Nomor Induk)</th>
                    <th class="py-3.5 px-6">Status Peran</th>
                    <th class="py-3.5 px-6">Terdaftar Sejak</th>
                    <th class="py-3.5 px-6 text-center w-36">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                @forelse($gurus as $index => $guru)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <!-- Nomor Urut -->
                        <td class="py-4 px-6 text-center font-semibold text-slate-400">
                            {{ $gurus->firstItem() + $index }}
                        </td>

                        <!-- Nama Guru & Avatar -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-100 text-[#1E6BFF] flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
                                    {{ strtoupper(substr($guru->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-800 truncate leading-tight">{{ $guru->name }}</p>
                                    <span class="inline-flex items-center gap-1 text-[10px] text-[#1E6BFF] font-medium mt-0.5">
                                        <span>●</span> Pengajar Bahasa Inggris
                                    </span>
                                </div>
                            </div>
                        </td>

                        <!-- Kelas Diampu -->
                        <td class="py-4 px-6">
                            @if(!empty($guru->kelas_diampu) && count($guru->kelas_diampu) > 0)
                                <div class="flex flex-wrap gap-1 max-w-xs">
                                    @foreach($guru->kelas_diampu as $k)
                                        <span class="px-2 py-0.5 rounded-lg bg-blue-50 text-[#1E6BFF] text-[10px] font-bold border border-blue-100">
                                            {{ $k }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-slate-400 italic text-[11px]">- Belum diplot -</span>
                            @endif
                        </td>

                        <!-- Email Login -->
                        <td class="py-4 px-6 font-medium text-slate-600">
                            <a href="mailto:{{ $guru->email }}" class="hover:text-[#1E6BFF] transition-colors">
                                {{ $guru->email }}
                            </a>
                        </td>

                        <!-- NIP -->
                        <td class="py-4 px-6 font-mono text-slate-600">
                            @if(!empty($guru->nip))
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-medium">
                                    {{ $guru->nip }}
                                </span>
                            @else
                                <span class="text-slate-400 italic text-[11px]">- Belum diisi -</span>
                            @endif
                        </td>

                        <!-- Status Peran -->
                        <td class="py-4 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-[11px] border border-emerald-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Guru Aktif
                            </span>
                        </td>

                        <!-- Terdaftar Sejak -->
                        <td class="py-4 px-6 text-slate-500 text-[11px]">
                            {{ $guru->created_at ? $guru->created_at->translatedFormat('d M Y') : '-' }}
                        </td>

                        <!-- Kolom Aksi -->
                        <td class="py-4 px-6 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- 1. Tombol Edit Guru -->
                                <button type="button" 
                                        onclick='openEditGuruModal(@json($guru))' 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-[#1E6BFF] hover:bg-blue-50 transition-colors cursor-pointer" 
                                        title="Edit Data Guru">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                </button>

                                <!-- 2. Tombol Reset Password Guru -->
                                <button type="button" 
                                        onclick="openResetPasswordModal({{ $guru->id }}, '{{ addslashes($guru->name) }}')" 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors cursor-pointer" 
                                        title="Reset Kata Sandi Guru">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </button>

                                <!-- 3. Tombol Hapus Guru -->
                                <button type="button" 
                                        onclick="openHapusGuruModal({{ $guru->id }}, '{{ addslashes($guru->name) }}')" 
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer" 
                                        title="Hapus Akun Guru">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"></polyline>
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <x-empty-state 
                        colspan="8" 
                        icon="👨‍🏫" 
                        title="Belum ada akun guru ditemukan" 
                        subtitle="Silakan klik tombol di bawah untuk menambahkan akun guru pengajar baru ke dalam sistem."
                        actionText="Tambah Guru Sekarang"
                        actionUrl="javascript:openTambahGuruModal()"
                    />
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gurus->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $gurus->links() }}
        </div>
    @endif
</div>
