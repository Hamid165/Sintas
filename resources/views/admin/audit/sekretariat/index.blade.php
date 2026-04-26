@extends('layouts.admin')
@section('title', 'Audit Sekretariat - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- ── Header Bar ────────────────────────────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Rekap Kesekretariatan</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">
                Surat Masuk: <span id="statMasuk" class="text-slate-700">0</span> &nbsp;·&nbsp; Surat Keluar: <span id="statKeluar" class="text-slate-700">0</span>
            </p>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">
            <div class="relative w-full sm:w-auto">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400" size="16"></i>
                <input type="text" id="searchInput" placeholder="Cari surat..." class="pl-10 pr-4 py-3 md:py-3.5 bg-gray-50 border-0 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-bold text-gray-700 outline-none focus:ring-2 focus:ring-blue-200 transition-all w-full sm:w-60 md:w-72">
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button onclick="openExportSekretariat()" class="flex-1 sm:flex-none justify-center bg-emerald-600 text-white px-4 py-3 md:px-6 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-emerald-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="file-spreadsheet" size="16"></i> Export
                </button>
                <a href="{{ route('admin.audit.sekretariat.tambah-masuk') }}" class="flex-1 sm:flex-none justify-center bg-orange-600 text-white px-4 py-3 md:px-5 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-orange-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Surat Masuk
                </a>
                <a href="{{ route('admin.audit.sekretariat.tambah-keluar') }}" class="flex-1 sm:flex-none justify-center bg-blue-600 text-white px-4 py-3 md:px-5 md:py-3.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-xl hover:bg-blue-700 transition-all flex items-center gap-2 whitespace-nowrap">
                    <i data-lucide="plus" size="16"></i> Surat Keluar
                </a>
            </div>
        </div>
    </div>

    {{-- ── Segmented Tab (full width, 50/50) ─────────────────────────────── --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">
        <div class="grid grid-cols-2">
            <button id="tabMasukBtn" onclick="switchTab('masuk')"
                class="flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-orange-500 text-orange-600 bg-orange-50 transition-all">
                <i data-lucide="inbox" size="16"></i> Surat Masuk
                <span id="badgeMasuk" class="bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full">0</span>
            </button>
            <button id="tabKeluarBtn" onclick="switchTab('keluar')"
                class="flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 bg-gray-50 transition-all">
                <i data-lucide="send" size="16"></i> Surat Keluar
                <span id="badgeKeluar" class="bg-gray-300 text-white text-[9px] font-black px-2 py-0.5 rounded-full">0</span>
            </button>
        </div>
    </div>

    {{-- ── Tabel Panel (kartu terpisah) ───────────────────────────────── --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden w-full">

        {{-- ── Surat Masuk Panel ─────────────────────────────────────────── --}}
        <div id="panelMasuk">
            <div class="p-6 border-b border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
                <h4 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800 flex items-center gap-2">
                    <i data-lucide="inbox" size="16" class="text-orange-500"></i> Daftar Surat Masuk
                </h4>
                <span id="totalMasukLabel" class="text-[10px] font-black text-gray-400 uppercase">0 surat</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                        <tr>
                            <th class="px-6 py-5 w-8">No</th>
                            <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="applySortMasuk('kode_surat')">
                                <div class="flex items-center gap-2">Kode Surat <i id="sortIconMasukkode_surat" class="sort-icon-masuk text-gray-300 group-hover:text-orange-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                            </th>
                            <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="applySortMasuk('perihal')">
                                <div class="flex items-center gap-2">Perihal <i id="sortIconMasukperihal" class="sort-icon-masuk text-gray-300 group-hover:text-orange-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                            </th>
                            <th class="px-6 py-5">Pengirim</th>
                            <th class="px-6 py-5">Tgl Surat</th>
                            <th class="px-6 py-5">Tgl Diterima</th>
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratMasukTable" class="divide-y divide-gray-100 text-sm">
                        <tr><td colspan="7" class="px-8 py-24 text-center text-gray-400">
                            <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-orange-400" size="28"></i>
                            <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
                        </td></tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationMasuk" class="hidden px-8 py-5 border-t border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
                <p id="paginationInfoMasuk" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
                <div id="paginationBtnsMasuk" class="flex items-center gap-2"></div>
            </div>
        </div>

        {{-- ── Surat Keluar Panel ─────────────────────────────────────────── --}}
        <div id="panelKeluar" class="hidden">
            <div class="p-6 border-b border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
                <h4 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800 flex items-center gap-2">
                    <i data-lucide="send" size="16" class="text-blue-500"></i> Daftar Surat Keluar
                </h4>
                <span id="totalKeluarLabel" class="text-[10px] font-black text-gray-400 uppercase">0 surat</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                        <tr>
                            <th class="px-6 py-5 w-8">No</th>
                            <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="applySortKeluar('kode_surat')">
                                <div class="flex items-center gap-2">Kode Surat <i id="sortIconKeluarkode_surat" class="sort-icon-keluar text-gray-300 group-hover:text-blue-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                            </th>
                            <th class="px-6 py-5 cursor-pointer hover:bg-gray-100 transition-colors group" onclick="applySortKeluar('perihal')">
                                <div class="flex items-center gap-2">Perihal <i id="sortIconKeluarperihal" class="sort-icon-keluar text-gray-300 group-hover:text-blue-400 transition-colors" data-lucide="chevrons-up-down" size="14"></i></div>
                            </th>
                            <th class="px-6 py-5">Tujuan</th>
                            <th class="px-6 py-5">Tgl Surat</th>
                            <th class="px-6 py-5">Tgl Dikirim</th>
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="suratKeluarTable" class="divide-y divide-gray-100 text-sm">
                        <tr><td colspan="7" class="px-8 py-24 text-center text-gray-400">
                            <i data-lucide="loader" class="mx-auto mb-3 animate-spin text-blue-400" size="28"></i>
                            <p class="text-xs font-bold uppercase tracking-widest mt-2">Memuat data...</p>
                        </td></tr>
                    </tbody>
                </table>
            </div>
            <div id="paginationKeluar" class="hidden px-8 py-5 border-t border-[#D1D5DC] bg-gray-50/50 flex items-center justify-between">
                <p id="paginationInfoKeluar" class="text-[11px] text-gray-400 font-bold uppercase tracking-widest"></p>
                <div id="paginationBtnsKeluar" class="flex items-center gap-2"></div>
            </div>
        </div>
    </div>
</div>

{{-- ── Modal Surat Masuk ─────────────────────────────────────────────────── --}}
<div id="modalTambahMasuk" class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl animate-modal relative">
        <button onclick="closeModal('modalTambahMasuk')" class="absolute top-6 right-6 text-slate-300 hover:text-slate-600"><i data-lucide="x" size="22"></i></button>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="inbox" size="22"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-800">Surat Masuk Baru</h3>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tambah ke Rekap</p>
            </div>
        </div>
        <form id="formTambahMasuk" class="space-y-4">
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Kode Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_surat" placeholder="Contoh: SM-APR-2026-001" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm font-bold outline-none">
                <p class="text-[10px] text-gray-400 mt-1 ml-1">Harus unik. Gunakan format: SM-BLN-TAHUN-URUTAN</p>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Perihal <span class="text-rose-500">*</span></label>
                <input type="text" name="perihal" placeholder="Topik/judul surat" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Pengirim <span class="text-rose-500">*</span></label>
                <input type="text" name="pengirim" placeholder="Nama instansi / organisasi" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm font-bold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_surat" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm font-bold outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Tanggal Diterima <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_diterima" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm font-bold outline-none">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan tambahan (opsional)" rows="2" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-orange-400 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambahMasuk')" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-gray-500 font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" id="btnSimpanMasuk" class="flex-1 py-3.5 rounded-2xl bg-orange-600 text-white font-black text-[10px] uppercase tracking-widest hover:bg-orange-700 transition-all shadow-lg shadow-orange-100 flex items-center justify-center gap-2">
                    <i data-lucide="save" size="14"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal Surat Keluar ─────────────────────────────────────────────────── --}}
<div id="modalTambahKeluar" class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl animate-modal relative">
        <button onclick="closeModal('modalTambahKeluar')" class="absolute top-6 right-6 text-slate-300 hover:text-slate-600"><i data-lucide="x" size="22"></i></button>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <i data-lucide="send" size="22"></i>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-800">Surat Keluar Baru</h3>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Tambah ke Rekap</p>
            </div>
        </div>
        <form id="formTambahKeluar" class="space-y-4">
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Kode Surat <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_surat" placeholder="Contoh: SK-APR-2026-001" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                <p class="text-[10px] text-gray-400 mt-1 ml-1">Harus unik. Gunakan format: SK-BLN-TAHUN-URUTAN</p>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Perihal <span class="text-rose-500">*</span></label>
                <input type="text" name="perihal" placeholder="Topik/judul surat" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Tujuan <span class="text-rose-500">*</span></label>
                <input type="text" name="tujuan" placeholder="Nama instansi / organisasi tujuan" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_surat" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Tanggal Dikirim <span class="text-rose-500">*</span></label>
                    <input type="date" name="tanggal_dikirim" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm font-bold outline-none">
                </div>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1 block mb-1">Keterangan</label>
                <textarea name="keterangan" placeholder="Catatan tambahan (opsional)" rows="2" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-400 text-sm outline-none resize-none"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambahKeluar')" class="flex-1 py-3.5 rounded-2xl border-2 border-gray-200 text-gray-500 font-black text-[10px] uppercase tracking-widest hover:bg-gray-50 transition-all">Batal</button>
                <button type="submit" id="btnSimpanKeluar" class="flex-1 py-3.5 rounded-2xl bg-blue-600 text-white font-black text-[10px] uppercase tracking-widest hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center justify-center gap-2">
                    <i data-lucide="save" size="14"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const PER_PAGE = 10;
let allMasuk = [], allKeluar = [], filteredMasuk = [], filteredKeluar = [];
let activeTab = 'masuk';

let sortMasukKey = '', sortMasukAsc = true;
let sortKeluarKey = '', sortKeluarAsc = true;

function applySortMasuk(key) {
    if(sortMasukKey === key) sortMasukAsc = !sortMasukAsc;
    else { sortMasukKey = key; sortMasukAsc = true; }
    
    filteredMasuk.sort((a, b) => {
        let valA = (a[key] || '').toLowerCase();
        let valB = (b[key] || '').toLowerCase();
        if(valA < valB) return sortMasukAsc ? -1 : 1;
        if(valA > valB) return sortMasukAsc ? 1 : -1;
        return 0;
    });
    renderMasuk(1);
    updateSortIcons('Masuk');
}

function applySortKeluar(key) {
    if(sortKeluarKey === key) sortKeluarAsc = !sortKeluarAsc;
    else { sortKeluarKey = key; sortKeluarAsc = true; }
    
    filteredKeluar.sort((a, b) => {
        let valA = (a[key] || '').toLowerCase();
        let valB = (b[key] || '').toLowerCase();
        if(valA < valB) return sortKeluarAsc ? -1 : 1;
        if(valA > valB) return sortKeluarAsc ? 1 : -1;
        return 0;
    });
    renderKeluar(1);
    updateSortIcons('Keluar');
}

function updateSortIcons(type) {
    const key = type === 'Masuk' ? sortMasukKey : sortKeluarKey;
    const asc = type === 'Masuk' ? sortMasukAsc : sortKeluarAsc;
    const color = type === 'Masuk' ? 'text-orange-500' : 'text-blue-500';
    
    document.querySelectorAll(`.sort-icon-${type.toLowerCase()}`).forEach(el => {
        el.setAttribute('data-lucide', 'chevrons-up-down');
        el.classList.remove('text-orange-500', 'text-blue-500');
        el.classList.add('text-gray-300');
    });

    if(key) {
        const activeIcon = document.getElementById(`sortIcon${type}${key}`);
        if(activeIcon) {
            activeIcon.setAttribute('data-lucide', asc ? 'chevron-up' : 'chevron-down');
            activeIcon.classList.remove('text-gray-300');
            activeIcon.classList.add(color);
        }
    }
    lucide.createIcons();
}

// switchTab dihapus, kedua tabel sekarang selalu tampil berdampingan

// ── Tab Switch (Segmented Control) ─────────────────────────────────────────
function switchTab(tab) {
    activeTab = tab;
    document.getElementById('panelMasuk').classList.toggle('hidden', tab !== 'masuk');
    document.getElementById('panelKeluar').classList.toggle('hidden', tab !== 'keluar');

    // Active tab = filled color, inactive = ghost
    document.getElementById('tabMasukBtn').className = tab === 'masuk'
        ? 'flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-orange-500 text-orange-600 bg-orange-50 transition-all'
        : 'flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 bg-gray-50 transition-all';
    document.getElementById('tabKeluarBtn').className = tab === 'keluar'
        ? 'flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-blue-500 text-blue-600 bg-blue-50 transition-all'
        : 'flex items-center justify-center gap-2 py-4 text-[11px] font-black uppercase tracking-widest border-b-2 border-transparent text-slate-400 hover:text-slate-600 bg-gray-50 transition-all';

    // Badge color sync
    document.getElementById('badgeMasuk').className = tab === 'masuk'
        ? 'bg-orange-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full'
        : 'bg-gray-300 text-white text-[9px] font-black px-2 py-0.5 rounded-full';
    document.getElementById('badgeKeluar').className = tab === 'keluar'
        ? 'bg-blue-500 text-white text-[9px] font-black px-2 py-0.5 rounded-full'
        : 'bg-gray-300 text-white text-[9px] font-black px-2 py-0.5 rounded-full';

    lucide.createIcons();
}

function openModalSurat(jenis) {
    const id = jenis === 'masuk' ? 'modalTambahMasuk' : 'modalTambahKeluar';
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
    lucide.createIcons();
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
}

const getAuthHeaders = () => {
    return {
        'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };
};

// ── Load Data ───────────────────────────────────────────────────────────────
async function loadSuratMasuk() {
    try {
        const res = await fetch('/api/surat-masuk?per_page=9999', { headers: getAuthHeaders() });
        const json = await res.json();
        allMasuk = json.data || [];
        filteredMasuk = [...allMasuk];
        document.getElementById('statMasuk').textContent = allMasuk.length;
        document.getElementById('badgeMasuk').textContent = allMasuk.length;
        renderMasuk(1);
    } catch(e) { console.error(e); }
}

async function loadSuratKeluar() {
    try {
        const res = await fetch('/api/surat-keluar?per_page=9999', { headers: getAuthHeaders() });
        const json = await res.json();
        allKeluar = json.data || [];
        filteredKeluar = [...allKeluar];
        document.getElementById('statKeluar').textContent = allKeluar.length;
        document.getElementById('badgeKeluar').textContent = allKeluar.length;
        renderKeluar(1);
    } catch(e) { console.error(e); }
}

// ── Render Tables ───────────────────────────────────────────────────────────
function renderMasuk(page) {
    const start = (page - 1) * PER_PAGE;
    const tbody = document.getElementById('suratMasukTable');
    document.getElementById('totalMasukLabel').textContent = filteredMasuk.length + ' surat';

    if (filteredMasuk.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-8 py-20 text-center text-gray-400">
            <i data-lucide="inbox" class="mx-auto mb-3 text-gray-200" size="48"></i>
            <p class="text-xs font-bold uppercase tracking-widest">Belum ada surat masuk</p>
        </td></tr>`;
        lucide.createIcons();
        document.getElementById('paginationMasuk').classList.add('hidden');
        return;
    }

    tbody.innerHTML = filteredMasuk.slice(start, start + PER_PAGE).map((item, i) => `
        <tr class="hover:bg-orange-50/30 transition-colors">
            <td class="px-6 py-4 text-xs font-black text-gray-400">${start + i + 1}</td>
            <td class="px-6 py-4"><span class="bg-orange-50 text-orange-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">${item.kode_surat}</span></td>
            <td class="px-6 py-4 font-bold text-gray-800">${item.perihal}</td>
            <td class="px-6 py-4 text-gray-500">${item.pengirim}</td>
            <td class="px-6 py-4 text-gray-500">${fmtDate(item.tanggal_surat)}</td>
            <td class="px-6 py-4 text-gray-500">${fmtDate(item.tanggal_diterima)}</td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                    <a href="/admin/audit/sekretariat/edit-masuk?id=${item.id}" class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                        <i data-lucide="pencil" size="14"></i>
                    </a>
                    <button onclick="hapusSurat('masuk', ${item.id})" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i data-lucide="trash-2" size="14"></i>
                    </button>
                </div>
            </td>
        </tr>`).join('');

    lucide.createIcons();
    renderPagination('Masuk', filteredMasuk.length, page, renderMasuk);
}

function renderKeluar(page) {
    const start = (page - 1) * PER_PAGE;
    const tbody = document.getElementById('suratKeluarTable');
    document.getElementById('totalKeluarLabel').textContent = filteredKeluar.length + ' surat';

    if (filteredKeluar.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-8 py-20 text-center text-gray-400">
            <i data-lucide="send" class="mx-auto mb-3 text-gray-200" size="48"></i>
            <p class="text-xs font-bold uppercase tracking-widest">Belum ada surat keluar</p>
        </td></tr>`;
        lucide.createIcons();
        document.getElementById('paginationKeluar').classList.add('hidden');
        return;
    }

    tbody.innerHTML = filteredKeluar.slice(start, start + PER_PAGE).map((item, i) => `
        <tr class="hover:bg-blue-50/30 transition-colors">
            <td class="px-6 py-4 text-xs font-black text-gray-400">${start + i + 1}</td>
            <td class="px-6 py-4"><span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest">${item.kode_surat}</span></td>
            <td class="px-6 py-4 font-bold text-gray-800">${item.perihal}</td>
            <td class="px-6 py-4 text-gray-500">${item.tujuan}</td>
            <td class="px-6 py-4 text-gray-500">${fmtDate(item.tanggal_surat)}</td>
            <td class="px-6 py-4 text-gray-500">${fmtDate(item.tanggal_dikirim)}</td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                    <a href="/admin/audit/sekretariat/edit-keluar?id=${item.id}" class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                        <i data-lucide="pencil" size="14"></i>
                    </a>
                    <button onclick="hapusSurat('keluar', ${item.id})" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i data-lucide="trash-2" size="14"></i>
                    </button>
                </div>
            </td>
        </tr>`).join('');

    lucide.createIcons();
    renderPagination('Keluar', filteredKeluar.length, page, renderKeluar);
}

// ── Pagination ──────────────────────────────────────────────────────────────
function renderPagination(which, total, page, renderFn) {
    const totalPages = Math.ceil(total / PER_PAGE);
    const bar = document.getElementById('pagination' + which);
    const info = document.getElementById('paginationInfo' + which);
    const btns = document.getElementById('paginationBtns' + which);
    if (totalPages <= 1) { bar.classList.add('hidden'); return; }
    bar.classList.remove('hidden');
    const s = (page - 1) * PER_PAGE + 1, e = Math.min(page * PER_PAGE, total);
    info.textContent = `Menampilkan ${s}–${e} dari ${total} data`;
    btns.innerHTML = `
        <button onclick="${renderFn.name}(${page - 1})" ${page <= 1 ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-40 transition-all">
            <i data-lucide="chevron-left" size="16"></i>
        </button>
        <span class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-600 text-white font-black text-xs">${page}</span>
        <button onclick="${renderFn.name}(${page + 1})" ${page >= totalPages ? 'disabled' : ''} class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 text-gray-400 hover:bg-gray-50 disabled:opacity-40 transition-all">
            <i data-lucide="chevron-right" size="16"></i>
        </button>`;
    lucide.createIcons();
}

// ── Delete ──────────────────────────────────────────────────────────────────
async function hapusSurat(jenis, id) {
    const ok = await showConfirm(`Surat ${jenis} ini akan dihapus secara permanen.`, 'Tindakan ini tidak dapat dibatalkan.', `Hapus Surat ${jenis.charAt(0).toUpperCase()+jenis.slice(1)}`, 'Ya, Hapus');
    if (!ok) return;
    const endpoint = jenis === 'masuk' ? `/api/surat-masuk/${id}` : `/api/surat-keluar/${id}`;
    const res = await fetch(endpoint, { 
        method: 'DELETE', 
        headers: getAuthHeaders()
    });
    if (res.ok) {
        showToast(`Surat ${jenis} berhasil dihapus`, 'success');
        jenis === 'masuk' ? loadSuratMasuk() : loadSuratKeluar();
    } else {
        showToast('Gagal menghapus data', 'error');
    }
}

// ── Form Submissions ────────────────────────────────────────────────────────
document.getElementById('formTambahMasuk').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    const btn = document.getElementById('btnSimpanMasuk');
    btn.disabled = true; btn.innerHTML = '<i data-lucide="loader" size="14" class="animate-spin"></i> Menyimpan...'; lucide.createIcons();
    const res = await fetch('/api/surat-masuk', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
    });
    const result = await res.json();
    btn.disabled = false; btn.innerHTML = '<i data-lucide="save" size="14"></i> Simpan'; lucide.createIcons();
    if (res.ok) {
        closeModal('modalTambahMasuk'); e.target.reset();
        showToast('Surat masuk berhasil ditambahkan', 'success');
        loadSuratMasuk();
    } else {
        const errs = result.errors ? Object.values(result.errors).flat().join(' | ') : (result.message || 'Gagal menyimpan');
        showToast(errs, 'error');
    }
});

document.getElementById('formTambahKeluar').addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(e.target));
    const btn = document.getElementById('btnSimpanKeluar');
    btn.disabled = true; btn.innerHTML = '<i data-lucide="loader" size="14" class="animate-spin"></i> Menyimpan...'; lucide.createIcons();
    const res = await fetch('/api/surat-keluar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data)
    });
    const result = await res.json();
    btn.disabled = false; btn.innerHTML = '<i data-lucide="save" size="14"></i> Simpan'; lucide.createIcons();
    if (res.ok) {
        closeModal('modalTambahKeluar'); e.target.reset();
        showToast('Surat keluar berhasil ditambahkan', 'success');
        loadSuratKeluar();
    } else {
        const errs = result.errors ? Object.values(result.errors).flat().join(' | ') : (result.message || 'Gagal menyimpan');
        showToast(errs, 'error');
    }
});

