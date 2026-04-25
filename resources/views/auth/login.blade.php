<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - CareHub Admin</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes zoom-in { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .animate-zoom { animation: zoom-in 0.5s ease-out forwards; }
    </style>
</head>
<body class="min-h-screen bg-[#F8FAFC] flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl shadow-blue-100 overflow-hidden border-0 border-white relative animate-zoom">
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-bl-[4rem] opacity-60"></div>
        
        <div class="p-10 space-y-8 relative z-10">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 bg-blue-600 rounded-3xl mx-auto flex items-center justify-center shadow-xl shadow-blue-200 mb-4 p-2.5">
                    <img src="/icon.svg" alt="CareHub Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-4xl font-black tracking-tighter"><span class="text-black">Care</span><span class="text-blue-600">Hub</span></h1>
                <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Cahaya Asuhan Ruang Empati</p>
            </div>

            <meta name="csrf-token" content="{{ csrf_token() }}">

            <form id="loginForm" class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Email / Username</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="user" size="18"></i>
                        </div>
                        <input type="email" id="email" placeholder="admin@CareHub.com" required
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-0 rounded-2xl outline-none font-bold text-sm focus:ring-4 focus:ring-blue-100 transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="lock" size="18"></i>
                        </div>
                        <input type="password" id="password" placeholder="••••••••" required
                            class="w-full pl-12 pr-12 py-4 bg-gray-50 border-0 rounded-2xl outline-none font-bold text-sm focus:ring-4 focus:ring-blue-100 transition-all">
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400/50 hover:text-blue-600 transition-all cursor-pointer focus:outline-none">
                            <i data-lucide="eye-off" size="18" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div id="errorMessage" class="hidden flex items-center gap-2 text-rose-500 bg-rose-50 p-3 rounded-xl border-0 border-rose-100">
                    <i data-lucide="alert-circle" size="14"></i>
                    <p id="errorText" class="text-[10px] font-black uppercase tracking-widest"></p>
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3 mt-4">
                    <span>Masuk Ke Dashboard</span>
                    <i data-lucide="log-in" size="18"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            togglePassword.innerHTML = `<i data-lucide="${isPassword ? 'eye' : 'eye-off'}" size="18"></i>`;
            lucide.createIcons();
        });

        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btnSubmit = document.getElementById('btnSubmit');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            // Ambil token CSRF dari meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            errorMessage.classList.add('hidden');
            btnSubmit.innerHTML = 'Memproses...';
            btnSubmit.disabled = true;

            try {
                // Gunakan URL endpoint API yang sudah kamu buat di web.php/api.php
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken // WAJIB ada di Laravel
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok) {
                    // Simpan data untuk Mobile App capability
                    localStorage.setItem('auth_token', data.token);
                    localStorage.setItem('user_data', JSON.stringify(data.user));
                    
                    // Beri jeda sedikit agar penyimpanan localStorage selesai
                    setTimeout(() => {
                        window.location.href = '/admin/dashboard';
                    }, 500);
                } else {
                    errorMessage.classList.remove('hidden');
                    errorText.innerText = data.message || 'Email atau Password salah!';
                    btnSubmit.innerHTML = '<span>Masuk Ke Dashboard</span><i data-lucide="log-in" size="18"></i>';
                    btnSubmit.disabled = false;
                    lucide.createIcons();
                }
            } catch (err) {
                errorMessage.classList.remove('hidden');
                errorText.innerText = 'Server tidak merespon.';
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<span>Masuk Ke Dashboard</span><i data-lucide="log-in" size="18"></i>';
                lucide.createIcons();
            }
        });
    </script>
</body>