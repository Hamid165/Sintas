@extends('layouts.admin')
@section('title', 'Manajemen Anak - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Database Anak Asuh</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Total: <span id="statTotalAnak">0</span> Anak Terdaftar</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari nama anak..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 border-gray-200 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportAnak()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                <a href="{{ route('admin.anak.tambah') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Tambah
                </a>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-slate-800 text-[10px] uppercase font-black border-b border-[#D1D5DC]">
                    <tr>
                        <th class="px-6 py-5 w-8">No</th>
                        <th class="px-6 py-5">
                            <button onclick="toggleSort()" class="flex items-center gap-1.5 group hover:text-blue-600 transition-colors" title="Urutkan berdasarkan Nama">
                                NAMA LENGKAP
                                <span id="sortIcon" class="flex flex-col gap-[2px] opacity-40 group-hover:opacity-100 transition-opacity">
                                    <i data-lucide="chevrons-up-down" size="12"></i>
                                </span>
                            </button>
                        </th>
                        <th class="px-6 py-5">Usia</th>
                        <th class="px-6 py-5">Jenis Kelamin</th>
                        <th class="px-6 py-5">Tempat / Tgl Lahir</th>
                        <th class="px-6 py-5">Riwayat Kesehatan</th>
                        <th class="px-6 py-5">Pendidikan</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anakTableBody" class="divide-y divide-gray-100 text-sm">
                    <tr><td colspan="8" class="px-8 py-24 text-center text-gray-400">
                        <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                        <p class="text-xs uppercase tracking-widest font-bold mt-2">Memuat data...</p>
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

    const PER_PAGE = 10;
    let allData = [];
    let filteredData = [];
    let currentPage = 1;
    let sortOrder = null; // null | 'asc' | 'desc'

    document.addEventListener('DOMContentLoaded', () => {
        loadData();

        document.getElementById('searchInput').addEventListener('input', (e) => {
            const keyword = e.target.value.toLowerCase();
            filteredData = allData.filter(c => 
                (c.nama_lengkap || '').toLowerCase().includes(keyword) ||
                (c.tempat_tgl_lahir || '').toLowerCase().includes(keyword) ||
                (c.riwayat_kesehatan || '').toLowerCase().includes(keyword) ||
                (c.info_pendidikan || '').toLowerCase().includes(keyword)
            );
            applySortToFiltered();
            document.getElementById('statTotalAnak').innerText = filteredData.length;
            renderPage(1);
        });
    });

    async function loadData() {
        try {
            const res = await fetch('/api/anak', { headers: getAuthHeaders() });
            if(res.status === 401) { localStorage.removeItem('auth_token'); window.location.href = '/login'; return; }
            allData = await res.json();
            filteredData = [...allData];
            applySortToFiltered();
            document.getElementById('statTotalAnak').innerText = filteredData.length;
            renderPage(1);
        } catch (e) { console.error(e); }
    }

    function applySortToFiltered() {
        if (!sortOrder) return;
        filteredData.sort((a, b) => {
            const na = (a.nama_lengkap || '').toLowerCase();
            const nb = (b.nama_lengkap || '').toLowerCase();
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
        const end = start + PER_PAGE;
        const pageData = filteredData.slice(start, end);
        const tbody = document.getElementById('anakTableBody');

        if (filteredData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="px-8 py-24 text-center text-gray-400">
                <i data-lucide="users" class="mx-auto text-gray-200 mb-4" size="48"></i>
                <p class="font-bold uppercase text-xs tracking-widest mb-4">Belum ada data anak asuh.</p>
                <a href="/admin/anak/tambah" class="bg-blue-600 text-white px-6 py-3 rounded-2xl text-xs font-black uppercase hover:bg-blue-700 transition-all">+ Tambah Anak Pertama</a>
            </td></tr>`;
            lucide.createIcons(); return;
        }

        tbody.innerHTML = pageData.map((c, i) => `
            <tr class="hover:bg-blue-50/20 transition-colors">
                <td class="px-6 py-4 text-gray-800 font-black text-xs">${start + i + 1}</td>
                <td class="px-6 py-4">
                    <p class="font-black text-gray-800">${c.nama_lengkap}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="bg-blue-50 text-blue-700 font-black text-xs px-3 py-1.5 rounded-xl">${c.usia} Tahun</span>
                </td>
                <td class="px-6 py-4">
                    <span class="font-bold text-xs px-3 py-1.5 rounded-xl ${c.jenis_kelamin === 'Perempuan' ? 'bg-pink-50 text-pink-700' : 'bg-sky-50 text-sky-700'}">
                        ${c.jenis_kelamin === 'Perempuan' ? '♀' : '♂'} ${c.jenis_kelamin}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-800 text-xs font-medium">${c.tempat_tgl_lahir || '-'}</td>
                <td class="px-6 py-4">
                    <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-3 py-1.5 rounded-xl border-0 border-rose-100 italic">${c.riwayat_kesehatan || 'Sehat'}</span>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-800">${c.info_pendidikan || '-'}</td>
                <td class="px-6 py-4">
                    <div class="flex justify-center gap-2">
                        <a href="/admin/anak/tambah?id=${c.id}" class="w-9 h-9 rounded-xl bg-blue-50 text-blue-500 hover:bg-blue-500 hover:text-white transition-all flex items-center justify-center" title="Edit">
                            <i data-lucide="edit-3" size="14"></i>
                        </a>
                        <button onclick="hapusAnak(${c.id})" class="w-9 h-9 rounded-xl bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center" title="Hapus">
                            <i data-lucide="trash-2" size="14"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

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

    async function hapusAnak(id) {
        const ok = await showConfirm('Hapus profil anak ini dari database CareHub?');
        if (!ok) return;
        try {
            await fetch(`/api/anak/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            showToast('Data anak berhasil dihapus.', 'success');
            loadData();
        } catch(e) { showToast('Gagal menghapus data.', 'error'); }
    }

    function openExportAnak() {
        if (filteredData.length === 0) return showToast('Tidak ada data yang bisa diekspor.', 'warning');
        openExportModal('Manajemen Anak', {
            pdf:   () => exportAnakPdf(),
            excel: () => exportAnakExcel(),
            csv:   () => exportAnakCsv(),
        });
    }

    function _getAnakRows() {
        return filteredData.map((c, i) => [
            i + 1,
            c.nama_lengkap || '-',
            (c.usia || '0') + ' Tahun',
            c.jenis_kelamin || '-',
            c.tempat_tgl_lahir || '-',
            c.riwayat_kesehatan || 'Sehat',
            c.info_pendidikan || '-'
        ]);
    }
    const _anakHeaders = ['No', 'Nama Lengkap', 'Usia', 'Jenis Kelamin', 'Tempat / Tgl Lahir', 'Riwayat Kesehatan', 'Pendidikan'];

    function exportAnakPdf() {
        showToast('Laporan PDF sedang diunduh...', 'success');
        buildPdf({
            title: 'Data Anak Asuh CareHub',
            module: 'Manajemen Anak',
            columns: _anakHeaders,
            rows: _getAnakRows(),
            filename: `carehub-anak-${Date.now()}.pdf`
        });
    }

    function exportAnakExcel() {
        showToast('File Excel sedang diunduh...', 'success');
        buildExcel({
            title: 'Data Anak Asuh CareHub',
            module: 'Manajemen Anak',
            headers: _anakHeaders,
            rows: _getAnakRows(),
            filename: `carehub-anak-${Date.now()}.xlsx`
        });
    }

    function exportAnakCsv() {
        showToast('File CSV sedang diunduh...', 'success');
        buildCsv(_anakHeaders, _getAnakRows(), `carehub-anak-${Date.now()}.csv`);
    }
</script>
@endsection