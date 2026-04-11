@extends('layouts.admin')
@section('title', 'Artikel & CMS - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    <div class="flex justify-between items-center bg-white p-8 rounded-[2rem] border shadow-sm">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Artikel & CMS</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Total: <span id="statArtikel">0</span> Artikel Dipublish</p>
        </div>
        <a href="{{ route('admin.artikel.tambah') }}" class="bg-blue-600 text-white px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2">
            <i data-lucide="plus" size="16"></i> Tulis Artikel
        </a>
    </div>

    {{-- Grid Artikel --}}
    <div id="artikelGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div class="col-span-full py-24 text-center text-gray-400">
            <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
            <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
        </div>
    </div>

    {{-- Pagination Footer --}}
    <div id="paginationBar" class="hidden bg-white rounded-[2rem] border shadow-sm px-8 py-5 flex items-center justify-between">
        <p id="paginationInfo" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
        <div id="paginationBtns" class="flex items-center gap-2"></div>
    </div>
</div>

<script>
    const token = localStorage.getItem('auth_token');
    if(!token) { window.location.href = '/login'; }

    const getAuthHeaders = () => ({
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    });

    const PER_PAGE = 8; // 8 cards per halaman untuk grid 4 kolom
    let allData = [];

    document.addEventListener('DOMContentLoaded', loadArtikel);

    async function loadArtikel() {
        try {
            const res = await fetch('/api/artikel', { headers: getAuthHeaders() });
            if(res.status === 401) { localStorage.removeItem('auth_token'); window.location.href = '/login'; return; }
            allData = await res.json();
            document.getElementById('statArtikel').innerText = allData.length;
            renderPage(1);
        } catch(e) { console.error(e); }
    }

    function renderPage(page) {
        const start = (page - 1) * PER_PAGE;
        const pageData = allData.slice(start, start + PER_PAGE);
        const grid = document.getElementById('artikelGrid');

        if (allData.length === 0) {
            grid.innerHTML = `<div class="col-span-full bg-white p-24 rounded-[2.5rem] border border-dashed text-center">
                <i data-lucide="newspaper" class="mx-auto text-gray-200 mb-4" size="56"></i>
                <p class="text-gray-400 font-bold uppercase text-xs tracking-widest mb-5">Belum ada artikel.</p>
                <a href="/admin/artikel/tambah" class="bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 transition-all">+ Tulis Artikel Pertama</a>
            </div>`;
            lucide.createIcons(); return;
        }

        grid.innerHTML = pageData.map(a => {
            const tanggal = new Date(a.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
            const preview = (a.deskripsi_konten || '').replace(/<[^>]*>?/gm, '').substring(0, 120);
            const hasGambar = a.gambar_konten && a.gambar_konten.trim() !== '';
            return `
            <div class="bg-white rounded-[2.5rem] border overflow-hidden shadow-sm hover:shadow-lg transition-all duration-200 flex flex-col group">
                <div class="h-44 ${hasGambar ? '' : 'bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50'} flex items-center justify-center relative shrink-0 overflow-hidden">
                    ${hasGambar
                        ? `<img src="/storage/${a.gambar_konten}" alt="${a.judul}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">`
                        : `<i data-lucide="newspaper" class="text-blue-100 group-hover:scale-110 transition-transform" size="52"></i>`
                    }
                    <div class="absolute bottom-3 left-4">
                        <span class="bg-white/80 backdrop-blur-sm text-blue-700 text-[9px] font-black px-2.5 py-1 rounded-lg border border-blue-100 uppercase">${tanggal}</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-1">
                    <h3 class="font-black text-gray-800 leading-tight text-base mb-2 line-clamp-2">${a.judul}</h3>
                    <p class="text-gray-400 text-xs leading-relaxed line-clamp-3 flex-1">${preview}${preview.length >= 120 ? '...' : ''}</p>
                    <div class="mt-5 pt-4 border-t border-gray-50 flex justify-between items-center">
                        <a href="/admin/artikel/tambah?id=${a.id}" class="flex items-center gap-1.5 text-blue-500 font-black text-[10px] uppercase hover:text-blue-700 transition-colors">
                            <i data-lucide="edit-3" size="12"></i> Edit
                        </a>
                        <button onclick="hapusArtikel(${a.id})" class="flex items-center gap-1.5 text-rose-400 font-black text-[10px] uppercase hover:text-rose-600 transition-colors">
                            <i data-lucide="trash-2" size="12"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>`;
        }).join('');

        renderPagination(allData.length, page);
        lucide.createIcons();
    }

    function renderPagination(total, page) {
        const totalPages = Math.ceil(total / PER_PAGE);
        const bar = document.getElementById('paginationBar');
        const info = document.getElementById('paginationInfo');
        const btns = document.getElementById('paginationBtns');

        if (total === 0 || totalPages <= 1) { bar.classList.add('hidden'); return; }
        bar.classList.remove('hidden');

        const start = (page - 1) * PER_PAGE + 1;
        const end = Math.min(page * PER_PAGE, total);
        info.innerText = `Menampilkan ${start}–${end} dari ${total} artikel`;

        let html = `<button onclick="renderPage(${page - 1})" ${page === 1 ? 'disabled' : ''}
            class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
            <i data-lucide="chevron-left" size="16"></i>
        </button>`;
        for (let p = 1; p <= totalPages; p++) {
            if (totalPages > 7 && p > 3 && p < totalPages - 2 && Math.abs(p - page) > 1) {
                if (p === 4) html += `<span class="text-gray-300 font-black px-1">···</span>`;
                continue;
            }
            html += `<button onclick="renderPage(${p})"
                class="w-9 h-9 rounded-xl text-xs font-black transition-all ${p === page ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-gray-400 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200'}">
                ${p}
            </button>`;
        }
        html += `<button onclick="renderPage(${page + 1})" ${page === totalPages ? 'disabled' : ''}
            class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
            <i data-lucide="chevron-right" size="16"></i>
        </button>`;

        btns.innerHTML = html;
        lucide.createIcons();
    }

    async function hapusArtikel(id) {
        const ok = await showConfirm('Hapus artikel ini secara permanen?');
        if (!ok) return;
        try {
            await fetch(`/api/artikel/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            showToast('Artikel berhasil dihapus.', 'success');
            loadArtikel();
        } catch(e) { showToast('Gagal menghapus artikel.', 'error'); }
    }
</script>
@endsection