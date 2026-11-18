<x-layout 
    title="Mini Games & Leaderboard - Study English Prima" 
    pageTitle="Mini Games & Leaderboard" 
    pageSubtitle="Kelola konten soal game edukasi, pantau performa permainan, dan papan peringkat siswa." 
    active="game"
>
    <!-- Notifikasi Flash Message Sukses -->
    @if(session('success'))
        <div class="mb-5 p-4 rounded-2xl bg-[#EAF5EF] border border-[#D7EDE1] text-[#2E7352] text-xs font-semibold flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <svg class="w-4 h-4 text-[#4EA87D] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="text-[#2E7352] hover:text-[#1d4c36] font-bold cursor-pointer">&times;</button>
        </div>
    @endif

    <!-- Alert Validasi Error -->
    <x-alert-errors title="Mohon periksa kesalahan input berikut:" />

    <!-- 1. Bar Aksi Pencarian & Kartu Statistik Ringkasan -->
    @include('guru.mini_game_leaderboard.stat-cards')

    <!-- Tata Letak Dua Kolom: Mode Game & Leaderboard -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- 2. Kolom Kiri (2 Kolom): Mode Permainan Edukasi & Bank Soal -->
        <div class="lg:col-span-2 space-y-6">
            @include('guru.mini_game_leaderboard.game-modes')
        </div>

        <!-- 3. Kolom Kanan (1 Kolom): Papan Peringkat Game (Leaderboard) -->
        <div class="space-y-4">
            @include('guru.mini_game_leaderboard.leaderboard')
        </div>

    </div>

    <!-- 4. Modal Tambah Soal & Script Handler -->
    @include('guru.mini_game_leaderboard.modals')
</x-layout>
