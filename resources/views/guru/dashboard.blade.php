<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Belajar Bahasa Inggris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-['Poppins',sans-serif] min-h-screen text-slate-800">
    <div class="max-w-6xl mx-auto p-6 md:p-10">
        <header class="flex justify-between items-center bg-white rounded-3xl p-6 shadow-sm border border-slate-100 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-xl">
                    👨‍🏫
                </div>
                <div>
                    <h1 class="text-xl font-bold text-slate-800">Selamat Datang, {{ auth()->user()->name ?? 'Guru' }}!</h1>
                    <p class="text-xs text-slate-500">Portal Pengajar & Admin Belajar Bahasa Inggris</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 font-semibold text-sm transition-all flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    Keluar
                </button>
            </form>
        </header>

        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm text-center py-16">
            <img src="{{ asset('images/rakun_auth.png') }}" alt="Mascot" class="w-28 mx-auto mb-4 drop-shadow-md">
            <h2 class="text-2xl font-bold text-slate-800 mb-2">Halaman Dashboard Utama Guru</h2>
            <p class="text-slate-500 max-w-md mx-auto text-sm mb-6">
                Login berhasil! Bagian halaman dashboard guru & admin akan kita kembangkan selanjutnya sesuai modul data yang dibutuhkan.
            </p>
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 font-semibold text-xs border border-emerald-100">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Sesi Web Aktif: {{ auth()->user()->email ?? '-' }}
            </span>
        </div>
    </div>
</body>
</html>