// ── Search ──────────────────────────────────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', (e) => {
    const kw = e.target.value.toLowerCase();
    filteredMasuk = allMasuk.filter(i => (i.kode_surat+i.perihal+i.pengirim).toLowerCase().includes(kw));
    filteredKeluar = allKeluar.filter(i => (i.kode_surat+i.perihal+i.tujuan).toLowerCase().includes(kw));
    document.getElementById('statMasuk').textContent = filteredMasuk.length;
    document.getElementById('statKeluar').textContent = filteredKeluar.length;
    renderMasuk(1); renderKeluar(1);
});

// ── Helpers ─────────────────────────────────────────────────────────────────
function fmtDate(d) {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
}

async function fetchAllSuratMasuk() { const r = await fetch('/api/surat-masuk?per_page=9999', { headers: getAuthHeaders() }); const j = await r.json(); return j.data||[]; }
async function fetchAllSuratKeluar() { const r = await fetch('/api/surat-keluar?per_page=9999', { headers: getAuthHeaders() }); const j = await r.json(); return j.data||[]; }

function openExportSekretariat() {
    openExportModal('Rekap Kesekretariatan', {
        pdf: () => exportSekretariatPdf(),
        excel: () => exportSekretariatExcel(),
        csv: () => exportSekretariatCsv(),
    });
}

