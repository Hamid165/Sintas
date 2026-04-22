@extends('layouts.admin')
@section('title', 'Audit Keuangan - CareHub')

@section('content')
<div class="space-y-6 w-full">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Pusat Audit Keuangan</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Validasi Mutasi Kas Berbasis Dokumen Resmi</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openExportAudit()" class="bg-emerald-600 text-white px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i data-lucide="download" size="16"></i> Export
            </button>
            <button onclick="openModalTambahAudit()" class="bg-blue-600 text-white px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" size="16"></i> Audit Baru
            </button>
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
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-blue-600/10 to-indigo-600/10">
            <h4 class="font-black text-sm uppercase tracking-[0.2em] text-slate-800 flex items-center gap-2">
                <i data-lucide="shield-check" size="18" class="text-blue-600"></i>
                Daftar Audit Keuangan
            </h4>
        </div>

        <div class="p-6 border-b border-gray-200 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                    <input type="text" id="searchAudit" placeholder="Cari audit..." class="pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-xl w-full text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-300 transition-all">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap text-xs">
                <thead class="bg-blue-50 text-slate-800 font-black uppercase border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">
                            <button onclick="toggleSortAudit('tanggal')" class="flex items-center gap-1.5 hover:text-blue-600" title="Urutkan">
                                Tanggal Audit
                                <span id="sortIconAudit" class="flex flex-col gap-[2px] opacity-40 hover:opacity-100">
                                    <i data-lucide="chevrons-up-down" size="10"></i>
                                </span>
                            </button>
                        </th>
                        <th class="px-6 py-4">Jenis Audit</th>
                        <th class="px-6 py-4">Kode Dokumen</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-right">Nominal (Rp)</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="auditKeuanganTable" class="divide-y divide-gray-100">
                    <tr><td colspan="8" class="px-8 py-12 text-center text-gray-400">
                        <i data-lucide="loader" class="mx-auto mb-2 animate-spin text-blue-400" size="24"></i>
                        <p class="text-xs font-bold uppercase">Memuat data...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>

        <div id="paginationAudit" class="hidden px-8 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs">
            <p id="paginationInfoAudit" class="text-gray-400 font-bold"></p>
            <div id="paginationBtnsAudit" class="flex items-center gap-2"></div>
        </div>
    </div>
</div>

<!-- Modal Tambah Audit Keuangan -->
<div id="modalTambahAudit" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center">
                    <i data-lucide="plus-circle" size="20"></i>
                </div>
                <h3 class="font-black text-slate-800 uppercase">Audit Baru</h3>
            </div>
            <button onclick="closeModal('modalTambahAudit')" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>

        <form id="formTambahAudit" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Pilih Transaksi Keuangan</label>
                <select name="keuangan_id" id="keuanganSelect" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">- Pilih Transaksi -</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Jenis Audit</label>
                <select name="jenis_audit" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">- Pilih Jenis -</option>
                    <option value="MASUK">Pemasukan</option>
                    <option value="KELUAR">Pengeluaran</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Kode Dokumen (Surat)</label>
                <input type="text" name="kode_dokumen" placeholder="SRT-IN-2026-001 atau SRT-OUT-2026-001" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <p class="text-[10px] text-gray-500 mt-2">Format: SRT-IN-YYYY-NNN (surat masuk) atau SRT-OUT-YYYY-NNN (surat keluar)</p>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan audit" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modalTambahAudit')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all">Simpan Audit</button>
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

<script>
let sortByAudit = 'tanggal';
let sortDirAudit = 'desc';
let currentPageAudit = 1;
let keuanganData = [];

async function loadKeuanganOptions() {
    try {
        const response = await fetch('/api/keuangan');
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
        const response = await fetch(`/api/audit-keuangan?page=${page}&search=${search}&sort=${sortByAudit}&direction=${sortDirAudit}`);
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
            row.className = 'hover:bg-blue-50 transition-colors';
            const isMasuk = item.jenis === 'MASUK';
            const statusColor = isMasuk ? 'text-green-600 bg-green-50' : 'text-red-600 bg-red-50';
            
            row.innerHTML = `
                <td class="px-6 py-4">${((data.current_page - 1) * data.per_page) + index + 1}</td>
                <td class="px-6 py-4 font-bold">${new Date(item.tanggal).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4"><span class="px-3 py-1 rounded-lg ${isMasuk ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'} font-bold text-[10px]">${item.jenis}</span></td>
                <td class="px-6 py-4 font-bold text-blue-600">${item.kode_dokumen}</td>
                <td class="px-6 py-4 text-gray-600 max-w-xs truncate">${item.keterangan || '-'}</td>
                <td class="px-6 py-4 text-right font-bold">${isMasuk ? '+' : '-'} Rp ${parseInt(item.nominal).toLocaleString('id-ID')}</td>
                <td class="px-6 py-4 text-center"><span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 font-bold text-[10px]">TERAUDIT</span></td>
                <td class="px-6 py-4 text-center">
                    <button onclick="deleteAudit(${item.id})" class="text-red-600 hover:text-red-700 font-bold" title="Hapus">
                        <i data-lucide="trash-2" size="16"></i>
                    </button>
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
            document.getElementById('paginationInfoAudit').textContent = `Halaman ${data.current_page} dari ${data.last_page} (Total: ${data.total} audit)`;
            
            const paginationBtns = document.getElementById('paginationBtnsAudit');
            paginationBtns.innerHTML = '';

            if (data.current_page > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                prevBtn.textContent = 'Sebelumnya';
                prevBtn.onclick = () => loadAuditKeuangan(data.current_page - 1);
                paginationBtns.appendChild(prevBtn);
            }

            if (data.current_page < data.last_page) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                nextBtn.textContent = 'Berikutnya';
                nextBtn.onclick = () => loadAuditKeuangan(data.current_page + 1);
                paginationBtns.appendChild(nextBtn);
            }
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
        const response = await fetch(`/api/audit-keuangan/${id}`, { method: 'DELETE' });
        if (response.ok) {
            showSuccess('Audit berhasil dihapus');
            loadAuditKeuangan();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function openModalTambahAudit() {
    loadKeuanganOptions();
    document.getElementById('modalTambahAudit').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openExportAudit() {
    openExportModal('Audit Keuangan', {
        pdf: () => exportAuditPdf(),
        excel: () => exportAuditExcel(),
        csv: () => exportAuditCsv(),
    });
}

async function fetchAllAuditKeuangan() {
    const res = await fetch('/api/audit-keuangan?per_page=9999');
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

function showSuccess(message) {
    document.getElementById('successTitle').textContent = 'Berhasil!';
    document.getElementById('successMessage').textContent = message;
    document.getElementById('modalSuccess').classList.remove('hidden');
    setTimeout(() => closeModal('modalSuccess'), 3000);
}

document.getElementById('formTambahAudit').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/audit-keuangan', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });

        const result = await response.json();
        if (response.ok) {
            closeModal('modalTambahAudit');
            e.target.reset();
            showSuccess('Audit keuangan berhasil ditambahkan');
            loadAuditKeuangan();
        } else {
            alert('Gagal: ' + (result.message || 'Terjadi kesalahan'));
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
@endsection
