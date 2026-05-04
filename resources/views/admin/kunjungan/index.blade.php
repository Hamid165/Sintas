@extends('layouts.admin')
@section('title', 'Kunjungan Tamu - CareHub')

@section('content')
<div class="space-y-6 w-full">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Kunjungan Tamu</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Total: <span id="statArtikel">0</span> Kunjungan</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari artikel..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 border-gray-200 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportKunjungan()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                @can('create_kunjungan')
                <a href="{{ route('admin.kunjungan.tambah') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Tambah
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Grid Kunjungan Tamu --}}
    <div class="bg-white rounded-[2rem] shadow-sm w-full p-6 md:p-8">
        <div id="artikelGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <div class="col-span-full py-24 text-center text-gray-400">
                <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
            </div>
        </div>

        {{-- Pagination Footer --}}
        <div id="paginationBar" class="hidden px-8 py-5 border-t border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between mt-8 rounded-b-[2rem]">
            <p id="paginationInfo" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
            <div id="paginationBtns" class="flex items-center gap-2"></div>
        </div>
    </div>
</div>

{{-- Modal Detail Kunjungan --}}
<div id="modalDetailKunjungan" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] overflow-hidden shadow-2xl flex flex-col transform scale-95 transition-transform duration-300 relative" id="modalDetailContent">
        <button onclick="tutupDetailKunjungan()" class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/50 text-white rounded-xl flex items-center justify-center hover:bg-black/70 backdrop-blur-md transition-all">
            <i data-lucide="x" size="20"></i>
        </button>
        <div id="detailGambar" class="w-full h-64 bg-gray-100 relative shrink-0"></div>
        <div class="p-6 md:p-8 overflow-y-auto flex-1">
            <div class="flex items-center gap-2 mb-4" id="detailBadges"></div>
            <h2 class="text-2xl md:text-3xl font-black text-slate-800 mb-2" id="detailJudul"></h2>
            <p class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest mb-6" id="detailTanggal"></p>
            <div class="bg-slate-50 p-6 rounded-[1.5rem] border border-gray-100">
                <h4 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-3">Isi Laporan / Keterangan:</h4>
                <div class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap" id="detailDeskripsi"></div>
            </div>
        </div>
    </div>
</div>