async function exportSekretariatPdf() {
    const [masuk, keluar] = await Promise.all([fetchAllSuratMasuk(), fetchAllSuratKeluar()]);
    const now = new Date(); const { jsPDF } = window.jspdf; const doc = new jsPDF({ orientation:'landscape', unit:'mm', format:'a4' });
    const W = doc.internal.pageSize.getWidth(); const H = doc.internal.pageSize.getHeight();
    function hdr() { doc.setFillColor(15,23,42);doc.rect(0,0,W,28,'F');doc.setFillColor(37,99,235);doc.rect(0,28,W,3,'F');doc.setFillColor(37,99,235);doc.roundedRect(10,6,16,16,3,3,'F');doc.setTextColor(255,255,255);doc.setFont('helvetica','bold');doc.setFontSize(9);doc.text('CH',18,16.5,{align:'center'});doc.setFontSize(14);doc.text('Care',30,12);doc.setTextColor(96,165,250);doc.text('Hub',30+doc.getTextWidth('Care'),12);doc.setFont('helvetica','normal');doc.setFontSize(7);doc.setTextColor(148,163,184);doc.text('ADMIN PANEL  ·  LAPORAN RESMI',30,18);doc.text(`Dicetak: ${now.toLocaleString('id-ID')}`,W-10,15,{align:'right'}); }
    function ftr() { const t=doc.internal.getNumberOfPages(); for(let p=1;p<=t;p++){doc.setPage(p);doc.setFillColor(15,23,42);doc.rect(0,H-10,W,10,'F');doc.setFont('helvetica','normal');doc.setFontSize(6.5);doc.setTextColor(148,163,184);doc.text('© CareHub Admin',10,H-3.5);doc.text(`Halaman ${p}/${t}`,W-10,H-3.5,{align:'right'});} }
    hdr(); doc.setFont('helvetica','bold');doc.setFontSize(13);doc.setTextColor(15,23,42);doc.text('REKAP SURAT MASUK',10,42);
    doc.autoTable({ startY:50, head:[['No','Kode Surat','Perihal','Pengirim','Tgl Surat','Tgl Diterima']], body:masuk.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.pengirim,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_diterima)]), headStyles:{fillColor:[15,23,42],textColor:255,fontStyle:'bold'}, alternateRowStyles:{fillColor:[248,250,252]}, margin:{left:10,right:10} });
    doc.addPage(); hdr(); doc.setFont('helvetica','bold');doc.setFontSize(13);doc.setTextColor(15,23,42);doc.text('REKAP SURAT KELUAR',10,42);
    doc.autoTable({ startY:50, head:[['No','Kode Surat','Perihal','Tujuan','Tgl Surat','Tgl Dikirim']], body:keluar.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.tujuan,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_dikirim)]), headStyles:{fillColor:[15,23,42],textColor:255,fontStyle:'bold'}, alternateRowStyles:{fillColor:[248,250,252]}, margin:{left:10,right:10} });
    ftr(); doc.save(`rekap_sekretariat_${now.toISOString().slice(0,10)}.pdf`);
}
async function exportSekretariatExcel() {
    const [m,k] = await Promise.all([fetchAllSuratMasuk(),fetchAllSuratKeluar()]);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([['No','Kode Surat','Perihal','Pengirim','Tgl Surat','Tgl Diterima'],...m.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.pengirim,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_diterima)])]), 'Surat Masuk');
    XLSX.utils.book_append_sheet(wb, XLSX.utils.aoa_to_sheet([['No','Kode Surat','Perihal','Tujuan','Tgl Surat','Tgl Dikirim'],...k.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.tujuan,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_dikirim)])]), 'Surat Keluar');
    XLSX.writeFile(wb, `rekap_sekretariat_${new Date().toISOString().slice(0,10)}.xlsx`);
}
async function exportSekretariatCsv() {
    const [m,k] = await Promise.all([fetchAllSuratMasuk(),fetchAllSuratKeluar()]);
    const esc = v => { const s=String(v??''); return s.includes(',')||s.includes('"')?`"${s.replace(/"/g,'""')}"`:s; };
    const lines = ['=== SURAT MASUK ===','No,Kode Surat,Perihal,Pengirim,Tgl Surat,Tgl Diterima',...m.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.pengirim,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_diterima)].map(esc).join(',')),'','=== SURAT KELUAR ===','No,Kode Surat,Perihal,Tujuan,Tgl Surat,Tgl Dikirim',...k.map((r,i)=>[i+1,r.kode_surat,r.perihal,r.tujuan,fmtDate(r.tanggal_surat),fmtDate(r.tanggal_dikirim)].map(esc).join(','))];
    const a = document.createElement('a'); a.href=URL.createObjectURL(new Blob(['\uFEFF'+lines.join('\n')],{type:'text/csv;charset=utf-8;'})); a.download=`rekap_sekretariat_${new Date().toISOString().slice(0,10)}.csv`; a.click();
}

// ── Init ────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    loadSuratMasuk();
    loadSuratKeluar();
});
</script>
@endpush
@endsection
