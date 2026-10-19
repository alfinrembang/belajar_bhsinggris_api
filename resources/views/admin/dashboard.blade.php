<x-layout 
    title="Dashboard Admin - Study English Prima" 
    pageTitle="Dashboard Administrator" 
    pageSubtitle="Pusat manajemen akun tenaga pendidik (guru) dan sistem administrasi sekolah." 
    active="dashboard"
>
    <!-- Notifikasi Flash Message -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-xs animate-fade-in">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">✓</span>
                <p class="text-xs lg:text-sm font-medium">{{ session('success') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-lg leading-none p-1">×</button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3">
                <span class="w-8 h-8 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-sm shrink-0">!</span>
                <p class="text-xs lg:text-sm font-medium">{{ session('error') }}</p>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700 text-lg leading-none p-1">×</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 shadow-xs">
            <div class="flex items-center gap-2 mb-2 font-semibold text-xs lg:text-sm">
                <span>⚠️ Terdapat kesalahan pengisian data:</span>
            </div>
            <ul class="list-disc list-inside text-xs space-y-1 text-rose-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- 1. Banner Sambutan Administrator -->
    @include('admin.partials.banner')

    <!-- 2. Kartu Statistik Ringkasan -->
    @include('admin.partials.stat-cards')

    <!-- 3. Tabel Data Akun Guru & Filter Pencarian -->
    @include('admin.partials.guru-table')

    <!-- 4. Modals Interaktif & Script Logika Admin -->
    @include('admin.partials.guru-modals')
</x-layout>
