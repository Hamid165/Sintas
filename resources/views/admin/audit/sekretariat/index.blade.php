@extends('layouts.admin')
@section('title', 'Rekap Kesekretariatan - CareHub')

@section('content')
<div class="space-y-6 w-full">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Rekap Kesekretariatan</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Pengelolaan Administrasi Surat Masuk & Keluar</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openExportSekretariat()" class="bg-emerald-600 text-white px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i data-lucide="download" size="16"></i> Export
            </button>
            <button onclick="openModalTambahSurat('masuk')" class="bg-orange-600 text-white px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-orange-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" size="16"></i> Surat Masuk
            </button>
            <button onclick="openModalTambahSurat('keluar')" class="bg-blue-600 text-white px-4 py-3 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2">
                <i data-lucide="plus" size="16"></i> Surat Keluar
            </button>
        </div>
    </div>

    <!-- Surat Masuk Section -->
    <div class="space-y-4">
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-orange-50/50">
                <h4 class="font-black text-sm uppercase tracking-[0.2em] text-orange-800 flex items-center gap-2">
                    <i data-lucide="inbox" size="18" class="text-orange-600"></i>
                    SURAT MASUK
                </h4>
            </div>

            <div class="p-6 border-b border-gray-200 bg-gray-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                        <input type="text" id="searchMasuk" placeholder="Cari surat masuk..." class="pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-xl w-full text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-orange-300 transition-all">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-xs">
                    <thead class="bg-orange-50 text-orange-800 font-black uppercase border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kode Surat</th>
                            <th class="px-6 py-4">
                                <button onclick="toggleSortMasuk('perihal')" class="flex items-center gap-1.5 hover:text-orange-600" title="Urutkan">
                                    Perihal
                                    <span id="sortIconMasuk" class="flex flex-col gap-[2px] opacity-40 hover:opacity-100">
                                        <i data-lucide="chevrons-up-down" size="10"></i>
                                    </span>
                                </button>
                            </th>
                            <th class="px-6 py-4">Pengirim</th>
                            <th class="px-6 py-4">Tanggal Surat</th>
                            <th class="px-6 py-4">Tanggal Diterima</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratMasukTable" class="divide-y divide-gray-100">
                        <tr><td colspan="8" class="px-8 py-12 text-center text-gray-400">
                            <i data-lucide="loader" class="mx-auto mb-2 animate-spin text-orange-400" size="24"></i>
                            <p class="text-xs font-bold uppercase">Memuat data...</p>
                        </td></tr>
                    </tbody>
                </table>
            </div>

            <div id="paginationMasuk" class="hidden px-8 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs">
                <p id="paginationInfoMasuk" class="text-gray-400 font-bold"></p>
                <div id="paginationBtnsMasuk" class="flex items-center gap-2"></div>
            </div>
        </div>
    </div>

    <!-- Surat Keluar Section -->
    <div class="space-y-4">
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-blue-50/50">
                <h4 class="font-black text-sm uppercase tracking-[0.2em] text-blue-800 flex items-center gap-2">
                    <i data-lucide="send" size="18" class="text-blue-600"></i>
                    SURAT KELUAR
                </h4>
            </div>

            <div class="p-6 border-b border-gray-200 bg-gray-50/50">
                <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <div class="relative flex-1">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                        <input type="text" id="searchKeluar" placeholder="Cari surat keluar..." class="pl-10 pr-4 py-3 bg-gray-50 border-0 rounded-xl w-full text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-300 transition-all">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap text-xs">
                    <thead class="bg-blue-50 text-blue-800 font-black uppercase border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Kode Surat</th>
                            <th class="px-6 py-4">
                                <button onclick="toggleSortKeluar('perihal')" class="flex items-center gap-1.5 hover:text-blue-600" title="Urutkan">
                                    Perihal
                                    <span id="sortIconKeluar" class="flex flex-col gap-[2px] opacity-40 hover:opacity-100">
                                        <i data-lucide="chevrons-up-down" size="10"></i>
                                    </span>
                                </button>
                            </th>
                            <th class="px-6 py-4">Tujuan</th>
                            <th class="px-6 py-4">Tanggal Surat</th>
                            <th class="px-6 py-4">Tanggal Dikirim</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratKeluarTable" class="divide-y divide-gray-100">
                        <tr><td colspan="8" class="px-8 py-12 text-center text-gray-400">
                            <i data-lucide="loader" class="mx-auto mb-2 animate-spin text-blue-400" size="24"></i>
                            <p class="text-xs font-bold uppercase">Memuat data...</p>
                        </td></tr>
                    </tbody>
                </table>
            </div>

            <div id="paginationKeluar" class="hidden px-8 py-4 border-t border-gray-200 bg-gray-50/50 flex items-center justify-between text-xs">
                <p id="paginationInfoKeluar" class="text-gray-400 font-bold"></p>
                <div id="paginationBtnsKeluar" class="flex items-center gap-2"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Surat Masuk -->
