<x-layout 
    title="Dashboard Guru - Study English Prima" 
    pageTitle="Dashboard Utama" 
    pageSubtitle="Ringkasan eksekutif seluruh modul pembelajaran, progres belajar siswa, dan pintasan menu." 
    active="dashboard"
>
    <!-- 1. Header Banner Pendidik -->
    @include('guru.dashboard_utama.banner')

    <!-- 2. Empat Kartu Metrik Ringkasan Utama -->
    @include('guru.dashboard_utama.stat-cards')

    <!-- 3. Tata Letak Dua Kolom Komprehensif -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Kolom Kiri (7 Kolom): Ketuntasan Belajar & Modul Pembelajaran Terkini -->
        <div class="lg:col-span-7 space-y-6">
            @include('guru.dashboard_utama.ringkasan-ketuntasan')
            @include('guru.dashboard_utama.modul-terbaru')
        </div>

        <!-- Kolom Kanan (5 Kolom): Peringkat Siswa Paling Unggul & Hub Pintasan Akses Cepat -->
        <div class="lg:col-span-5 space-y-6">
            @include('guru.dashboard_utama.siswa-unggulan')
            @include('guru.dashboard_utama.pintasan-menu')
        </div>

    </div>
</x-layout>
