<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CareHub Admin')</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- SheetJS for Excel Export -->
    <script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        @keyframes slide-in { from { transform: translateY(1rem); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .animate-page { animation: slide-in 0.4s ease-out forwards; }
        @keyframes modal-in { from { transform: scale(0.95) translateY(-10px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
        .animate-modal { animation: modal-in 0.25s ease-out forwards; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 selection:bg-blue-100">

    <div class="flex h-screen overflow-hidden relative">
        
        <!-- Mobile Sidebar Overlay (opsional supaya background tertutup) -->
        <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

        <aside id="sidebar" class="w-64 max-w-[80vw] fixed lg:relative z-50 bg-[#0F172A] h-full transition-transform duration-300 overflow-hidden flex flex-col shadow-2xl shrink-0 -translate-x-full lg:translate-x-0">
            <div class="p-8 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg overflow-hidden p-1.5">
                    <img src="/icon.svg" alt="Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-2xl font-black tracking-tighter"><span class="text-white">Care</span><span class="text-blue-500">Hub</span></span>
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto scrollbar-hide">
                <a href="{{ route('admin.dashboard') }}" 
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="layout-dashboard" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Dashboard</span>
                </a>

                <a href="{{ route('admin.anak') }}" 
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.anak*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="users" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Manajemen Anak</span>
                </a>

                <a href="{{ route('admin.keuangan') }}" 
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.keuangan*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="wallet" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Keuangan</span>
                </a>

                <a href="{{ route('admin.inventori') }}" 
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.inventori*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="package" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Inventaris</span>
                </a>

                <a href="{{ route('admin.artikel') }}" 
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.artikel*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="newspaper" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Artikel & CMS</span>
                </a>
            </nav>

            <div class="p-6 border-t border-white/10">
                <button onclick="handleLogout()" class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl text-rose-400 hover:bg-rose-400/10 transition-all font-black text-[10px] uppercase tracking-widest">
                    <i data-lucide="log-out" size="18"></i> Keluar Sistem
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <header class="h-16 bg-white border-b px-8 flex items-center justify-between sticky top-0 z-40">
                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="p-2 text-slate-400 transition-colors hover:bg-gray-50 rounded-xl">
                        <i data-lucide="menu" size="20"></i>
                    </button>
                    {{-- Judul halaman sesuai nama menu aktif --}}
                    <h2 class="font-black text-slate-800 uppercase tracking-widest text-sm">
                        @if(request()->routeIs('admin.dashboard'))
                            Dashboard
                        @elseif(request()->routeIs('admin.anak*'))
                            Manajemen Anak
                        @elseif(request()->routeIs('admin.keuangan*'))
                            Keuangan
                        @elseif(request()->routeIs('admin.inventori*'))
                            Inventaris
                        @elseif(request()->routeIs('admin.artikel*'))
                            Artikel & CMS
                        @elseif(request()->routeIs('admin.profil'))
                            Profil Admin
                        @else
                            CareHub
                        @endif
                    </h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <i data-lucide="bell" size="20" class="text-gray-300 cursor-pointer hover:text-blue-500 transition-colors"></i>
                    {{-- Avatar yang bisa diklik ke profil --}}
                    <a href="{{ route('admin.profil') }}" class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 border-2 border-white shadow-md overflow-hidden hover:ring-2 hover:ring-blue-400 hover:ring-offset-1 transition-all" title="Profil Admin">
                        <img id="topbarAvatar" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Admin" alt="profile">
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-8 scroll-smooth bg-gray-50/50">
                <div class="animate-page">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // ─── Global Toast System ───────────────────────────────────────────────
        function showToast(msg, type = 'success') {
            const existing = document.getElementById('sintas-toast');
            if (existing) existing.remove();

            const icons = { success: 'check-circle', error: 'x-circle', warning: 'alert-triangle', info: 'info' };
            const colors = {
                success: 'bg-emerald-600 text-white',
                error:   'bg-rose-600 text-white',
                warning: 'bg-amber-500 text-white',
                info:    'bg-blue-600 text-white'
            };

            const toast = document.createElement('div');
            toast.id = 'sintas-toast';
            toast.className = `fixed bottom-6 right-6 z-[9999] flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl font-bold text-sm max-w-sm ${colors[type] || colors.success}`;
            toast.style.cssText = 'animation: toastIn .3s cubic-bezier(.34,1.56,.64,1) forwards';
            toast.innerHTML = `<i data-lucide="${icons[type] || 'check-circle'}" size="20" class="shrink-0"></i><span>${msg}</span>`;
            document.body.appendChild(toast);
            lucide.createIcons();

            setTimeout(() => {
                toast.style.animation = 'toastOut .3s ease forwards';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // ─── Global Confirm Dialog ─────────────────────────────────────────────
        function showConfirm(msg, subtitle = 'Tindakan ini tidak dapat dibatalkan.', title = 'Konfirmasi Tindakan', btnText = 'Lanjutkan') {
            return new Promise(resolve => {
                const existing = document.getElementById('sintas-confirm');
                if (existing) existing.remove();

                const overlay = document.createElement('div');
                overlay.id = 'sintas-confirm';
                overlay.className = 'fixed inset-0 z-[9998] flex items-center justify-center p-4';
                overlay.style.cssText = 'background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); animation: toastIn .2s ease forwards';
                overlay.innerHTML = `
                    <div style="animation: toastIn .25s cubic-bezier(.34,1.56,.64,1) forwards"
                         class="bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-w-sm w-full text-center">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-rose-50 rounded-[1.25rem] flex items-center justify-center mx-auto mb-4 md:mb-5">
                            <i data-lucide="alert-triangle" size="28" class="text-rose-500"></i>
                        </div>
                        <h3 class="font-black text-slate-800 text-base md:text-lg mb-2">${title}</h3>
                        <p class="text-gray-500 text-xs md:text-sm font-medium mb-1">${msg}</p>
                        <p class="text-gray-300 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-6 md:mb-7">${subtitle}</p>
                        <div class="flex flex-col sm:flex-row gap-2 md:gap-3">
                            <button id="confirmNo"
                                class="flex-1 py-3 md:py-3.5 rounded-xl md:rounded-2xl border-2 border-gray-200 text-gray-500 font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-gray-50 transition-all">
                                Batal
                            </button>
                            <button id="confirmYes"
                                class="flex-1 py-3 md:py-3.5 rounded-xl md:rounded-2xl bg-rose-600 text-white font-black text-[10px] md:text-xs uppercase tracking-widest hover:bg-rose-700 transition-all shadow-lg shadow-rose-100">
                                ${btnText}
                            </button>
                        </div>
                    </div>`;

                document.body.appendChild(overlay);
                lucide.createIcons();

                const close = (val) => { overlay.style.animation = 'toastOut .2s ease forwards'; setTimeout(() => overlay.remove(), 200); resolve(val); };
                document.getElementById('confirmYes').onclick = () => close(true);
                document.getElementById('confirmNo').onclick  = () => close(false);
                overlay.onclick = (e) => { if (e.target === overlay) close(false); };
                const esc = (e) => { if (e.key === 'Escape') { close(false); document.removeEventListener('keydown', esc); } };
                document.addEventListener('keydown', esc);
            });
        }
        // ──────────────────────────────────────────────────────────────────────
        document.addEventListener('DOMContentLoaded', async () => {
            // Toast check
            const p = new URLSearchParams(window.location.search);
            const msg = p.get('toast');
            const type = p.get('type') || 'success';
            if (msg) {
                showToast(decodeURIComponent(msg), type);
                window.history.replaceState({}, '', window.location.pathname);
            }

            // Sync User Profile (Avatar & Name)
            const token = localStorage.getItem('auth_token');
            if(token) {
                try {
                    const res = await fetch('/api/user', { 
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' } 
                    });
                    if(res.ok) {
                        const user = await res.json();
                        const topAvatar = document.getElementById('topbarAvatar');
                        if(topAvatar) {
                            if(user.foto) {
                                topAvatar.src = `/storage/${user.foto}`;
                            } else {
                                const seed = encodeURIComponent(user.name || 'Admin');
                                topAvatar.src = `https://api.dicebear.com/7.x/avataaars/svg?seed=${seed}`;
                            }
                        }
                    }
                } catch(e) { console.error('Profile sync failed', e); }
            }
        });

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar
            sidebar.classList.toggle('-translate-x-full');
            
            // Toggle overlay for mobile
            if (window.innerWidth < 1024) {
                if (sidebar.classList.contains('-translate-x-full')) {
                    // Hide overlay
                    overlay.classList.remove('opacity-100');
                    setTimeout(() => overlay.classList.add('hidden'), 300);
                } else {
                    // Show overlay
                    overlay.classList.remove('hidden');
                    // Small delay to allow display:block to apply before animating opacity
                    setTimeout(() => overlay.classList.add('opacity-100'), 10);
                }
            } else {
                sidebar.classList.toggle('lg:hidden'); // on desktop if toggled, just hide completely
            }
        }

        async function handleLogout() {
            const ok = await showConfirm('Anda yakin ingin keluar dari sistem?', 'Sesi anda akan berakhir.', 'Konfirmasi Logout', 'Ya, Log Out');
            if(!ok) return;
            const token = localStorage.getItem('auth_token');
            if(token) {
                try {
                    await fetch('/api/logout', { 
                        method: 'POST', 
                        headers: { 
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json' 
                        } 
                    });
                } catch(e) { console.error(e); }
            }
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user_name');
            localStorage.removeItem('user_email');
            window.location.href = '/login';
        }
    </script>
    <style>
        @keyframes toastIn  { from { opacity:0; transform: translateY(1rem) scale(.95) } to { opacity:1; transform: translateY(0) scale(1) } }
        @keyframes toastOut { from { opacity:1; transform: translateY(0) scale(1) } to { opacity:0; transform: translateY(1rem) scale(.95) } }
    </style>
    @stack('scripts')
</body>
</html>