<div id="modalTambahMasuk" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-orange-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-orange-600 text-white rounded-xl flex items-center justify-center">
                    <i data-lucide="file-plus" size="20"></i>
                </div>
                <h3 class="font-black text-slate-800 uppercase">Surat Masuk Baru</h3>
            </div>
            <button onclick="closeModal('modalTambahMasuk')" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>

        <form id="formTambahMasuk" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Kode Surat</label>
                <input type="text" name="kode_surat" placeholder="SRT-IN-2026-001" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Perihal</label>
                <input type="text" name="perihal" placeholder="Judul surat masuk" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Pengirim</label>
                <input type="text" name="pengirim" placeholder="Nama organisasi/instansi" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Tanggal Diterima</label>
                    <input type="date" name="tanggal_diterima" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan tambahan" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-300"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modalTambahMasuk')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-orange-600 text-white rounded-xl font-bold text-sm hover:bg-orange-700 transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Tambah Surat Keluar -->
<div id="modalTambahKeluar" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-[2rem] shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between bg-blue-50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-600 text-white rounded-xl flex items-center justify-center">
                    <i data-lucide="file-plus" size="20"></i>
                </div>
                <h3 class="font-black text-slate-800 uppercase">Surat Keluar Baru</h3>
            </div>
            <button onclick="closeModal('modalTambahKeluar')" class="text-gray-400 hover:text-gray-600">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>

        <form id="formTambahKeluar" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Kode Surat</label>
                <input type="text" name="kode_surat" placeholder="SRT-OUT-2026-001" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Perihal</label>
                <input type="text" name="perihal" placeholder="Judul surat keluar" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Tujuan</label>
                <input type="text" name="tujuan" placeholder="Nama organisasi/instansi tujuan" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Tanggal Dikirim</label>
                    <input type="date" name="tanggal_dikirim" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-gray-700 mb-2 uppercase">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan tambahan" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeModal('modalTambahKeluar')" class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all">Simpan</button>
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
let sortByMasuk = 'perihal';
let sortDirMasuk = 'asc';
let sortByKeluar = 'perihal';
let sortDirKeluar = 'asc';
let currentPageMasuk = 1;
let currentPageKeluar = 1;

// Cache semua data surat untuk export
let allSuratMasukData = [];
let allSuratKeluarData = [];

function openModalTambahSurat(jenis) {
    closeModal('modalTambahMasuk');
    closeModal('modalTambahKeluar');
    if (jenis === 'masuk') {
        document.getElementById('modalTambahMasuk').classList.remove('hidden');
    } else {
        document.getElementById('modalTambahKeluar').classList.remove('hidden');
    }
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

function openExportSekretariat() {
    openExportModal('Rekap Kesekretariatan', {
        pdf: () => exportSekretariatPdf(),
        excel: () => exportSekretariatExcel(),
        csv: () => exportSekretariatCsv(),
    });
}

async function fetchAllSuratMasuk() {
    const res = await fetch('/api/surat-masuk?per_page=9999');
    const json = await res.json();
    return json.data || [];
}

async function fetchAllSuratKeluar() {
    const res = await fetch('/api/surat-keluar?per_page=9999');
    const json = await res.json();
    return json.data || [];
}

function fmtDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day:'2-digit', month:'2-digit', year:'numeric' });
}

