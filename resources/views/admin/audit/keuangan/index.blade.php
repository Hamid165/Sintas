@extends('layouts.admin')
@section('title', 'Audit Keuangan - CareHub')

@section('content')
<div class="space-y-6 w-full">
    {{-- Back Link --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.audit') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Menu Audit
        </a>
    </div>

    {{-- Header Bar --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Pusat Audit Keuangan</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Total: <span id="statTotalAudit">0</span> Audit Terverifikasi</p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchAudit" placeholder="Cari audit..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportAudit()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                <a href="{{ route('admin.audit.keuangan.tambah') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Audit Baru
                </a>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border-l-4 border-blue-600">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="activity" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-blue-600 uppercase tracking-widest">Total Audit</p>
            </div>
            <h3 id="statTotalAudit" class="text-3xl font-black text-blue-700">0</h3>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border-l-4 border-green-600">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-green-50 text-green-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-up" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Pemasukan Teraudit</p>
            </div>
            <h3 id="statMasukAudit" class="text-3xl font-black text-green-700">Rp 0</h3>
        </div>
        <div class="bg-white p-8 rounded-[2rem] shadow-sm border-l-4 border-red-600">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                    <i data-lucide="trending-down" size="20"></i>
                </div>
                <p class="text-[10px] font-black text-red-600 uppercase tracking-widest">Pengeluaran Teraudit</p>
            </div>
            <h3 id="statKeluarAudit" class="text-3xl font-black text-red-700">Rp 0</h3>
        </div>
    </div>

    <!-- Audit Table -->
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">
        <div class="p-6 border-b border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
            <h4 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800 flex items-center gap-2">
                <i data-lucide="shield-check" size="16" class="text-blue-600"></i> Daftar Audit Keuangan
            </h4>
            <span id="totalAuditLabel" class="text-[10px] font-black text-gray-400 uppercase">0 audit</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap text-xs">
                <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                    <tr>
                        <th class="px-6 py-5 w-8">No</th>
                        <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="setSortAudit('tanggal')">
                            <div class="flex items-center gap-2">Tanggal Audit <i id="sortIconAudittanggal" class="sort-icon-audit text-gray-300 group-hover:text-blue-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                        </th>
                        <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="setSortAudit('jenis')">
                            <div class="flex items-center gap-2">Jenis Audit <i id="sortIconAuditjenis" class="sort-icon-audit text-gray-300 group-hover:text-blue-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                        </th>
                        <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="setSortAudit('kode_dokumen')">
                            <div class="flex items-center gap-2">Kode Dokumen <i id="sortIconAuditkode_dokumen" class="sort-icon-audit text-gray-300 group-hover:text-blue-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                        </th>
                        <th class="px-6 py-5">Keterangan</th>
                        <th class="px-6 py-5 text-left">Nominal (Rp)</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="auditKeuanganTable" class="divide-y divide-gray-100 text-sm">
                        <tr><td colspan="8" class="px-8 py-24 text-center text-gray-400">
                        <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                        <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>

        <div id="paginationAudit" class="hidden px-8 py-5 border-t border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
            <p id="paginationInfoAudit" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
            <div id="paginationBtnsAudit" class="flex items-center gap-2"></div>
        </div>
    </div>
</div>

<!-- Modal Tambah Audit Keuangan -->
<div id="modalTambahAudit" class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl animate-modal relative">
        <button onclick="closeModal('modalTambahAudit')" class="absolute top-6 right-6 text-slate-300 hover:text-slate-600"><i data-lucide="x" size="22"></i></button>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="shield-check" size="22"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-800">Audit Baru</h3>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Verifikasi Transaksi</p>
            </div>
        </div>

        <form id="formTambahAudit" class="space-y-4">
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Pilih Transaksi Keuangan <span class="text-rose-500">*</span></label>
                <select name="keuangan_id" id="keuanganSelect" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                    <option value="">- Pilih Transaksi -</option>
                    @foreach($transaksiList as $t)
                        <option value="{{ $t->id }}">
                            {{ $t->created_at->format('d/m/Y') }} - {{ $t->jenis_transaksi }} (Rp {{ number_format($t->jumlah_nominal) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Jenis Audit <span class="text-rose-500">*</span></label>
                <select name="jenis_audit" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                    <option value="">- Pilih Jenis -</option>
                    <option value="MASUK">Pemasukan</option>
                    <option value="KELUAR">Pengeluaran</option>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Kode Dokumen Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_dokumen" placeholder="SM-APR-2026-001 atau SK-APR-2026-001" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                <p class="text-[10px] text-gray-400 mt-1 ml-1">Gunakan kode surat yang sudah ada di Rekap Sekretariat</p>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan audit (opsional)" rows="2" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambahAudit')" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-gray-500 font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" id="btnSimpanAudit" class="flex-1 py-3.5 rounded-2xl bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                    <i data-lucide="save" size="14"></i> Simpan Audit
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="modalSuccess" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl max-w-sm w-full text-center p-8">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="check-circle" size="32"></i>
        </div>
        <h3 class="font-black text-lg text-slate-800 mb-2" id="successTitle">Berhasil</h3>
        <p class="text-sm text-gray-600 mb-6" id="successMessage">Data telah berhasil disimpan</p>
        <button onclick="closeModal('modalSuccess')" class="w-full px-6 py-3 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition-all">Tutup</button>
    </div>
</div>

{{-- Export modal is handled by the global openExportModal() in admin layout --}}

@push('scripts')
<script>
let sortByAudit = 'tanggal';
let sortDirAudit = 'desc';
let keuanganData = [];

function setSortAudit(column) {
    if (sortByAudit === column) {
        sortDirAudit = sortDirAudit === 'asc' ? 'desc' : 'asc';
    } else {
        sortByAudit = column;
        sortDirAudit = 'asc';
    }
    loadAuditKeuangan(1);
    updateSortIconsAudit();
}

function updateSortIconsAudit() {
    document.querySelectorAll('.sort-icon-audit').forEach(el => {
        el.setAttribute('data-lucide', 'chevrons-up-down');
        el.classList.remove('text-blue-500');
        el.classList.add('text-gray-300');
    });

    const activeIcon = document.getElementById(`sortIconAudit${sortByAudit}`);
    if(activeIcon) {
        activeIcon.setAttribute('data-lucide', sortDirAudit === 'asc' ? 'chevron-up' : 'chevron-down');
        activeIcon.classList.remove('text-gray-300');
        activeIcon.classList.add('text-blue-500');
    }
    lucide.createIcons();
}

const getAuthHeaders = () => {
    return {
        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };
};

async function loadKeuanganOptions() {
    try {
        const response = await fetch('/api/keuangan-list', { headers: getAuthHeaders() });
        const data = await response.json();
        keuanganData = data.data || data;

        const select = document.getElementById('keuanganSelect');
        select.innerHTML = '<option value="">- Pilih Transaksi -</option>';

        keuanganData.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.keterangan} (${item.jenis_transaksi}) - Rp ${parseInt(item.jumlah_nominal).toLocaleString('id-ID')}`;
            select.appendChild(option);
        });
    } catch (error) {
        console.error('Error loading keuangan:', error);
    }
}

async function loadAuditKeuangan(page = 1) {
    try {
        const search = document.getElementById('searchAudit').value;
        const response = await fetch(`/api/audit-keuangan?page=${page}&search=${search}&sort=${sortByAudit}&direction=${sortDirAudit}`, { headers: getAuthHeaders() });
        const data = await response.json();

        const tbody = document.getElementById('auditKeuanganTable');
        tbody.innerHTML = '';

        if (data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-8 py-12 text-center text-gray-400"><p class="text-xs font-bold">Tidak ada audit</p></td></tr>';
            document.getElementById('paginationAudit').classList.add('hidden');
            updateStats([]);
            return;
        }

    data.data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50/30 transition-colors group';
            const isMasuk = item.jenis === 'MASUK';

            row.innerHTML = `
                <td class="px-6 py-4 text-xs font-black text-gray-400">${((data.current_page - 1) * data.per_page) + index + 1}</td>
                <td class="px-6 py-4 font-bold text-gray-800">${new Date(item.tanggal).toLocaleDateString('id-ID', {day:'numeric',month:'short',year:'numeric'})}</td>
                <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg ${isMasuk ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'} font-black text-[10px] uppercase">${item.jenis}</span></td>
                <td class="px-6 py-4"><span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">${item.kode_dokumen}</span></td>
                <td class="px-6 py-4 text-gray-600 max-w-xs truncate">${item.keterangan || '-'}</td>
                <td class="px-6 py-4 font-bold ${isMasuk ? 'text-emerald-700' : 'text-rose-600'} text-left">${isMasuk ? '+' : '-'} Rp ${parseInt(item.nominal||0).toLocaleString('id-ID')}</td>
                <td class="px-6 py-4 text-center"><span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 font-black text-[10px] uppercase">TERAUDIT</span></td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="deleteAudit(${item.id})" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                            <i data-lucide="trash-2" size="14"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        lucide.createIcons();

        updateStats(data.data);

        // Pagination
        const pagination = document.getElementById('paginationAudit');
        if (data.last_page > 1) {
            pagination.classList.remove('hidden');
            const s = (data.current_page - 1) * data.per_page + 1;
            const e = Math.min(data.current_page * data.per_page, data.total);
            document.getElementById('paginationInfoAudit').textContent = `Menampilkan ${s}–${e} dari ${data.total} data`;

            const paginationBtns = document.getElementById('paginationBtnsAudit');
            paginationBtns.innerHTML = `
                <button onclick="loadAuditKeuangan(${data.current_page - 1})" ${data.current_page <= 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-40 transition-all">
                    <i data-lucide="chevron-left" size="16"></i>
                </button>
                <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-600 text-white font-black text-xs">${data.current_page}</span>
                <button onclick="loadAuditKeuangan(${data.current_page + 1})" ${data.current_page >= data.last_page ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-40 transition-all">
                    <i data-lucide="chevron-right" size="16"></i>
                </button>`;
            lucide.createIcons();
        } else {
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading audit keuangan:', error);
    }
}

function updateStats(auditData) {
    let totalAudit = 0;
    let totalMasuk = 0;
    let totalKeluar = 0;

    auditData.forEach(item => {
        totalAudit++;
        if (item.jenis === 'MASUK') {
            totalMasuk += parseInt(item.nominal) || 0;
        } else {
            totalKeluar += parseInt(item.nominal) || 0;
        }
    });

    document.getElementById('statTotalAudit').textContent = totalAudit;
    document.getElementById('statMasukAudit').textContent = 'Rp ' + totalMasuk.toLocaleString('id-ID');
    document.getElementById('statKeluarAudit').textContent = 'Rp ' + totalKeluar.toLocaleString('id-ID');
}

function toggleSortAudit(field) {
    if (sortByAudit === field) {
        sortDirAudit = sortDirAudit === 'asc' ? 'desc' : 'asc';
    } else {
        sortByAudit = field;
        sortDirAudit = 'desc';
    }
    loadAuditKeuangan(1);
}

async function deleteAudit(id) {
    const ok = await showConfirm(
        'Data audit keuangan ini akan dihapus secara permanen.',
        'Tindakan ini tidak dapat dibatalkan.',
        'Hapus Audit Keuangan',
        'Ya, Hapus'
    );
    if (!ok) return;

    try {
        const response = await fetch(`/api/audit-keuangan/${id}`, { method: 'DELETE', headers: getAuthHeaders() });
        if (response.ok) {
            showToast('Audit keuangan berhasil dihapus.', 'success');
            loadAuditKeuangan();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function openModalTambahAudit() {
    const el = document.getElementById('modalTambahAudit');
    el.classList.remove('hidden');
    el.classList.add('flex');
    lucide.createIcons();
    loadKeuanganOptions();
}

function closeModal(modalId) {
    const el = document.getElementById(modalId);
    el.classList.add('hidden');
    el.classList.remove('flex');
}

function openExportAudit() {
    openExportModal('Audit Keuangan', {
        pdf: () => exportAuditPdf(),
        excel: () => exportAuditExcel(),
        csv: () => exportAuditCsv(),
    });
}

async function fetchAllAuditKeuangan() {
    const res = await fetch('/api/audit-keuangan?per_page=9999', { headers: getAuthHeaders() });
    const json = await res.json();
    return json.data || [];
}

function fmtDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric' });
}

function fmtRupiah(n) {
    return 'Rp ' + parseInt(n||0).toLocaleString('id-ID');
}

async function exportAuditPdf() {
    const rows = await fetchAllAuditKeuangan();
    const now = new Date();
    const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    const timeStr = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });
    buildPdf({
        title: 'Laporan Audit Keuangan',
        module: 'Audit Keuangan',
        columns: ['No','Tanggal','Jenis','Kode Dokumen','Keterangan','Nominal (Rp)','Status Transaksi'],
        rows: rows.map((r,i) => [
            i+1,
            fmtDate(r.tanggal),
            r.jenis,
            r.kode_dokumen,
            r.keterangan || '-',
            (r.jenis === 'MASUK' ? '+' : '-') + ' Rp ' + parseInt(r.nominal||0).toLocaleString('id-ID'),
            r.keuangan_jenis || 'PENGELUARAN',
        ]),
        filename: `audit_keuangan_${now.toISOString().slice(0,19).replace(/[:T]/g,'-')}.pdf`,
    });
}

async function exportAuditExcel() {
    const rows = await fetchAllAuditKeuangan();
    const now = new Date();
    buildExcel({
        title: 'Laporan Audit Keuangan',
        module: 'Audit Keuangan',
        headers: ['No','Tanggal','Jenis Audit','Kode Dokumen','Keterangan','Nominal (Rp)','Jenis Transaksi'],
        rows: rows.map((r,i) => [
            i+1,
            fmtDate(r.tanggal),
            r.jenis,
            r.kode_dokumen,
            r.keterangan || '-',
            parseInt(r.nominal||0),
            r.keuangan_jenis || 'PENGELUARAN',
        ]),
        filename: `audit_keuangan_${now.toISOString().slice(0,10)}.xlsx`,
    });
}

async function exportAuditCsv() {
    const rows = await fetchAllAuditKeuangan();
    buildCsv(
        ['No','Tanggal','Jenis Audit','Kode Dokumen','Keterangan','Nominal (Rp)','Jenis Transaksi'],
        rows.map((r,i) => [
            i+1,
            fmtDate(r.tanggal),
            r.jenis,
            r.kode_dokumen,
            r.keterangan || '-',
            parseInt(r.nominal||0),
            r.keuangan_jenis || 'PENGELUARAN',
        ]),
        `audit_keuangan_${new Date().toISOString().slice(0,10)}.csv`
    );
}



document.getElementById('formTambahAudit').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/audit-keuangan', {
            method: 'POST',
            headers: { 
                ...getAuthHeaders(), 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content 
            },
            body: JSON.stringify(data)
        });

    const result = await response.json();
        if (response.ok) {
            closeModal('modalTambahAudit');
            e.target.reset();
            showToast('Audit keuangan berhasil ditambahkan', 'success');
            loadAuditKeuangan();
        } else {
            const errs = result.errors ? Object.values(result.errors).flat().join(' | ') : (result.message || 'Gagal menyimpan');
            showToast(errs, 'error');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

let searchTimeout;
document.getElementById('searchAudit').addEventListener('input', () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadAuditKeuangan(1), 300);
});

document.addEventListener('DOMContentLoaded', () => {
    loadAuditKeuangan();
});
</script>
@endpush
@endsection
