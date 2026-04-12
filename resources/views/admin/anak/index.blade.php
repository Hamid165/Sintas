@extends('layouts.admin')
@section('title', 'Manajemen Anak - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    {{-- Header --}}
    <div class="flex justify-between items-center bg-white p-8 rounded-[2rem] border shadow-sm flex-wrap gap-4">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Database Anak Asuh</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Total: <span id="statTotalAnak">0</span> Anak Terdaftar</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari nama anak..." class="pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl text-xs font-bold text-gray-700 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all w-60 md:w-72">
            </div>
            <button onclick="exportExcel()" class="bg-emerald-600 text-white px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 min-w-max">
                <i data-lucide="file-spreadsheet" size="16"></i> Export
            </button>
            <a href="{{ route('admin.anak.tambah') }}" class="bg-blue-600 text-white px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 min-w-max">
                <i data-lucide="plus" size="16"></i> Tambah Anak
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-[2rem] shadow-sm border overflow-hidden w-full">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-400 text-[10px] uppercase font-black border-b">
                    <tr>
                        <th class="px-6 py-5 w-8">#</th>
                        <th class="px-6 py-5">Nama Lengkap</th>
                        <th class="px-6 py-5">Usia</th>
                        <th class="px-6 py-5">Jenis Kelamin</th>
                        <th class="px-6 py-5">Tempat / Tgl Lahir</th>
                        <th class="px-6 py-5">Riwayat Kesehatan</th>
                        <th class="px-6 py-5">Pendidikan</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="anakTableBody" class="divide-y text-sm">
                    <tr><td colspan="8" class="px-8 py-24 text-center text-gray-400">
                        <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                        <p class="text-xs uppercase tracking-widest font-bold mt-2">Memuat data...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>

        {{-- Pagination Footer --}}
        <div id="paginationBar" class="hidden px-8 py-5 border-t bg-gray-50/50 flex items-center justify-between">
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
            document.getElementById('statTotalAnak').innerText = filteredData.length;
            renderPage(1);
        } catch (e) { console.error(e); }
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
                <td class="px-6 py-4 text-gray-300 font-black text-xs">${start + i + 1}</td>
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
                <td class="px-6 py-4 text-gray-600 text-xs font-medium">${c.tempat_tgl_lahir || '-'}</td>
                <td class="px-6 py-4">
                    <span class="bg-rose-50 text-rose-600 text-[10px] font-bold px-3 py-1.5 rounded-xl border border-rose-100 italic">${c.riwayat_kesehatan || 'Sehat'}</span>
                </td>
                <td class="px-6 py-4 text-xs font-bold text-gray-600">${c.info_pendidikan || '-'}</td>
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

    async function hapusAnak(id) {
        const ok = await showConfirm('Hapus profil anak ini dari database SINTAS?');
        if (!ok) return;
        try {
            await fetch(`/api/anak/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
            showToast('Data anak berhasil dihapus.', 'success');
            loadData();
        } catch(e) { showToast('Gagal menghapus data.', 'error'); }
    }

    function exportExcel() {
        if(filteredData.length === 0) return showToast('Tidak ada data yang bisa diekspor.', 'warning');
        
        const dataToExport = filteredData.map((c, index) => {
            return {
                '#': index + 1,
                'Nama Lengkap': c.nama_lengkap || '-',
                'Usia': (c.usia || '0') + ' Tahun',
                'Jenis Kelamin': c.jenis_kelamin || '-',
                'Tempat / Tgl Lahir': c.tempat_tgl_lahir || '-',
                'Riwayat Kesehatan': c.riwayat_kesehatan || 'Sehat',
                'Pendidikan': c.info_pendidikan || '-'
            };
        });

        const worksheet = XLSX.utils.json_to_sheet(dataToExport);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Data Anak");
        
        XLSX.writeFile(workbook, "data-anak-sintas.xlsx");
    }
</script>
@endsection