async function exportSekretariatPdf() {
    const [masuk, keluar] = await Promise.all([fetchAllSuratMasuk(), fetchAllSuratKeluar()]);
    const now = new Date();
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
    const W = doc.internal.pageSize.getWidth();
    const H = doc.internal.pageSize.getHeight();
    const dateStr = now.toLocaleDateString('id-ID', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    const timeStr = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit' });

    function drawHeader() {
        doc.setFillColor(15, 23, 42); doc.rect(0, 0, W, 28, 'F');
        doc.setFillColor(37, 99, 235); doc.rect(0, 28, W, 3, 'F');
        doc.setFillColor(37, 99, 235); doc.roundedRect(10, 6, 16, 16, 3, 3, 'F');
        doc.setTextColor(255,255,255); doc.setFont('helvetica','bold'); doc.setFontSize(9);
        doc.text('CH', 18, 16.5, {align:'center'});
        doc.setFontSize(14); doc.text('Care', 30, 12);
        doc.setTextColor(96,165,250); doc.text('Hub', 30 + doc.getTextWidth('Care'), 12);
        doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(148,163,184);
        doc.text('ADMIN PANEL  ·  LAPORAN RESMI', 30, 18);
        doc.text(`Dicetak: ${dateStr}, ${timeStr}`, W-10, 12, {align:'right'});
        doc.text('Modul: Rekap Kesekretariatan', W-10, 18, {align:'right'});
    }

    function drawFooter() {
        const total = doc.internal.getNumberOfPages();
        for (let p = 1; p <= total; p++) {
            doc.setPage(p);
            doc.setFillColor(15,23,42); doc.rect(0, H-10, W, 10, 'F');
            doc.setFont('helvetica','normal'); doc.setFontSize(6.5); doc.setTextColor(148,163,184);
            doc.text('© CareHub Admin  ·  Dokumen ini digenerate otomatis oleh sistem', 10, H-3.5);
            doc.text(`Halaman ${p} / ${total}`, W-10, H-3.5, {align:'right'});
        }
    }

    // Surat Masuk
    drawHeader();
    doc.setFillColor(248,250,252); doc.rect(0,31,W,16,'F');
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(15,23,42);
    doc.text('REKAP SURAT MASUK', 10, 42);
    doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(100,116,139);
    doc.text(`Total: ${masuk.length} surat`, W-10, 42, {align:'right'});
    doc.autoTable({
        startY: 50,
        head: [['No','Kode Surat','Perihal','Pengirim','Tgl Surat','Tgl Diterima','Keterangan']],
        body: masuk.map((r,i) => [i+1, r.kode_surat, r.perihal, r.pengirim, fmtDate(r.tanggal_surat), fmtDate(r.tanggal_diterima), r.keterangan||'-']),
        styles: { font:'helvetica', fontSize:8, cellPadding:{top:4,bottom:4,left:5,right:5}, textColor:[30,41,59], lineColor:[226,232,240], lineWidth:0.3 },
        headStyles: { fillColor:[15,23,42], textColor:[255,255,255], fontStyle:'bold', fontSize:7.5 },
        alternateRowStyles: { fillColor:[248,250,252] },
        margin: { left:10, right:10 },
    });

    // Surat Keluar – new page
    doc.addPage();
    drawHeader();
    doc.setFillColor(248,250,252); doc.rect(0,31,W,16,'F');
    doc.setFont('helvetica','bold'); doc.setFontSize(13); doc.setTextColor(15,23,42);
    doc.text('REKAP SURAT KELUAR', 10, 42);
    doc.setFont('helvetica','normal'); doc.setFontSize(7); doc.setTextColor(100,116,139);
    doc.text(`Total: ${keluar.length} surat`, W-10, 42, {align:'right'});
    doc.autoTable({
        startY: 50,
        head: [['No','Kode Surat','Perihal','Tujuan','Tgl Surat','Tgl Dikirim','Keterangan']],
        body: keluar.map((r,i) => [i+1, r.kode_surat, r.perihal, r.tujuan, fmtDate(r.tanggal_surat), fmtDate(r.tanggal_dikirim), r.keterangan||'-']),
        styles: { font:'helvetica', fontSize:8, cellPadding:{top:4,bottom:4,left:5,right:5}, textColor:[30,41,59], lineColor:[226,232,240], lineWidth:0.3 },
        headStyles: { fillColor:[15,23,42], textColor:[255,255,255], fontStyle:'bold', fontSize:7.5 },
        alternateRowStyles: { fillColor:[248,250,252] },
        margin: { left:10, right:10 },
    });

    drawFooter();
    const ts = now.toISOString().slice(0,19).replace(/[:T]/g,'-');
    doc.save(`rekap_kesekretariatan_${ts}.pdf`);
}

async function exportSekretariatExcel() {
    const [masuk, keluar] = await Promise.all([fetchAllSuratMasuk(), fetchAllSuratKeluar()]);
    const now = new Date();
    const dateStr = now.toLocaleString('id-ID');
    const wb = XLSX.utils.book_new();

    // Sheet Surat Masuk
    const masukRows = [
        ['CareHub Admin – Rekap Kesekretariatan'],
        [`Dicetak: ${dateStr}`],
        [],
        ['No','Kode Surat','Perihal','Pengirim','Tanggal Surat','Tanggal Diterima','Keterangan'],
        ...masuk.map((r,i) => [i+1, r.kode_surat, r.perihal, r.pengirim, fmtDate(r.tanggal_surat), fmtDate(r.tanggal_diterima), r.keterangan||'-'])
    ];
    const wsMasuk = XLSX.utils.aoa_to_sheet(masukRows);
    wsMasuk['!cols'] = [6,18,30,20,14,14,25].map(w=>({wch:w}));
    wsMasuk['!merges'] = [{s:{r:0,c:0},e:{r:0,c:6}}];
    XLSX.utils.book_append_sheet(wb, wsMasuk, 'Surat Masuk');

    // Sheet Surat Keluar
    const keluarRows = [
        ['CareHub Admin – Rekap Kesekretariatan'],
        [`Dicetak: ${dateStr}`],
        [],
        ['No','Kode Surat','Perihal','Tujuan','Tanggal Surat','Tanggal Dikirim','Keterangan'],
        ...keluar.map((r,i) => [i+1, r.kode_surat, r.perihal, r.tujuan, fmtDate(r.tanggal_surat), fmtDate(r.tanggal_dikirim), r.keterangan||'-'])
    ];
    const wsKeluar = XLSX.utils.aoa_to_sheet(keluarRows);
    wsKeluar['!cols'] = [6,18,30,20,14,14,25].map(w=>({wch:w}));
    wsKeluar['!merges'] = [{s:{r:0,c:0},e:{r:0,c:6}}];
    XLSX.utils.book_append_sheet(wb, wsKeluar, 'Surat Keluar');

    const ts = now.toISOString().slice(0,10);
    XLSX.writeFile(wb, `rekap_kesekretariatan_${ts}.xlsx`);
}

async function exportSekretariatCsv() {
    const [masuk, keluar] = await Promise.all([fetchAllSuratMasuk(), fetchAllSuratKeluar()]);
    const hdMasuk = ['No','Kode Surat','Perihal','Pengirim','Tanggal Surat','Tanggal Diterima','Keterangan'];
    const rowsMasuk = masuk.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.pengirim,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_diterima),r.keterangan||'-']);
    const hdKeluar = ['No','Kode Surat','Perihal','Tujuan','Tanggal Surat','Tanggal Dikirim','Keterangan'];
    const rowsKeluar = keluar.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.tujuan,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_dikirim),r.keterangan||'-']);

    const esc = v => { const s=String(v??''); return s.includes(',')||s.includes('"')||s.includes('\n')?`"${s.replace(/"/g,'""')}"`:s; };
    const lines = [
        '=== SURAT MASUK ===',
        hdMasuk.map(esc).join(','),
        ...rowsMasuk.map(r=>r.map(esc).join(',')),
        '',
        '=== SURAT KELUAR ===',
        hdKeluar.map(esc).join(','),
        ...rowsKeluar.map(r=>r.map(esc).join(','))
    ];
    const blob = new Blob(['\uFEFF' + lines.join('\n')], {type:'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href=url; a.download=`rekap_kesekretariatan_${new Date().toISOString().slice(0,10)}.csv`; a.click();
    setTimeout(()=>URL.revokeObjectURL(url),1000);
}

async function loadSuratMasuk(page = 1) {
    try {
        const search = document.getElementById('searchMasuk').value;
        const response = await fetch(`/api/surat-masuk?page=${page}&search=${search}&sort=${sortByMasuk}&direction=${sortDirMasuk}`);
        const data = await response.json();

        const tbody = document.getElementById('suratMasukTable');
        tbody.innerHTML = '';

        if (data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-8 py-12 text-center text-gray-400"><p class="text-xs font-bold">Tidak ada data</p></td></tr>';
            document.getElementById('paginationMasuk').classList.add('hidden');
            return;
        }

        data.data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-orange-50 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4">${((data.current_page - 1) * data.per_page) + index + 1}</td>
                <td class="px-6 py-4 font-bold text-orange-600">${item.kode_surat}</td>
                <td class="px-6 py-4">${item.perihal}</td>
                <td class="px-6 py-4 text-gray-600">${item.pengirim}</td>
                <td class="px-6 py-4">${new Date(item.tanggal_surat).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4">${new Date(item.tanggal_diterima).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                    <button onclick="deleteItem('surat-masuk', ${item.id})" class="text-red-600 hover:text-red-700 font-bold" title="Hapus">
                        <i data-lucide="trash-2" size="16"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        lucide.createIcons();

        // Pagination
        const pagination = document.getElementById('paginationMasuk');
        if (data.last_page > 1) {
            pagination.classList.remove('hidden');
            document.getElementById('paginationInfoMasuk').textContent = `Halaman ${data.current_page} dari ${data.last_page} (Total: ${data.total} data)`;
            
            const paginationBtns = document.getElementById('paginationBtnsMasuk');
            paginationBtns.innerHTML = '';

            if (data.current_page > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                prevBtn.textContent = 'Sebelumnya';
                prevBtn.onclick = () => loadSuratMasuk(data.current_page - 1);
                paginationBtns.appendChild(prevBtn);
            }

            if (data.current_page < data.last_page) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                nextBtn.textContent = 'Berikutnya';
                nextBtn.onclick = () => loadSuratMasuk(data.current_page + 1);
                paginationBtns.appendChild(nextBtn);
            }
        } else {
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading surat masuk:', error);
    }
}

async function loadSuratKeluar(page = 1) {
    try {
        const search = document.getElementById('searchKeluar').value;
        const response = await fetch(`/api/surat-keluar?page=${page}&search=${search}&sort=${sortByKeluar}&direction=${sortDirKeluar}`);
        const data = await response.json();

        const tbody = document.getElementById('suratKeluarTable');
        tbody.innerHTML = '';

        if (data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="px-8 py-12 text-center text-gray-400"><p class="text-xs font-bold">Tidak ada data</p></td></tr>';
            document.getElementById('paginationKeluar').classList.add('hidden');
            return;
        }

        data.data.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4">${((data.current_page - 1) * data.per_page) + index + 1}</td>
                <td class="px-6 py-4 font-bold text-blue-600">${item.kode_surat}</td>
                <td class="px-6 py-4">${item.perihal}</td>
                <td class="px-6 py-4 text-gray-600">${item.tujuan}</td>
                <td class="px-6 py-4">${new Date(item.tanggal_surat).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4">${new Date(item.tanggal_dikirim).toLocaleDateString('id-ID')}</td>
                <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                    <button onclick="deleteItem('surat-keluar', ${item.id})" class="text-red-600 hover:text-red-700 font-bold" title="Hapus">
                        <i data-lucide="trash-2" size="16"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });
        lucide.createIcons();

        // Pagination
        const pagination = document.getElementById('paginationKeluar');
        if (data.last_page > 1) {
            pagination.classList.remove('hidden');
            document.getElementById('paginationInfoKeluar').textContent = `Halaman ${data.current_page} dari ${data.last_page} (Total: ${data.total} data)`;
            
            const paginationBtns = document.getElementById('paginationBtnsKeluar');
            paginationBtns.innerHTML = '';

            if (data.current_page > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                prevBtn.textContent = 'Sebelumnya';
                prevBtn.onclick = () => loadSuratKeluar(data.current_page - 1);
                paginationBtns.appendChild(prevBtn);
            }

            if (data.current_page < data.last_page) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'px-3 py-1 border border-gray-200 rounded-lg hover:bg-gray-100';
                nextBtn.textContent = 'Berikutnya';
                nextBtn.onclick = () => loadSuratKeluar(data.current_page + 1);
                paginationBtns.appendChild(nextBtn);
            }
        } else {
            pagination.classList.add('hidden');
        }
    } catch (error) {
        console.error('Error loading surat keluar:', error);
    }
}

