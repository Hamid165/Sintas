<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CareHub Admin')</title>
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- xlsx-js-style for Excel Export with Styling (Drop-in replacement for SheetJS) -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx-js-style@1.2.0/dist/xlsx.bundle.js"></script>
    <!-- jsPDF for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>

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

                @can('view_anak')
                <a href="{{ route('admin.anak') }}"
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.anak*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="users" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Manajemen Anak</span>
                </a>
                @endcan

                @can('view_keuangan')
                <a href="{{ route('admin.keuangan') }}"
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.keuangan*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="wallet" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Keuangan</span>
                </a>
                @endcan

                @can('view_inventori')
                <a href="{{ route('admin.inventori') }}"
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.inventoris*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="package" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Inventaris</span>
                </a>
                @endcan

                @can('view_kunjungan')
                <a href="{{ route('admin.kunjungan') }}"
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.kunjungan*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="users-round" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Kunjungan Tamu</span>
                </a>
                @endcan

                @can('view_audit')
                <a href="{{ route('admin.audit') }}"
                class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.audit*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <i data-lucide="shield-check" size="20"></i>
                    <span class="font-black text-xs uppercase tracking-widest">Audit</span>
                </a>
                @endcan

                @if(Auth::user()->role == 'admin')
                    <a href="{{ route('admin.struktur') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.struktur') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="network" size="20"></i>
                        <span class="font-black text-xs uppercase tracking-widest">Struktur</span>
                    </a>
                    <a href="{{ route('admin.role.index') }}" class="flex items-center gap-3 px-4 py-3.5 rounded-2xl transition-all {{ request()->routeIs('admin.role.*') ? 'bg-white/10 text-white' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                        <i data-lucide="shield-alert" size="20"></i>
                        <span class="font-black text-xs uppercase tracking-widest">Hak Akses (RBAC)</span>
                    </a>
                @endif
            </nav>

            <div class="p-6 border-t border-white/10">
                <button onclick="handleLogout()" class="flex items-center gap-3 w-full px-4 py-3 rounded-2xl text-rose-400 hover:bg-rose-400/10 transition-all font-black text-[10px] uppercase tracking-widest">
                    <i data-lucide="log-out" size="18"></i> Log Out
                </button>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <header class="h-16 bg-white border-b border-[#D1D5DC] px-8 flex items-center justify-between sticky top-0 z-40">
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
                        @elseif(request()->routeIs('admin.kunjungan*'))
                            Kunjungan Tamu
                        @elseif(request()->routeIs('admin.audit*'))
                            Audit
                        @elseif(request()->routeIs('admin.profil'))
                            Profil Admin
                        @elseif(request()->routeIs('admin.struktur*'))
                            Struktur
                        @elseif(request()->routeIs('admin.role.*'))
                            Hak Akses (RBAC)
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
        // ─── User Permissions (dari Spatie RBAC) ──────────────────────────────────
        window.__perms = @json(Auth::user()->getAllPermissions()->pluck('name'));
        // Helper: cek apakah user punya permission tertentu
        window.__can = (perm) => window.__perms.includes(perm);
        // ─────────────────────────────────────────────────────────────────────────

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
            // Toast dari URL query param (API-side redirects)
            const p = new URLSearchParams(window.location.search);
            const msg = p.get('toast');
            const type = p.get('type') || 'success';
            if (msg) {
                showToast(decodeURIComponent(msg), type);
                window.history.replaceState({}, '', window.location.pathname);
            }

            // Toast dari Laravel session flash (web form redirects)
            @if(session('toast'))
                showToast(@json(session('toast')), '{{ session('toast_type', 'success') }}');
            @endif

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

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("logout") }}';
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <style>
        @keyframes toastIn  { from { opacity:0; transform: translateY(1rem) scale(.95) } to { opacity:1; transform: translateY(0) scale(1) } }
        @keyframes toastOut { from { opacity:1; transform: translateY(0) scale(1) } to { opacity:0; transform: translateY(1rem) scale(.95) } }
        @keyframes exportModalIn { from { opacity:0; transform: scale(0.92) translateY(-16px); } to { opacity:1; transform: scale(1) translateY(0); } }
        .export-modal-card { animation: exportModalIn 0.3s cubic-bezier(.34,1.56,.64,1) forwards; }
    </style>

    <!-- ── Global Export Modal ───────────────────────────────────────── -->
    <div id="exportModal" class="fixed inset-0 z-[9990] hidden items-center justify-center p-4" style="background: rgba(15,23,42,0.55); backdrop-filter: blur(6px);">
        <div class="export-modal-card bg-white rounded-[2rem] shadow-2xl w-full max-w-sm p-8 text-center relative">
            <!-- Icon -->
            <div class="w-16 h-16 bg-teal-50 rounded-[1.5rem] flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><polyline points="9 15 12 18 15 15"/></svg>
            </div>
            <!-- Title -->
            <h3 class="font-black text-slate-800 text-lg tracking-tight mb-1">EKSPOR LAPORAN RESMI</h3>
            <p id="exportModalSubtitle" class="text-[10px] font-black uppercase tracking-[0.18em] text-gray-400 mb-7">MODUL AKTIF: —</p>
            <!-- Buttons -->
            <div class="space-y-3">
                <button id="exportBtnPdf"
                    class="w-full flex items-center justify-center gap-3 bg-rose-600 hover:bg-rose-700 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl transition-all shadow-lg shadow-rose-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Unduh PDF
                </button>
                <button id="exportBtnExcel"
                    class="w-full flex items-center justify-center gap-3 bg-emerald-500 hover:bg-emerald-600 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl transition-all shadow-lg shadow-emerald-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Unduh Excel
                </button>
                <button id="exportBtnCsv"
                    class="w-full flex items-center justify-center gap-3 bg-blue-500 hover:bg-blue-600 active:scale-[0.98] text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl transition-all shadow-lg shadow-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Unduh CSV
                </button>
            </div>
            <!-- Close -->
            <button onclick="closeExportModal()" class="mt-6 text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 hover:text-gray-600 transition-colors">Kembali</button>
        </div>
    </div>

    <script>
    // ─── Global Export Modal System ─────────────────────────────────────────────
    let _exportCallbacks = { pdf: null, excel: null, csv: null };

    function openExportModal(subtitle, callbacks) {
        _exportCallbacks = callbacks;
        document.getElementById('exportModalSubtitle').textContent = 'MODUL AKTIF: ' + subtitle.toUpperCase();
        const modal = document.getElementById('exportModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        const card = modal.querySelector('.export-modal-card');
        card.style.animation = 'none';
        requestAnimationFrame(() => { card.style.animation = ''; });
    }

    function closeExportModal() {
        const modal = document.getElementById('exportModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.getElementById('exportBtnPdf').onclick   = () => { closeExportModal(); _exportCallbacks.pdf   && _exportCallbacks.pdf(); };
    document.getElementById('exportBtnExcel').onclick = () => { closeExportModal(); _exportCallbacks.excel && _exportCallbacks.excel(); };
    document.getElementById('exportBtnCsv').onclick   = () => { closeExportModal(); _exportCallbacks.csv   && _exportCallbacks.csv(); };
    document.getElementById('exportModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeExportModal(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeExportModal(); });

    // ─── PDF Builder ─────────────────────────────────────────────────────────────
    async function buildPdf({ title, module, columns, rows, filename }) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
        const W = doc.internal.pageSize.getWidth();
        const H = doc.internal.pageSize.getHeight();
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        const timeStr = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });

        // Clean Header Background
        doc.setFillColor(255, 255, 255);
        doc.rect(0, 0, W, 25, 'F');
        
        // Logo Fetching & Placement
        let logoData = null;
        try {
            const response = await fetch('/icon.svg');
            if (response.ok) {
                const svgText = await response.text();
                const blob = new Blob([svgText], {type: 'image/svg+xml;charset=utf-8'});
                const url = URL.createObjectURL(blob);
                
                logoData = await new Promise((resolve) => {
                    const img = new Image();
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width || 800;
                        canvas.height = img.height || 800;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        resolve(canvas.toDataURL('image/png'));
                        URL.revokeObjectURL(url);
                    };
                    img.onerror = () => {
                        resolve(null);
                        URL.revokeObjectURL(url);
                    };
                    img.src = url;
                });
            }
        } catch (e) { console.warn('Gagal memuat logo', e); }

        if (logoData) {
            // The SVG bounding box is 800x800. We place it at size 14x14
            doc.addImage(logoData, 'PNG', 15, 8, 14, 14);
        } else {
            // Fallback Logo circle
            doc.setFillColor(37, 99, 235); // blue-600
            doc.roundedRect(15, 8, 14, 14, 3, 3, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(9);
            doc.text('CH', 22, 17, { align: 'center' });
        }

        // Brand name & Module
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(18);
        doc.setTextColor(30, 41, 59);
        doc.text('Care', 35, 14);
        doc.setTextColor(37, 99, 235);
        doc.text('Hub', 35 + doc.getTextWidth('Care'), 14);
        
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(100, 116, 139);
        doc.text('LAPORAN RESMI  •  SISTEM INFORMASI', 35, 20);

        // Right side: Metadata
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8);
        doc.setTextColor(100, 116, 139);
        doc.text(`Tanggal Cetak: ${dateStr}, ${timeStr}`, W - 15, 14, { align: 'right' });
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(30, 41, 59);
        doc.text(`Modul Aktif: ${module}`, W - 15, 20, { align: 'right' });

        // Separator line
        doc.setDrawColor(226, 232, 240); // slate-200
        doc.setLineWidth(0.5);
        doc.line(15, 26, W - 15, 26);

        // Document Title
        doc.setFont('helvetica', 'bold');
        doc.setFontSize(14);
        doc.setTextColor(15, 23, 42);
        doc.text(title.toUpperCase(), 15, 36);
        
        doc.setFont('helvetica', 'normal');
        doc.setFontSize(8.5);
        doc.setTextColor(100, 116, 139);
        doc.text(`Total Baris: ${rows.length}`, W - 15, 36, { align: 'right' });

        // Elegant Table
        doc.autoTable({
            startY: 44,
            head: [columns],
            body: rows,
            theme: 'plain',
            styles: {
                font: 'helvetica', 
                fontSize: 8.5,
                cellPadding: { top: 4, bottom: 4, left: 4, right: 4 },
                textColor: [71, 85, 105], // slate-500
                lineColor: [226, 232, 240], // slate-200
                lineWidth: { bottom: 0.1 }, 
                valign: 'middle'
            },
            headStyles: {
                fillColor: [248, 250, 252], // slate-50
                textColor: [15, 23, 42], // slate-900
                fontStyle: 'bold', 
                fontSize: 8.5, 
                halign: 'left',
                valign: 'middle',
                lineWidth: { top: 0.5, bottom: 0.5 },
                lineColor: [203, 213, 225] // slate-300
            },
            alternateRowStyles: { 
                fillColor: [255, 255, 255]
            },
            columnStyles: { 
                0: { halign: 'center', fontStyle: 'bold', cellWidth: 12 } // 12mm is plenty with 4mm padding (4mm text space)
            },
            margin: { left: 15, right: 15, bottom: 20 },
        });

        // Footer on each page
        const totalPages = doc.internal.getNumberOfPages();
        for (let p = 1; p <= totalPages; p++) {
            doc.setPage(p);
            // Footer separator
            doc.setDrawColor(226, 232, 240);
            doc.setLineWidth(0.5);
            doc.line(15, H - 15, W - 15, H - 15);

            doc.setFont('helvetica', 'normal');
            doc.setFontSize(8);
            doc.setTextColor(148, 163, 184); // slate-400
            doc.text('© CareHub Admin  ·  Dokumen ini dibuat otomatis oleh sistem dan sah secara digital.', 15, H - 9);
            doc.text(`Halaman ${p} dari ${totalPages}`, W - 15, H - 9, { align: 'right' });
        }

        doc.save(filename);
    }

    // ─── Excel Builder ───────────────────────────────────────────────────────────
    function buildExcel({ title, module, headers, rows, filename }) {
        const wb = XLSX.utils.book_new();
        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' }) + ' ' + now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });

        const ws_data = [
            ['CareHub'],
            ['LAPORAN RESMI • SISTEM INFORMASI'],
            [],
            ['Judul Laporan:', title],
            ['Modul Aktif:', module],
            ['Tanggal Cetak:', dateStr],
            ['Total Data:', `${rows.length} Baris`],
            [],
            headers,
            ...rows
        ];
        const ws = XLSX.utils.aoa_to_sheet(ws_data);

        // Styling Cells
        const range = XLSX.utils.decode_range(ws['!ref']);
        for (let R = range.s.r; R <= range.e.r; ++R) {
            for (let C = range.s.c; C <= range.e.c; ++C) {
                const cell = ws[XLSX.utils.encode_cell({c: C, r: R})];
                if (!cell) continue;

                cell.s = { font: { name: 'Arial', sz: 11 }, alignment: { vertical: 'center' } };

                // Kop Surat Styling
                if (R === 0) cell.s.font = { name: 'Arial', sz: 16, bold: true, color: { rgb: "0F172A" } };
                if (R === 1) cell.s.font = { name: 'Arial', sz: 9, color: { rgb: "64748B" } };
                if (R >= 3 && R <= 6 && C === 0) cell.s.font = { bold: true };

                // Table Header (Row 8)
                if (R === 8) {
                    cell.s.font = { bold: true, color: { rgb: "FFFFFF" } };
                    cell.s.fill = { fgColor: { rgb: "1E3A8A" } }; // blue-900
                    cell.s.alignment = { vertical: 'center', horizontal: 'center' };
                    cell.s.border = {
                        top: { style: 'thin', color: { auto: 1 } },
                        bottom: { style: 'thin', color: { auto: 1 } },
                        left: { style: 'thin', color: { auto: 1 } },
                        right: { style: 'thin', color: { auto: 1 } }
                    };
                }

                // Table Rows (Row 9+)
                if (R >= 9) {
                    cell.s.border = {
                        top: { style: 'thin', color: { rgb: "CBD5E1" } },
                        bottom: { style: 'thin', color: { rgb: "CBD5E1" } },
                        left: { style: 'thin', color: { rgb: "CBD5E1" } },
                        right: { style: 'thin', color: { rgb: "CBD5E1" } }
                    };
                    if (C === 0) cell.s.alignment.horizontal = 'center';
                }
            }
        }

        // Column widths
        const colW = headers.map((h, i) => ({ wch: i === 0 ? 6 : Math.max(h.length + 6, 20) }));
        ws['!cols'] = colW;

        // Merge title cell across all columns
        ws['!merges'] = [{ s:{r:0,c:0}, e:{r:0,c:headers.length-1} }];

        XLSX.utils.book_append_sheet(wb, ws, module.substring(0, 31));
        XLSX.writeFile(wb, filename);
    }

    // ─── CSV Builder ──────────────────────────────────────────────────────────────
    function buildCsv(headers, rows, filename) {
        const BOM = '\uFEFF';
        const esc = (v) => {
            const s = String(v ?? '');
            return s.includes(',') || s.includes('"') || s.includes('\n') ? `"${s.replace(/"/g, '""')}"` : s;
        };

        const now = new Date();
        const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' }) + ' ' + now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
        
        let title = filename.split('_').slice(0, -1).join(' ').toUpperCase() || 'LAPORAN SISTEM';

        const kopSurat = [
            ['CareHub'],
            ['LAPORAN RESMI • SISTEM INFORMASI'],
            [],
            ['Judul Laporan:', title],
            ['Tanggal Cetak:', dateStr],
            ['Total Data:', `${rows.length} Baris`],
            []
        ].map(r => r.map(esc).join(','));

        const csvRows = [headers, ...rows].map(r => r.map(esc).join(','));
        const csvContent = BOM + kopSurat.join('\n') + '\n' + csvRows.join('\n');

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = filename; a.click();
        setTimeout(() => URL.revokeObjectURL(url), 1000);
    }
    </script>
    @stack('scripts')
</body>
</html>
