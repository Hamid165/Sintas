@extends('layouts.admin')
@section('title', 'Keuangan - CareHub')

@section('content')
<div class="space-y-6 w-full">

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Manajemen Keuangan</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Laporan Pemasukan & Pengeluaran CareHub</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari transaksi..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 border-gray-200 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportKeuangan()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                @can('create_keuangan')
                <a href="{{ route('admin.keuangan.tambah') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Tambah
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Total Pemasukan</p>
            </div>
            <h3 id="statMasuk" class="text-3xl font-black text-emerald-700">Rp 0</h3>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-down" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest">Total Pengeluaran</p>
            </div>
            <h3 id="statKeluar" class="text-3xl font-black text-rose-700">Rp 0</h3>
        </div>
        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 p-8 rounded-[2rem] shadow-xl text-white">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <i data-lucide="wallet" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest">Saldo Bersih</p>
            </div>
            <h3 id="statSaldo" class="text-3xl font-black">Rp 0</h3>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">
        <div class="p-6 border-b border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
            <h4 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800">Riwayat Transaksi</h4>
            <span id="totalTrx" class="text-[10px] font-black text-gray-400 uppercase">0 transaksi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                    <tr>
                        <th class="px-6 py-5 w-8">No</th>
                        <th class="px-6 py-5">
                            <button onclick="toggleSort()" class="flex items-center gap-1.5 group hover:text-blue-600 transition-colors" title="Urutkan berdasarkan Tanggal">
                                TANGGAL
                                <span id="sortIcon" class="flex flex-col gap-[2px] opacity-40 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="chevrons-up-down" size="12"></i>
                                </span>
                            </button>
                        </th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Keterangan</th>
                        <th class="px-6 py-5">Jenis</th>
                        <th class="px-6 py-5 text-left">
                            <button onclick="toggleSortNominal()" class="flex items-center gap-1.5 group hover:text-blue-600 transition-colors" title="Urutkan berdasarkan Nominal">
                                NOMINAL
                                <span id="sortIconNominal" class="flex flex-col gap-[2px] opacity-40 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="chevrons-up-down" size="12"></i>
                                </span>
                            </button>
                        </th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="keuanganTable" class="divide-y divide-gray-100 text-sm">
                    <tr><td colspan="7" class="px-8 py-24 text-center text-gray-400">
                        <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                        <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div id="paginationBar" class="hidden px-8 py-5 border-t border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
            <p id="paginationInfo" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
            <div id="paginationBtns" class="flex items-center gap-2"></div>
        </div>
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

    const formatRp = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(n);

    const PER_PAGE = 10;
    let allData = [];
    let filteredData = [];
    let sortOrder = null; // null | 'asc' | 'desc'
    let sortOrderNominal = null; // null | 'asc' | 'desc'

    document.addEventListener('DOMContentLoaded', () => {
        loadKeuangan();

        // ─── Real-Time Pusher Listener ──────────────────────────────────────
        if (window.Echo) {
            window.Echo.channel('keuangan-channel')
                .listen('KeuanganUpdated', (e) => {
                    console.log('Real-time event received:', e);
                    if (e.tipe_aksi === 'create') {
                        showToast('Ada data keuangan baru masuk', 'info');
                    } else if (e.tipe_aksi === 'update') {
                        showToast('Data keuangan telah diubah', 'warning');
                    } else {
                        showToast('Data keuangan telah dihapus', 'error');
                    }
                    
                    // Langsung muat ulang tabel keuangan untuk mensinkronisasi data
                    loadKeuangan();
                });
        }
        // ──────────────────────────────────────────────────────────────────

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase();
            filteredData = allData.filter(t =>
                (t.kategori || '').toLowerCase().includes(keyword) ||
                (t.keterangan || '').toLowerCase().includes(keyword) ||
                (t.jenis_transaksi || '').toLowerCase().includes(keyword)
            );
            applySortToFiltered();
            updateStats();
            renderPage(1);
        });
    });

    async function loadKeuangan() {
        try {
            const res = await fetch('/api/keuangan', { headers: getAuthHeaders() });
            if(res.status === 401) { localStorage.removeItem('auth_token'); window.location.href = '/login'; return; }
            allData = await res.json();
            filteredData = [...allData];
            applySortToFiltered();
            updateStats();
            renderPage(1);
        } catch(e) { console.error(e); }
    }

    function applySortToFiltered() {
        if (sortOrderNominal) {
            filteredData.sort((a, b) => {
                const na = parseFloat(a.jumlah_nominal) || 0;
                const nb = parseFloat(b.jumlah_nominal) || 0;
                return sortOrderNominal === 'asc' ? na - nb : nb - na;
            });
        } else if (sortOrder) {
            filteredData.sort((a, b) => {
                const da = new Date(a.created_at);
                const db = new Date(b.created_at);
                return sortOrder === 'asc' ? da - db : db - da;
            });
        }
    }

    function toggleSort() {
        sortOrderNominal = null;
        resetSortIconNominal();
        if (sortOrder === null || sortOrder === 'desc') {
            sortOrder = 'asc';
        } else {
            sortOrder = 'desc';
        }
        updateSortIcon();
        applySortToFiltered();
        renderPage(1);
    }

    function toggleSortNominal() {
        sortOrder = null;
        resetSortIconDate();
        if (sortOrderNominal === null || sortOrderNominal === 'desc') {
            sortOrderNominal = 'asc';
        } else {
            sortOrderNominal = 'desc';
        }
        updateSortIconNominal();
        applySortToFiltered();
        renderPage(1);
    }

    function resetSortIconDate() {
        const icon = document.getElementById('sortIcon');
        icon.innerHTML = '<i data-lucide="chevrons-up-down" size="12"></i>';
        icon.parentElement.classList.remove('text-blue-600');
        icon.classList.add('opacity-40');
        icon.classList.remove('opacity-100');
        lucide.createIcons();
    }

    function resetSortIconNominal() {
        const icon = document.getElementById('sortIconNominal');
        if (!icon) return;
        icon.innerHTML = '<i data-lucide="chevrons-up-down" size="12"></i>';
        icon.parentElement.classList.remove('text-blue-600');
        icon.classList.add('opacity-40');
        icon.classList.remove('opacity-100');
        lucide.createIcons();
    }

    function updateSortIcon() {
        const icon = document.getElementById('sortIcon');
        if (sortOrder === 'asc') {
            icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m18 15-6-6-6 6"/></svg>';
            icon.parentElement.classList.add('text-blue-600');
            icon.classList.remove('opacity-40');
            icon.classList.add('opacity-100');
        } else if (sortOrder === 'desc') {
            icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m6 9 6 6 6-6"/></svg>';
            icon.parentElement.classList.add('text-blue-600');
            icon.classList.remove('opacity-40');
            icon.classList.add('opacity-100');
        }
    }

    function updateSortIconNominal() {
        const icon = document.getElementById('sortIconNominal');
        if (!icon) return;
        if (sortOrderNominal === 'asc') {
            icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m18 15-6-6-6 6"/></svg>';
            icon.parentElement.classList.add('text-blue-600');
            icon.classList.remove('opacity-40');
            icon.classList.add('opacity-100');
        } else if (sortOrderNominal === 'desc') {
            icon.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600"><path d="m6 9 6 6 6-6"/></svg>';
            icon.parentElement.classList.add('text-blue-600');
            icon.classList.remove('opacity-40');
            icon.classList.add('opacity-100');
        }
    }

    function updateStats() {
        let totalMasuk = 0, totalKeluar = 0;
        filteredData.forEach(t => {
            const n = parseFloat(t.jumlah_nominal);
            if(t.jenis_transaksi === 'Pemasukan') totalMasuk += n;
            else totalKeluar += n;
        });
        document.getElementById('statMasuk').innerText = formatRp(totalMasuk);
        document.getElementById('statKeluar').innerText = formatRp(totalKeluar);
        document.getElementById('statSaldo').innerText = formatRp(totalMasuk - totalKeluar);
        document.getElementById('totalTrx').innerText = filteredData.length + ' transaksi';
    }

    function renderPage(page) {
        const start = (page - 1) * PER_PAGE;
        const pageData = filteredData.slice(start, start + PER_PAGE);
        const tbody = document.getElementById('keuanganTable');

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-8 py-24 text-center text-gray-400">
                <i data-lucide="inbox" class="mx-auto text-gray-200 mb-4" size="48"></i>
                <p class="font-bold uppercase text-xs tracking-widest mb-4">Belum ada riwayat transaksi.</p>
                <a href="/admin/keuangan/tambah" class="bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 transition-all">+ Tambah Transaksi</a>
            </td></tr>`;
            lucide.createIcons(); return;
        }

        tbody.innerHTML = pageData.map((t, idx) => {
            const nominal = parseFloat(t.jumlah_nominal);
            return `<tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-gray-800 font-black text-xs">${start + idx + 1}</td>
                <td class="px-6 py-4 font-bold text-gray-800 text-xs whitespace-nowrap">${new Date(t.created_at).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})}</td>
                <td class="px-6 py-4 font-black text-gray-800">${t.kategori}</td>
                <td class="px-6 py-4 text-gray-800 text-xs">${t.keterangan || '-'}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase ${t.jenis_transaksi === 'Pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
                        ${t.jenis_transaksi}
                    </span>
                </td>
                <td class="px-6 py-4 text-left font-black text-base ${t.jenis_transaksi === 'Pemasukan' ? 'text-emerald-600' : 'text-rose-600'}">
                    ${t.jenis_transaksi === 'Pemasukan' ? '+' : '-'} ${formatRp(nominal)}
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        ${window.__can('delete_keuangan') ? `<button onclick="hapusKeuangan(${t.id})" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center mx-auto" title="Hapus"><i data-lucide="trash-2" size="14"></i></button>` : `<span class="text-[10px] text-gray-300 font-black uppercase">Read Only</span>`}
                    </div>
                </td>
            </tr>`;
        }).join('');

        renderPagination(filteredData.length, page);
        lucide.createIcons();
    }

    function renderPagination(total, page) {
        const totalPages = Math.ceil(total / PER_PAGE);
        const bar = document.getElementById('paginationBar');
        const info = document.getElementById('paginationInfo');
        const btns = document.getElementById('paginationBtns');

        if (total === 0) { bar.classList.add('hidden'); return; }
        bar.classList.remove('hidden');

        const start = (page - 1) * PER_PAGE + 1;
        const end = Math.min(page * PER_PAGE, total);
        info.innerText = `Menampilkan ${start}–${end} dari ${total} data`;

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

    async function hapusKeuangan(id) {
        const ok = await showConfirm('Hapus transaksi ini dari riwayat keuangan?');
        if (!ok) return;
        try {
            await fetch(`/api/keuangan/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            showToast('Transaksi berhasil dihapus.', 'success');
            loadKeuangan();
        } catch(e) { showToast('Gagal menghapus transaksi.', 'error'); }
    }

    function openExportKeuangan() {
        if (filteredData.length === 0) return showToast('Tidak ada data yang bisa diekspor.', 'warning');
        openExportModal('Keuangan', {
            pdf:   () => exportKeuanganPdf(),
            excel: () => exportKeuanganExcel(),
            csv:   () => exportKeuanganCsv(),
        });
    }

    function _getKeuanganRows() {
        return filteredData.map((t, i) => {
            const tgl = new Date(t.created_at).toLocaleDateString('id-ID',{day:'numeric',month:'short',year:'numeric'});
            const nominal = parseFloat(t.jumlah_nominal) || 0;
            return [i+1, tgl, t.kategori||'-', t.keterangan||'-', t.jenis_transaksi||'-', nominal.toLocaleString('id-ID')];
        });
    }
    const _keuanganHeaders = ['No', 'Tanggal', 'Kategori', 'Keterangan', 'Jenis', 'Nominal (Rp)'];

    function exportKeuanganPdf() {
        showToast('Laporan PDF sedang diunduh...', 'success');
        buildPdf({
            title: 'Laporan Keuangan CareHub',
            module: 'Keuangan',
            columns: _keuanganHeaders,
            rows: _getKeuanganRows(),
            filename: `carehub-keuangan-${Date.now()}.pdf`
        });
    }

    function exportKeuanganExcel() {
        showToast('File Excel sedang diunduh...', 'success');
        buildExcel({
            title: 'Laporan Keuangan CareHub',
            module: 'Keuangan',
            headers: _keuanganHeaders,
            rows: _getKeuanganRows(),
            filename: `carehub-keuangan-${Date.now()}.xlsx`
        });
    }

    function exportKeuanganCsv() {
        showToast('File CSV sedang diunduh...', 'success');
        buildCsv(_keuanganHeaders, _getKeuanganRows(), `carehub-keuangan-${Date.now()}.csv`);
    }
</script>
@endsection