function toggleSortMasuk(field) {
    if (sortByMasuk === field) {
        sortDirMasuk = sortDirMasuk === 'asc' ? 'desc' : 'asc';
    } else {
        sortByMasuk = field;
        sortDirMasuk = 'asc';
    }
    loadSuratMasuk(1);
}

function toggleSortKeluar(field) {
    if (sortByKeluar === field) {
        sortDirKeluar = sortDirKeluar === 'asc' ? 'desc' : 'asc';
    } else {
        sortByKeluar = field;
        sortDirKeluar = 'asc';
    }
    loadSuratKeluar(1);
}

async function deleteItem(type, id) {
    const label = type === 'surat-masuk' ? 'Surat Masuk' : 'Surat Keluar';
    const ok = await showConfirm(
        `Data ${label} ini akan dihapus secara permanen.`,
        'Tindakan ini tidak dapat dibatalkan.',
        `Hapus ${label}`,
        'Ya, Hapus'
    );
    if (!ok) return;

    try {
        const response = await fetch(`/api/${type}/${id}`, { method: 'DELETE' });
        const data = await response.json();

        if (response.ok) {
            showSuccess('Berhasil dihapus');
            if (type === 'surat-masuk') loadSuratMasuk();
            else loadSuratKeluar();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function showSuccess(message) {
    document.getElementById('successTitle').textContent = 'Berhasil!';
    document.getElementById('successMessage').textContent = message;
    document.getElementById('modalSuccess').classList.remove('hidden');
    setTimeout(() => closeModal('modalSuccess'), 3000);
}

// Form submissions
document.getElementById('formTambahMasuk').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/surat-masuk', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            closeModal('modalTambahMasuk');
            e.target.reset();
            showSuccess('Surat masuk berhasil ditambahkan');
            loadSuratMasuk();
        } else {
            alert('Gagal menyimpan data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

document.getElementById('formTambahKeluar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData);

    try {
        const response = await fetch('/api/surat-keluar', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            closeModal('modalTambahKeluar');
            e.target.reset();
            showSuccess('Surat keluar berhasil ditambahkan');
            loadSuratKeluar();
        } else {
            alert('Gagal menyimpan data');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});

// Search with debounce
let masukSearchTimeout, keluarSearchTimeout;

document.getElementById('searchMasuk').addEventListener('input', () => {
    clearTimeout(masukSearchTimeout);
    masukSearchTimeout = setTimeout(() => loadSuratMasuk(1), 300);
});

document.getElementById('searchKeluar').addEventListener('input', () => {
    clearTimeout(keluarSearchTimeout);
    keluarSearchTimeout = setTimeout(() => loadSuratKeluar(1), 300);
});

// Load data on page load
document.addEventListener('DOMContentLoaded', () => {
    loadSuratMasuk();
    loadSuratKeluar();
});
</script>
@endsection
