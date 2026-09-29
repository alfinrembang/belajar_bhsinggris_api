<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Guru - Belajar Bahasa Inggris</title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 overflow-hidden font-['Poppins',sans-serif] select-none"
      style="background: linear-gradient(180deg, #2AC4EC 0%, #68CEEE 35%, #BCE7F5 70%, #E2F3F9 100%);">

    <!-- 1. Background Clouds Layer (Satu Layer Penuh Mulus Tanpa Kotak-kotak) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute inset-0 w-full h-full opacity-40 mix-blend-overlay"
             style="background-image: url('{{ asset('images/awan.png') }}'); background-size: cover; background-position: center; -webkit-mask-image: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.85) 30%, rgba(0,0,0,0.85) 70%, transparent 100%); mask-image: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.85) 30%, rgba(0,0,0,0.85) 70%, transparent 100%);">
        </div>
    </div>

    <!-- 2. Main Login Card Container -->
    <div class="relative z-10 w-full max-w-[390px] md:max-w-[430px] my-auto">

        <!-- Maskot Rakun Tidur: Bersandar Tepat di Atas Garis Kartu -->
        <div class="absolute -top-[92px] md:-top-[102px] left-1/2 -translate-x-1/2 z-20 pointer-events-none">
            <img src="{{ asset('images/rakun_auth.png') }}" 
                 alt="Mascot Raccoon Sleeping" 
                 class="w-[170px] md:w-[190px] h-auto object-contain filter drop-shadow-[0_6px_12px_rgba(0,0,0,0.08)]">
        </div>

        <!-- Kartu Form Putih Bersih -->
        <div class="bg-white rounded-[32px] px-8 pt-9 pb-9 md:px-9 md:pt-10 md:pb-10 shadow-[0_20px_50px_rgba(40,194,235,0.22),0_10px_25px_-5px_rgba(0,0,0,0.04)] border border-white/80 relative">
            
            <!-- Header Badge "Login Guru" -->
            <div class="flex justify-center mb-6">
                <div class="inline-flex items-center gap-2.5 px-6 py-2.5 bg-[#EFF4FF] rounded-2xl border border-[#DCE7FD] shadow-xs">
                    <!-- ID Card Icon -->
                    <svg class="w-4 h-4 text-[#0066D6]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="16" rx="3"></rect>
                        <circle cx="9" cy="10" r="2"></circle>
                        <path d="M15 8h2"></path>
                        <path d="M15 12h2"></path>
                        <path d="M7 16h10"></path>
                    </svg>
                    <span class="text-sm font-bold text-[#0066D6] tracking-tight">Login Guru</span>
                </div>
            </div>

            <!-- Alert Notifikasi Error (Jika Ada) -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 text-xs font-medium flex items-center gap-2.5">
                    <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 pl-1">Email</label>
                    <div class="relative flex items-center bg-[#F1F3FB] rounded-2xl px-4 py-3.5 border border-transparent focus-within:border-[#0066D6]/40 focus-within:ring-3 focus-within:ring-[#0066D6]/15 focus-within:bg-white transition-all duration-200">
                        <!-- Icon Card / ID -->
                        <svg class="w-5 h-5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                            <circle cx="8" cy="11" r="2"></circle>
                            <path d="M13 10h4"></path>
                            <path d="M13 13h3"></path>
                        </svg>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="admin@gmail.com" 
                               required 
                               autocomplete="email"
                               class="w-full bg-transparent border-0 outline-none text-slate-800 placeholder-slate-400 text-sm ml-3 font-normal">
                    </div>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 pl-1">Kata Sandi</label>
                    <div class="relative flex items-center bg-[#F1F3FB] rounded-2xl px-4 py-3.5 border border-transparent focus-within:border-[#0066D6]/40 focus-within:ring-3 focus-within:ring-[#0066D6]/15 focus-within:bg-white transition-all duration-200">
                        <!-- Lock Icon -->
                        <svg class="w-5 h-5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                            <circle cx="12" cy="16" r="1.5"></circle>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                        </svg>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="123456" 
                               required 
                               autocomplete="current-password"
                               class="w-full bg-transparent border-0 outline-none text-slate-800 placeholder-slate-400 text-sm ml-3 font-normal">
                        <!-- Toggle Password Visibility Button -->
                        <button type="button" 
                                onclick="togglePasswordVisibility()" 
                                class="text-slate-400 hover:text-slate-600 focus:outline-none ml-2 transition-colors cursor-pointer p-0.5" 
                                title="Lihat kata sandi"
                                aria-label="Toggle password visibility">
                            <svg id="eyeIcon" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg id="eyeOffIcon" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                                <line x1="2" y1="2" x2="22" y2="22"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tombol Submit "Masuk" -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-3.5 md:py-4 rounded-2xl bg-gradient-to-r from-[#006CE5] to-[#0051C6] hover:from-[#0060CE] hover:to-[#0047B0] text-white font-semibold text-sm md:text-base shadow-[0_8px_20px_rgba(0,108,229,0.35)] hover:shadow-[0_12px_28px_rgba(0,108,229,0.45)] active:scale-[0.985] transition-all duration-200 cursor-pointer flex items-center justify-center tracking-wide">
                        Masuk
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Script Password Toggle Visibility -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>
