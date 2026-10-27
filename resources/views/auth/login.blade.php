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

    <!-- Styling Animasi Validasi Error Pink Berkedip-kedip -->
    <style>
        @keyframes pinkInputBlink {
            0%, 100% {
                border-color: #FB7185;
                box-shadow: 0 0 0 3.5px rgba(251, 113, 133, 0.4), 0 0 18px rgba(244, 63, 94, 0.35);
                background-color: #FFF1F2;
            }
            50% {
                border-color: #FDA4AF;
                box-shadow: 0 0 0 1px rgba(251, 113, 133, 0.15), 0 0 5px rgba(251, 113, 133, 0.15);
                background-color: #FFFFFF;
            }
        }

        @keyframes pinkAlertPulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
                box-shadow: 0 0 18px rgba(244, 63, 94, 0.4);
                border-color: #FB7185;
            }
            50% {
                opacity: 0.88;
                transform: scale(0.995);
                box-shadow: 0 0 6px rgba(244, 63, 94, 0.15);
                border-color: #FDA4AF;
            }
        }

        @keyframes pinkShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }

        .pink-blink-active {
            animation: pinkInputBlink 1.1s cubic-bezier(0.4, 0, 0.6, 1) infinite !important;
            border-color: #FB7185 !important;
        }

        .pink-alert-active {
            animation: pinkAlertPulse 1.1s ease-in-out infinite !important;
        }

        .shake-active {
            animation: pinkShake 0.35s ease-in-out;
        }
    </style>
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto font-['Poppins',sans-serif] select-none"
      style="background: linear-gradient(180deg, #E6F5EE 0%, #F0F8F3 35%, #FBF5F7 70%, #FDF1F4 100%);">

    <!-- 1. Background Bingkai Belajar Bahasa Inggris Pastel Layer (Full 100% Tanpa Terpotong) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute inset-0 w-full h-full bg-[length:100%_100%] bg-center bg-no-repeat transition-all duration-700"
             style="background-image: url('{{ asset('images/' . rawurlencode('Bingkai Belajar Bahasa Inggris Pastel.png')) }}');">
        </div>
        <!-- Ambient pastel tint overlay lembut -->
        <div class="absolute inset-0 bg-gradient-to-b from-[#E6F5EE]/15 via-transparent to-[#FDF1F4]/15 pointer-events-none"></div>
    </div>

    <!-- 2. Main Login Card Container -->
    <div id="loginCard" class="relative z-10 w-full max-w-[390px] md:max-w-[430px] my-auto">

        <!-- Maskot Rakun Tidur: Bersandar Tepat di Atas Garis Kartu -->
        <div class="absolute -top-[92px] md:-top-[102px] left-1/2 -translate-x-1/2 z-20 pointer-events-none">
            <img src="{{ asset('images/rakun_auth.png') }}" 
                 alt="Mascot Raccoon Sleeping" 
                 class="w-[170px] md:w-[190px] h-auto object-contain filter drop-shadow-[0_6px_12px_rgba(0,0,0,0.08)]">
        </div>

        <!-- Kartu Form Putih Bersih -->
        <div class="bg-white/95 backdrop-blur-xs rounded-[32px] px-8 pt-9 pb-9 md:px-9 md:pt-10 md:pb-10 shadow-[0_20px_50px_rgba(78,168,125,0.18),0_10px_25px_-5px_rgba(0,0,0,0.04)] border border-white relative">
            
            <!-- Header Badge "Login Guru" dengan aksen Hijau Susu & Pink -->
            <div class="flex justify-center mb-6">
                <div class="inline-flex items-center gap-2.5 px-6 py-2.5 bg-[#EAF5EF] rounded-2xl border border-[#D7EDE1] shadow-xs">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#FB7185] animate-pulse"></span>
                    <span class="text-sm font-bold text-[#2E7352] tracking-tight">Login Guru & Admin</span>
                </div>
            </div>

            <!-- Alert Notifikasi Error (Server-side & Client-side dengan Pink Pulse) -->
            <div id="errorAlert" class="{{ ($errors ?? null)?->any() ? 'pink-alert-active flex' : 'hidden' }} mb-5 p-3.5 rounded-2xl bg-[#FFF1F2] border-2 border-[#FB7185] text-[#E11D48] text-xs font-medium items-center gap-2.5 shadow-sm transition-all duration-200">
                <div class="w-7 h-7 rounded-xl bg-[#FFE4E6] flex items-center justify-center shrink-0 text-[#E11D48]">
                    <svg class="w-4 h-4 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <span id="errorMessage" class="leading-relaxed">{{ ($errors ?? null)?->first() ?? '' }}</span>
                </div>
            </div>

            <!-- Form Login -->
            <form id="loginForm" action="{{ route('login.post') }}" method="POST" class="space-y-4" novalidate>
                @csrf

                <!-- Input Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5 pl-1">Email</label>
                    <div id="emailWrapper" class="relative flex items-center bg-[#F4F8F5] rounded-2xl px-4 py-3.5 border {{ ($errors ?? null)?->has('email') ? 'pink-blink-active border-[#FB7185]' : 'border-transparent' }} focus-within:border-[#4EA87D]/50 focus-within:ring-3 focus-within:ring-[#4EA87D]/15 focus-within:bg-white transition-all duration-200">
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
                               autocomplete="email"
                               class="w-full bg-transparent border-0 outline-none text-slate-800 placeholder-slate-400 text-sm ml-3 font-normal">
                    </div>
                    <!-- Pesan Validasi Error Email -->
                    <p id="emailError" class="{{ ($errors ?? null)?->has('email') ? 'flex' : 'hidden' }} text-rose-500 font-medium text-xs mt-1.5 pl-1 items-center gap-1.5 animate-pulse">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                            <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"></line>
                            <circle cx="12" cy="16" r="1" fill="currentColor"></circle>
                        </svg>
                        <span id="emailErrorText">{{ ($errors ?? null)?->first('email') ?? '' }}</span>
                    </p>
                </div>

                <!-- Input Kata Sandi -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5 pl-1">Kata Sandi</label>
                    <div id="passwordWrapper" class="relative flex items-center bg-[#F4F8F5] rounded-2xl px-4 py-3.5 border {{ ($errors ?? null)?->has('password') ? 'pink-blink-active border-[#FB7185]' : 'border-transparent' }} focus-within:border-[#4EA87D]/50 focus-within:ring-3 focus-within:ring-[#4EA87D]/15 focus-within:bg-white transition-all duration-200">
                        <!-- Lock Icon -->
                        <svg class="w-5 h-5 text-slate-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                            <circle cx="12" cy="16" r="1.5"></circle>
                            <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                        </svg>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Minimal 6 karakter" 
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
                    <!-- Pesan Validasi Error Password -->
                    <p id="passwordError" class="{{ ($errors ?? null)?->has('password') ? 'flex' : 'hidden' }} text-rose-500 font-medium text-xs mt-1.5 pl-1 items-center gap-1.5 animate-pulse">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"></circle>
                            <line x1="12" y1="8" x2="12" y2="12" stroke-width="2" stroke-linecap="round"></line>
                            <circle cx="12" cy="16" r="1" fill="currentColor"></circle>
                        </svg>
                        <span id="passwordErrorText">{{ ($errors ?? null)?->first('password') ?? '' }}</span>
                    </p>
                </div>

                <!-- Tombol Submit "Masuk" -->
                <div class="pt-3">
                    <button type="submit" 
                            class="w-full py-3.5 md:py-4 rounded-2xl bg-gradient-to-r from-[#4EA87D] via-[#439A70] to-[#388A60] hover:from-[#43966E] hover:to-[#2F7A52] text-white font-semibold text-sm md:text-base shadow-[0_8px_20px_rgba(78,168,125,0.32)] hover:shadow-[0_12px_28px_rgba(78,168,125,0.42)] active:scale-[0.985] transition-all duration-200 cursor-pointer flex items-center justify-center tracking-wide">
                        Masuk
                    </button>
                </div>

            </form>

        </div>
    </div>

    <!-- Script Interaktif: Password Toggle & Validasi Pink Berkedip -->
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

        document.addEventListener('DOMContentLoaded', function () {
            const loginForm = document.getElementById('loginForm');
            const loginCard = document.getElementById('loginCard');
            const emailInput = document.getElementById('email');
            const emailWrapper = document.getElementById('emailWrapper');
            const emailError = document.getElementById('emailError');
            const emailErrorText = document.getElementById('emailErrorText');

            const passwordInput = document.getElementById('password');
            const passwordWrapper = document.getElementById('passwordWrapper');
            const passwordError = document.getElementById('passwordError');
            const passwordErrorText = document.getElementById('passwordErrorText');

            const errorAlert = document.getElementById('errorAlert');
            const errorMessage = document.getElementById('errorMessage');

            function clearEmailError() {
                emailWrapper.classList.remove('pink-blink-active', 'border-[#FB7185]');
                emailWrapper.classList.add('border-transparent');
                emailError.classList.add('hidden');
                emailError.classList.remove('flex');
                checkGlobalAlert();
            }

            function clearPasswordError() {
                passwordWrapper.classList.remove('pink-blink-active', 'border-[#FB7185]');
                passwordWrapper.classList.add('border-transparent');
                passwordError.classList.add('hidden');
                passwordError.classList.remove('flex');
                checkGlobalAlert();
            }

            function checkGlobalAlert() {
                const hasEmailError = emailWrapper.classList.contains('pink-blink-active');
                const hasPasswordError = passwordWrapper.classList.contains('pink-blink-active');
                if (!hasEmailError && !hasPasswordError && errorAlert) {
                    errorAlert.classList.add('hidden');
                    errorAlert.classList.remove('flex', 'pink-alert-active');
                }
            }

            if (emailInput) {
                emailInput.addEventListener('input', clearEmailError);
            }

            if (passwordInput) {
                passwordInput.addEventListener('input', clearPasswordError);
            }

            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    let isValid = true;
                    let firstErrorInput = null;
                    let alertMsg = '';

                    const emailVal = emailInput.value.trim();
                    const passwordVal = passwordInput.value;

                    // Regex format email standar
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    // 1. Validasi Input Email
                    if (!emailVal) {
                        isValid = false;
                        emailWrapper.classList.add('pink-blink-active', 'border-[#FB7185]');
                        emailWrapper.classList.remove('border-transparent');
                        emailErrorText.textContent = 'Alamat email belum diisi! Silakan masukkan email akun Anda.';
                        emailError.classList.remove('hidden');
                        emailError.classList.add('flex');
                        alertMsg = 'Mohon lengkapi alamat email akun Anda.';
                        firstErrorInput = emailInput;
                    } else if (!emailRegex.test(emailVal)) {
                        isValid = false;
                        emailWrapper.classList.add('pink-blink-active', 'border-[#FB7185]');
                        emailWrapper.classList.remove('border-transparent');
                        emailErrorText.textContent = 'Format penulisan email tidak valid (contoh: guru@gmail.com).';
                        emailError.classList.remove('hidden');
                        emailError.classList.add('flex');
                        alertMsg = 'Format email belum tepat, mohon periksa kembali.';
                        firstErrorInput = emailInput;
                    } else {
                        clearEmailError();
                    }

                    // 2. Validasi Input Kata Sandi
                    if (!passwordVal) {
                        isValid = false;
                        passwordWrapper.classList.add('pink-blink-active', 'border-[#FB7185]');
                        passwordWrapper.classList.remove('border-transparent');
                        passwordErrorText.textContent = 'Kata sandi belum diisi! Silakan masukkan kata sandi Anda.';
                        passwordError.classList.remove('hidden');
                        passwordError.classList.add('flex');
                        if (!alertMsg) {
                            alertMsg = 'Kata sandi wajib diisi untuk verifikasi keamanan.';
                        }
                        if (!firstErrorInput) firstErrorInput = passwordInput;
                    } else if (passwordVal.length < 6) {
                        isValid = false;
                        passwordWrapper.classList.add('pink-blink-active', 'border-[#FB7185]');
                        passwordWrapper.classList.remove('border-transparent');
                        passwordErrorText.textContent = 'Kata sandi terlalu pendek! Minimal harus 6 karakter.';
                        passwordError.classList.remove('hidden');
                        passwordError.classList.add('flex');
                        if (!alertMsg) {
                            alertMsg = 'Kata sandi harus minimal 6 karakter.';
                        }
                        if (!firstErrorInput) firstErrorInput = passwordInput;
                    } else {
                        clearPasswordError();
                    }

                    // 3. Tangani jika ada kesalahan validasi
                    if (!isValid) {
                        e.preventDefault();

                        // Tampilkan pesan pada kotak alert berkedip pink
                        if (errorAlert && errorMessage) {
                            errorMessage.textContent = alertMsg || 'Silakan lengkapi formulir dengan data yang benar.';
                            errorAlert.classList.remove('hidden');
                            errorAlert.classList.add('flex', 'pink-alert-active');
                        }

                        // Efek getar pada kartu login
                        if (loginCard) {
                            loginCard.classList.remove('shake-active');
                            void loginCard.offsetWidth; // Trigger reflow browser
                            loginCard.classList.add('shake-active');
                        }

                        // Arahkan fokus ke input yang error
                        if (firstErrorInput) {
                            firstErrorInput.focus();
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>
