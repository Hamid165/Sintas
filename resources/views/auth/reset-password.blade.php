<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - CareHub</title>
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
        <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-50 rounded-bl-[4rem] opacity-60"></div>
        
        <div class="p-10 space-y-8 relative z-10">
            <div class="text-center space-y-2">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl mx-auto flex items-center justify-center mb-4">
                    <i data-lucide="shield-check" size="32"></i>
                </div>
                <h1 class="text-2xl font-black text-slate-800">Verifikasi OTP</h1>
                <p class="text-gray-400 font-bold text-xs">Masukkan kode OTP yang dikirim ke WhatsApp dan password baru Anda.</p>
                <div class="mt-2 bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase inline-block">{{ session('reset_email') }}</div>
            </div>

            <meta name="csrf-token" content="{{ csrf_token() }}">

            <form id="resetPasswordForm" class="space-y-5">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Kode OTP (6 Digit)</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="message-square" size="18"></i>
                        </div>
                        <input type="text" id="otp" placeholder="123456" required maxlength="6"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-0 rounded-2xl outline-none font-black text-center text-xl tracking-[0.5em] focus:ring-4 focus:ring-blue-100 transition-all">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-500 transition-colors">
                            <i data-lucide="lock" size="18"></i>
                        </div>
                        <input type="password" id="password" placeholder="Minimal 6 karakter" required minlength="6"
                            class="w-full pl-12 pr-12 py-4 bg-gray-50 border-0 rounded-2xl outline-none font-bold text-sm focus:ring-4 focus:ring-blue-100 transition-all">
                        <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400/50 hover:text-blue-600 transition-all cursor-pointer focus:outline-none">
                            <i data-lucide="eye-off" size="18"></i>
                        </button>
                    </div>
                </div>

                <div id="statusMessage" class="hidden flex items-center gap-2 p-3 rounded-xl border-0">
                    <i id="statusIcon" data-lucide="info" size="14"></i>
                    <p id="statusText" class="text-[10px] font-black uppercase tracking-widest"></p>
                </div>

                <button type="submit" id="btnSubmit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3 mt-4">
                    <span>Simpan Password Baru</span>
                    <i data-lucide="check" size="18"></i>
                </button>
                
                <div class="text-center mt-6">
                    <a href="{{ route('lupa-password') }}" class="text-[10px] font-black text-gray-400 hover:text-blue-600 uppercase tracking-widest transition-colors">
                        Batal & Kembali
                    </a>
                </div>
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

        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const otp = document.getElementById('otp').value;
            const password = document.getElementById('password').value;
            const btnSubmit = document.getElementById('btnSubmit');
            const statusMessage = document.getElementById('statusMessage');
            const statusText = document.getElementById('statusText');
            const statusIcon = document.getElementById('statusIcon');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            statusMessage.className = 'hidden flex items-center gap-2 p-3 rounded-xl border-0';
            btnSubmit.innerHTML = 'Memproses...';
            btnSubmit.disabled = true;

            try {
                const response = await fetch('/api/reset-password', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ otp, password })
                });

                const data = await response.json();

                if (response.ok) {
                    statusMessage.className = 'flex items-center gap-2 text-emerald-600 bg-emerald-50 p-3 rounded-xl';
                    statusIcon.setAttribute('data-lucide', 'check-circle');
                    statusText.innerText = data.message;
                    lucide.createIcons();
                    
                    // Redirect back to login page
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                } else {
                    statusMessage.className = 'flex items-center gap-2 text-rose-500 bg-rose-50 p-3 rounded-xl';
                    statusIcon.setAttribute('data-lucide', 'alert-circle');
                    statusText.innerText = data.message || 'Gagal reset password!';
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = '<span>Simpan Password Baru</span><i data-lucide="check" size="18"></i>';
                    lucide.createIcons();
                }
            } catch (err) {
                statusMessage.className = 'flex items-center gap-2 text-rose-500 bg-rose-50 p-3 rounded-xl';
                statusIcon.setAttribute('data-lucide', 'alert-circle');
                statusText.innerText = 'Server tidak merespon.';
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<span>Simpan Password Baru</span><i data-lucide="check" size="18"></i>';
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
