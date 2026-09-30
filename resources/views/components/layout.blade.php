@props([
    'title' => 'Dashboard Guru - Study English Prima',
    'pageTitle' => 'Dashboard Utama',
    'pageSubtitle' => 'Ringkasan aktivitas belajar, performa kelas, dan manajemen materi.',
    'active' => 'dashboard'
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F8FAFC] font-['Poppins',sans-serif] min-h-screen text-slate-800 antialiased flex">

    <!-- 1. Komponen Sidebar Terintegrasi -->
    <x-sidebar :active="$active" />

    <!-- 2. Area Konten Utama (Kanan) -->
    <div class="flex-1 flex flex-col min-w-0 min-h-screen overflow-y-auto">
        
        <!-- Topbar Header -->
        <header class="bg-white border-b border-slate-100 px-6 py-4.5 lg:px-8 flex items-center justify-between sticky top-0 z-10 shadow-xs">
            <div>
                <h1 class="text-xl lg:text-2xl font-bold text-slate-800 tracking-tight">{{ $pageTitle }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">{{ $pageSubtitle }}</p>
            </div>

            <div class="flex items-center gap-3.5">
                <!-- Status Badge -->
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-xs border border-emerald-100/80">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Portal Aktif
                </span>

                <!-- Avatar User -->
                <div class="flex items-center gap-3 pl-3 border-l border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-[#1E6BFF] flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="hidden sm:block text-left">
                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ auth()->user()->name ?? 'Guru Pengajar' }}</p>
                        <p class="text-[11px] text-slate-400 capitalize">{{ auth()->user()->role ?? 'guru' }}</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Konten Halaman Dinamis -->
        <main class="p-6 lg:p-8 space-y-6">
            {{ $slot }}
        </main>
    </div>

</body>
</html>
