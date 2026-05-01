@extends('layouts.admin')
@section('title', 'Inventaris - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Logistik & Inventaris</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Total: <span id="statBarang">0</span> Item Tersimpan</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari barang..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 border-gray-200 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportInventaris()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                <a href="{{ route('admin.inventori.tambah') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Tambah
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                    <tr>
                        <th class="px-6 py-5 w-8">No</th>
                        <th class="px-6 py-5">Foto</th>
                        <th class="px-6 py-5">
                            <button onclick="toggleSort()" class="flex items-center gap-1.5 group hover:text-blue-600 transition-colors" title="Urutkan berdasarkan Nama Barang">
                                NAMA BARANG
                                <span id="sortIcon" class="flex flex-col gap-[2px] opacity-40 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="chevrons-up-down" size="12"></i>
                                </span>
                            </button>
                        </th>
                        <th class="px-6 py-5">Kategori</th>
                        <th class="px-6 py-5">Stok</th>
                        <th class="px-6 py-5">Kondisi</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="inventarisTBody" class="divide-y divide-gray-100 text-sm">
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

    const kategoriBadge = {
        'Sembako':         'bg-amber-50 text-amber-700',
        'Kebutuhan Mandi': 'bg-sky-50 text-sky-700',
        'Pakaian':         'bg-purple-50 text-purple-700',
        'Pendidikan':      'bg-blue-50 text-blue-700',
        'Kesehatan':       'bg-green-50 text-green-700',
        'Lainnya':         'bg-gray-100 text-gray-600'
    };

    const kondisiBadge = {
        'Baik':          'bg-emerald-50 text-emerald-700',
        'Cukup Baik':    'bg-yellow-50 text-yellow-700',
        'Rusak Ringan':  'bg-orange-50 text-orange-700',
        'Rusak Berat':   'bg-rose-50 text-rose-700'
    };

    const PER_PAGE = 10;
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    let sortOrder = null; // null | 'asc' | 'desc'

    document.addEventListener('DOMContentLoaded', () => {
        loadInventori();

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase();
            filteredData = allData.filter(i => 
                (i.nama_barang || '').toLowerCase().includes(keyword) ||
                (i.kategori || '').toLowerCase().includes(keyword) ||
                (i.kondisi || '').toLowerCase().includes(keyword)
            );
            applySortToFiltered();
            document.getElementById('statBarang').innerText = filteredData.length;
            renderPage(1);
        });

        // ─── Real-Time Pusher Listener ──────────────────────────────────────
        if (window.Echo) {
            window.Echo.channel('inventaris-channel')
                .listen('InventarisUpdated', (e) => {
                    console.log('Real-time event received (Inventaris):', e);
                    if (e.tipe_aksi === 'create') {
                        showToast('Ada data inventaris baru masuk', 'info');
                    } else if (e.tipe_aksi === 'update') {
                        showToast('Data inventaris telah diperbarui', 'warning');
                    } else {
                        showToast('Data inventaris telah dihapus', 'error');
                    }
                    loadInventori();
                });
        }
        // ──────────────────────────────────────────────────────────────────
    });

    async function loadInventori() {
        try {
            const res = await fetch('/api/inventaris', { headers: getAuthHeaders() });
            if(res.status === 401) { localStorage.removeItem('auth_token'); window.location.href = '/login'; return; }
            allData = await res.json();
            filteredData = [...allData];
            applySortToFiltered();
            document.getElementById('statBarang').innerText = filteredData.length;
            renderPage(1);
        } catch(e) { console.error(e); }
    }

    function applySortToFiltered() {
        if (!sortOrder) return;
        filteredData.sort((a, b) => {
            const na = (a.nama_barang || '').toLowerCase();
            const nb = (b.nama_barang || '').toLowerCase();
            return sortOrder === 'asc' ? na.localeCompare(nb) : nb.localeCompare(na);
        });
    }

    function toggleSort() {
        if (sortOrder === null || sortOrder === 'desc') {
            sortOrder = 'asc';
        } else {
            sortOrder = 'desc';
        }
        updateSortIcon();
        applySortToFiltered();
        renderPage(1);
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

    function renderPage(page) {
        currentPage = page;
        const start = (page - 1) * PER_PAGE;
        const pageData = filteredData.slice(start, start + PER_PAGE);
        const tbody = document.getElementById('inventarisTBody');

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-8 py-24 text-center text-gray-400">
                <i data-lucide="package" class="mx-auto text-gray-200 mb-4" size="48"></i>
                <p class="font-bold text-gray-400 uppercase text-xs tracking-widest mb-4">Belum ada barang inventaris.</p>
                <a href="/admin/inventori/tambah" class="bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 transition-all">+ Tambah Barang Pertama</a>
            </td></tr>`;
            lucide.createIcons(); return;
        }

        tbody.innerHTML = pageData.map((i, idx) => {
            const badge = kategoriBadge[i.kategori] || 'bg-gray-100 text-gray-600';
            const kondisiClass = kondisiBadge[i.kondisi] || 'bg-gray-50 text-gray-600';
            const stokWarn = i.stok < 5;
            return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 text-gray-800 font-black text-xs">${start + idx + 1}</td>
                <td class="px-6 py-4">
                    ${i.gambar
                        ? `<img src="/storage/${i.gambar}" alt="${i.nama_barang}" class="w-14 h-14 object-cover rounded-2xl border-0 border-gray-100 shadow-sm">`
                        : `<div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center"><i data-lucide="package" size="22" class="text-gray-300"></i></div>`
                    }
                </td>
                <td class="px-6 py-4">
                    <p class="font-black text-gray-800">${i.nama_barang}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase ${badge}">${i.kategori}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="font-black text-lg ${stokWarn ? 'text-rose-600' : 'text-slate-800'}">${i.stok}</span>
                    <span class="text-[10px] text-gray-800 font-bold ml-1">unit</span>
                    ${stokWarn ? `<p class="text-[9px] text-rose-500 font-black uppercase mt-0.5">⚠ Stok Menipis</p>` : ''}
                </td>
                <td class="px-6 py-4">
                    <span class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase ${kondisiClass}">${i.kondisi}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="/admin/inventori/tambah?id=${i.id}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center" title="Edit">
                            <i data-lucide="edit-3" size="14"></i>
                        </a>
                        <button onclick="hapusBarang(${i.id})" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center" title="Hapus">
                            <i data-lucide="trash-2" size="14"></i>
                        </button>
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

    async function hapusBarang(id) {
        const ok = await showConfirm('Hapus barang ini dari inventaris?');
        if (!ok) return;
        try {
            await fetch(`/api/inventaris/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            showToast('Barang berhasil dihapus dari inventaris.', 'success');
            loadInventori();
        } catch(e) { showToast('Gagal menghapus barang.', 'error'); }
    }

    function openExportInventaris() {
        if (filteredData.length === 0) return showToast('Tidak ada data yang bisa diekspor.', 'warning');
        openExportModal('Inventaris', {
            pdf:   () => exportInventarisPdf(),
            excel: () => exportInventarisExcel(),
            csv:   () => exportInventarisCsv(),
        });
    }

    function _getInventarisRows() {
        return filteredData.map((item, i) => [
            i + 1,
            item.nama_barang || '-',
            item.kategori || '-',
            item.stok ?? 0,
            item.kondisi || '-'
        ]);
    }
    const _inventarisHeaders = ['No', 'Nama Barang', 'Kategori', 'Stok (unit)', 'Kondisi'];

    function exportInventarisPdf() {
        showToast('Laporan PDF sedang diunduh...', 'success');
        buildPdf({
            title: 'Laporan Inventaris CareHub',
            module: 'Inventaris',
            columns: _inventarisHeaders,
            rows: _getInventarisRows(),
            filename: `carehub-inventaris-${Date.now()}.pdf`
        });
    }

    function exportInventarisExcel() {
        showToast('File Excel sedang diunduh...', 'success');
        buildExcel({
            title: 'Laporan Inventaris CareHub',
            module: 'Inventaris',
            headers: _inventarisHeaders,
            rows: _getInventarisRows(),
            filename: `carehub-inventaris-${Date.now()}.xlsx`
        });
    }

    function exportInventarisCsv() {
        showToast('File CSV sedang diunduh...', 'success');
        buildCsv(_inventarisHeaders, _getInventarisRows(), `carehub-inventaris-${Date.now()}.csv`);
    }
</script>
@endsection