<script>
    // Pindahkan modal ke luar dari .animate-page agar tidak terjebak transform CSS
    document.addEventListener("DOMContentLoaded", () => {
        document.body.appendChild(document.getElementById('modalDetailKunjungan'));
    });

    const token = localStorage.getItem('auth_token');
    if(!token) { window.location.href = '/login'; }

    const getAuthHeaders = () => ({
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    });

    const PER_PAGE = 9; // 9 cards per halaman untuk grid 3 kolom
    let allData = [];
    let filteredData = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadArtikel();

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase();
            filteredData = allData.filter(a => 
                (a.judul_kegiatan || '').toLowerCase().includes(keyword) ||
                (a.nama_tamu || '').toLowerCase().includes(keyword) ||
                (a.deskripsi_laporan || '').toLowerCase().includes(keyword)
            );
            document.getElementById('statArtikel').innerText = filteredData.length;
            renderPage(1);
        });

        // ─── Real-Time Pusher Listener ──────────────────────────────────────
        if (window.Echo) {
            window.Echo.channel('kunjungan-channel')
                .listen('KunjunganUpdated', (e) => {
                    console.log('Real-time event received (Kunjungan):', e);
                    if (e.tipe_aksi === 'create') {
                        showToast('Ada data kunjungan tamu baru masuk', 'info');
                    } else if (e.tipe_aksi === 'update') {
                        showToast('Data kunjungan telah diperbarui', 'warning');
                    } else {
                        showToast('Data kunjungan telah dihapus', 'error');
                    }
                    loadArtikel();
                });
        }
        // ──────────────────────────────────────────────────────────────────
    });

    async function loadArtikel() {
        try {
            const res = await fetch('/api/kunjungan-tamu', { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if(res.status === 401) { localStorage.removeItem('auth_token'); window.location.href = '/login'; return; }
            allData = await res.json();
            filteredData = [...allData];
            document.getElementById('statArtikel').innerText = filteredData.length;
            renderPage(1);
        } catch(e) { console.error(e); }
    }

    function renderPage(page) {
        const start = (page - 1) * PER_PAGE;
        const pageData = filteredData.slice(start, start + PER_PAGE);
        const grid = document.getElementById('artikelGrid');

        if (filteredData.length === 0) {
            grid.innerHTML = `<div class="col-span-full py-24 text-center text-gray-400">
                <i data-lucide="users" class="mx-auto text-gray-200 mb-4" size="56"></i>
                <p class="text-gray-400 font-bold uppercase text-xs tracking-widest mb-5">Belum ada kunjungan tamu.</p>
                <a href="/admin/kunjungan/tambah" class="inline-block mt-4 bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 transition-all">+ Tambah Kunjungan Tamu</a>
            </div>`;
            lucide.createIcons(); return;
        }

        grid.innerHTML = pageData.map((a, index) => {
            const tanggal = new Date(a.tanggal_pelaksanaan || a.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
            const hasGambar = a.foto_url && a.foto_url.trim() !== '';
            const imgHTML = hasGambar 
                ? `<img src="${a.foto_url}" class="w-full h-48 object-cover hover:scale-105 transition-transform duration-500">`
                : `<div class="w-full h-48 bg-gray-100 flex flex-col items-center justify-center text-gray-400"><i data-lucide="image" size="48" class="opacity-20 mb-2"></i><span class="text-xs font-bold uppercase tracking-widest opacity-50">Tanpa Foto</span></div>`;
            
            const desc = (a.deskripsi_laporan || '').replace(/<[^>]*>?/gm, '');
            const shortDesc = desc.length > 150 ? desc.substring(0, 150) + '...' : desc;

            return `
            <div class="flex flex-col bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all overflow-hidden group">
                <div onclick="bukaDetailKunjungan(${a.id})" class="cursor-pointer flex flex-col flex-1">
                    <div class="relative w-full h-48 overflow-hidden shrink-0">
                        ${imgHTML}
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                            ${tanggal}
                        </div>
                    </div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="user" size="12"></i> ${a.nama_tamu}</span>
                        </div>
                        
                        <h4 class="text-lg font-black text-slate-800 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">${a.judul_kegiatan}</h4>
                        
                        <p class="text-sm text-gray-500 leading-relaxed flex-1 line-clamp-3">${shortDesc || '<i class="opacity-50">Tidak ada deskripsi laporan.</i>'}</p>
                    </div>
                </div>
                
                <div class="p-6 pt-0 shrink-0">
                    <div class="flex gap-3 pt-4 border-t border-gray-100">
                        ${window.__can('edit_kunjungan') ? `<a href="/admin/kunjungan/tambah?id=${a.id}" class="flex-1 py-3 rounded-xl bg-blue-50 text-blue-600 font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-blue-600 hover:text-white transition-all">Edit Data</a>` : ''}
                        ${window.__can('delete_kunjungan') ? `<button onclick="hapusArtikel(${a.id})" class="flex-1 py-3 rounded-xl bg-rose-50 text-rose-600 font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-rose-600 hover:text-white transition-all">Hapus Data</button>` : ''}
                        ${!window.__can('edit_kunjungan') && !window.__can('delete_kunjungan') ? `<span class="w-full text-center text-[10px] text-gray-300 font-black uppercase py-3">Read Only</span>` : ''}
                    </div>
                </div>
            </div>`;
        }).join('');

        renderPagination(filteredData.length, page);
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
        info.innerText = `Menampilkan ${start}–${end} dari ${total} kunjungan`;

        let html = `<button onclick="renderPage(${page - 1})" ${page === 1 ? 'disabled' : ''}
            class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 border-0 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
            <i data-lucide="chevron-left" size="16"></i>
        </button>`;
        for (let p = 1; p <= totalPages; p++) {
            if (totalPages > 7 && p > 3 && p < totalPages - 2 && Math.abs(p - page) > 1) {
                if (p === 4) html += `<span class="text-gray-300 font-black px-1">···</span>`;
                continue;
            }
            html += `<button onclick="renderPage(${p})"
                class="w-9 h-9 rounded-xl text-xs font-black transition-all ${p === page ? 'bg-blue-600 text-white shadow-lg shadow-blue-100' : 'text-gray-400 border-0 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200'}">
                ${p}
            </button>`;
        }
        html += `<button onclick="renderPage(${page + 1})" ${page === totalPages ? 'disabled' : ''}
            class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 border-0 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all disabled:opacity-30 disabled:cursor-not-allowed">
            <i data-lucide="chevron-right" size="16"></i>
        </button>`;

        btns.innerHTML = html;
        lucide.createIcons();
    }

    async function hapusArtikel(id) {
        const ok = await showConfirm('Hapus kunjungan tamu ini secara permanen?');
        if (!ok) return;
        try {
            await fetch(`/api/kunjungan-tamu/${id}`, { method: 'DELETE', headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'X-Requested-With': 'XMLHttpRequest' } });
            showToast('Kunjungan tamu berhasil dihapus.', 'success');
            loadArtikel();
        } catch(e) { showToast('Gagal menghapus kunjungan tamu.', 'error'); }
    }

    function openExportKunjungan() {
        openExportModal('Kunjungan Tamu', {
            pdf: () => exportKunjunganPdf(),
            excel: () => exportKunjunganExcel(),
            csv: () => exportKunjunganCsv(),
        });
    }

    function fmtTglKunjungan(d) {
        if (!d) return '-';
        return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
    }

    function getKunjunganRows() {
        return filteredData.map((a, index) => {
            const tgl = fmtTglKunjungan(a.tanggal_pelaksanaan || a.created_at);
            const textContent = (a.deskripsi_laporan || '').replace(/<[^>]*>?/gm, '');
            return [index + 1, a.judul_kegiatan || '-', a.nama_tamu || '-', tgl, textContent];
        });
    }

    function exportKunjunganPdf() {
        if (filteredData.length === 0) { showToast('Tidak ada data kunjungan yang bisa diekspor.', 'warning'); return; }
        const rows = getKunjunganRows();
        buildPdf({
            title: 'Laporan Kunjungan Tamu',
            module: 'Kunjungan Tamu',
            columns: ['No', 'Judul Kegiatan', 'Nama Tamu', 'Tanggal Pelaksanaan', 'Deskripsi Laporan'],
            rows: rows,
            filename: `kunjungan_tamu_${new Date().toISOString().slice(0,10)}.pdf`,
        });
        showToast('File PDF sedang diunduh...', 'success');
    }

    function exportKunjunganExcel() {
        if (filteredData.length === 0) { showToast('Tidak ada data kunjungan yang bisa diekspor.', 'warning'); return; }
        const rows = getKunjunganRows();
        buildExcel({
            title: 'Laporan Kunjungan Tamu',
            module: 'Kunjungan Tamu',
            headers: ['No', 'Judul Kegiatan', 'Nama Tamu', 'Tanggal Pelaksanaan', 'Deskripsi Laporan'],
            rows: rows,
            filename: `kunjungan_tamu_${new Date().toISOString().slice(0,10)}.xlsx`,
        });
        showToast('File Excel sedang diunduh...', 'success');
    }

    function exportKunjunganCsv() {
        if (filteredData.length === 0) { showToast('Tidak ada data kunjungan yang bisa diekspor.', 'warning'); return; }
        const rows = getKunjunganRows();
        buildCsv(
            ['No', 'Judul Kegiatan', 'Nama Tamu', 'Tanggal Pelaksanaan', 'Deskripsi Laporan'],
            rows,
            `kunjungan_tamu_${new Date().toISOString().slice(0,10)}.csv`
        );
        showToast('File CSV sedang diunduh...', 'success');
}

    // Modal Detail Logic
    function bukaDetailKunjungan(id) {
        const item = allData.find(d => d.id === id);
        if (!item) return;

        const tanggal = new Date(item.tanggal_pelaksanaan || item.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});

        document.getElementById('detailJudul').textContent = item.judul_kegiatan;
        document.getElementById('detailTanggal').textContent = `Tanggal Kunjungan: ${tanggal}`;
        document.getElementById('detailDeskripsi').innerHTML = item.deskripsi_laporan || '<i>Tidak ada deskripsi.</i>';

        document.getElementById('detailBadges').innerHTML = `
            <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="user" size="14"></i> ${item.nama_tamu}</span>
        `;

        if (item.foto_url && item.foto_url.trim() !== '') {
            document.getElementById('detailGambar').innerHTML = `<img src="${item.foto_url}" class="w-full h-full object-cover">`;
        } else {
            document.getElementById('detailGambar').innerHTML = `<div class="w-full h-full flex flex-col items-center justify-center text-gray-400"><i data-lucide="image" size="48" class="opacity-20 mb-2"></i><span class="text-xs font-bold uppercase tracking-widest opacity-50">Tanpa Foto</span></div>`;
        }

        const modal = document.getElementById('modalDetailKunjungan');
        const content = document.getElementById('modalDetailContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.classList.add('opacity-100');
            content.classList.remove('scale-95');
            content.classList.add('scale-100');
        });

        lucide.createIcons();
    }

    function tutupDetailKunjungan() {
        const modal = document.getElementById('modalDetailKunjungan');
        const content = document.getElementById('modalDetailContent');
        
        modal.classList.remove('opacity-100');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection