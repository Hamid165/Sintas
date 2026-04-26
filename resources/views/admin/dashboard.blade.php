@extends('layouts.admin')

@section('title', 'Dashboard - CareHub')

@section('content')
<div class="space-y-6" id="dashboardRoot">

    {{-- Header --}}
    <div class="flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Dashboard Operasional</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">CareHub · {{ date('d F Y') }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="layout-grid"></i>
        </div>
    </div>

    {{-- ===== STATS CARDS ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

        {{-- Anak Asuh --}}
        <a href="{{ route('admin.anak') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i data-lucide="users" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Anak Asuh</p>
            <h3 id="statTotalAnak" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p class="text-[10px] text-blue-500 font-black uppercase mt-2">Anak aktif</p>
        </a>

        {{-- Saldo Kas --}}
        <a href="{{ route('admin.keuangan') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="wallet" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Saldo Kas</p>
            <h3 id="statSaldoKas" class="text-2xl font-black text-slate-800 mt-1">—</h3>
            <p id="statTotalTrx" class="text-[10px] text-emerald-500 font-black uppercase mt-2">— transaksi</p>
        </a>

        {{-- Inventaris --}}
        <a href="{{ route('admin.inventori') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="package" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Inventaris</p>
            <h3 id="statBarang" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p id="statBarangKritis" class="text-[10px] text-amber-500 font-black uppercase mt-2">— item kritis</p>
        </a>

        {{-- Kunjungan --}}
        <a href="{{ route('admin.kunjungan') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-violet-600 group-hover:text-white transition-all">
                <i data-lucide="calendar-check" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Kunjungan Tamu</p>
            <h3 id="statKunjungan" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p id="statKunjunganBulan" class="text-[10px] text-violet-500 font-black uppercase mt-2">— bulan ini</p>
        </a>

        {{-- Surat Masuk --}}
        <a href="{{ route('admin.audit.sekretariat') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-orange-600 group-hover:text-white transition-all">
                <i data-lucide="inbox" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Surat Masuk</p>
            <h3 id="statSuratMasuk" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p class="text-[10px] text-orange-500 font-black uppercase mt-2">Total rekap</p>
        </a>

        {{-- Surat Keluar --}}
        <a href="{{ route('admin.audit.sekretariat') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-cyan-600 group-hover:text-white transition-all">
                <i data-lucide="send" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Surat Keluar</p>
            <h3 id="statSuratKeluar" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p class="text-[10px] text-cyan-500 font-black uppercase mt-2">Total dikirim</p>
        </a>

        {{-- Audit Keuangan --}}
        <a href="{{ route('admin.audit.keuangan') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all group">
            <div class="w-11 h-11 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                <i data-lucide="shield-check" size="20"></i>
            </div>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Audit Keuangan</p>
            <h3 id="statAudit" class="text-3xl font-black text-slate-800 mt-1">—</h3>
            <p class="text-[10px] text-indigo-500 font-black uppercase mt-2">Dokumen terverifikasi</p>
        </a>

        {{-- Pemasukan vs Pengeluaran mini --}}
        <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-6 rounded-[2rem] shadow-sm text-white">
            <div class="w-11 h-11 bg-white/20 rounded-2xl flex items-center justify-center mb-4">
                <i data-lucide="trending-up" size="20"></i>
            </div>
            <p class="text-[10px] text-blue-100 font-black uppercase tracking-widest">Pemasukan Bulan Ini</p>
            <h3 id="statPemasukan" class="text-2xl font-black mt-1">—</h3>
            <p id="statPengeluaran" class="text-[10px] text-blue-200 font-black uppercase mt-2">Keluar: —</p>
        </div>
    </div>

    {{-- ===== RECENT TABLES (2 kolom) ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Recent Anak --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center"><i data-lucide="users" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Anak Asuh Terbaru</h4>
                </div>
                <a href="{{ route('admin.anak') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">L/P</th>
                            <th class="px-5 py-3">Tempat, Tgl Lahir</th>
                        </tr>
                    </thead>
                    <tbody id="recentAnak" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Keuangan --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center"><i data-lucide="wallet" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Transaksi Terbaru</h4>
                </div>
                <a href="{{ route('admin.keuangan') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Keterangan</th>
                            <th class="px-5 py-3">Jenis</th>
                            <th class="px-5 py-3 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="recentKeuangan" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Inventaris --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center"><i data-lucide="package" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Inventaris Terbaru</h4>
                </div>
                <a href="{{ route('admin.inventori') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Nama Barang</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3 text-right">Stok</th>
                        </tr>
                    </thead>
                    <tbody id="recentInventaris" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Kunjungan --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center"><i data-lucide="calendar-check" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Kunjungan Terbaru</h4>
                </div>
                <a href="{{ route('admin.kunjungan') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Nama Tamu</th>
                            <th class="px-5 py-3">Kegiatan</th>
                            <th class="px-5 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody id="recentKunjungan" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Surat Masuk --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center"><i data-lucide="inbox" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Surat Masuk Terbaru</h4>
                </div>
                <a href="{{ route('admin.audit.sekretariat') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Perihal</th>
                            <th class="px-5 py-3">Pengirim</th>
                        </tr>
                    </thead>
                    <tbody id="recentSuratMasuk" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Surat Keluar --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-cyan-50 text-cyan-600 rounded-xl flex items-center justify-center"><i data-lucide="send" size="16"></i></div>
                    <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Surat Keluar Terbaru</h4>
                </div>
                <a href="{{ route('admin.audit.sekretariat') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-black text-slate-500 uppercase border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3">Kode</th>
                            <th class="px-5 py-3">Perihal</th>
                            <th class="px-5 py-3">Tujuan</th>
                        </tr>
                    </thead>
                    <tbody id="recentSuratKeluar" class="divide-y divide-gray-50 text-xs">
                        <tr><td colspan="3" class="p-8 text-center text-gray-400 font-bold">Memuat...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/login';

const formatRp = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
const fmtDate  = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-';
const empty    = (cols, msg = 'Belum ada data.') =>
    `<tr><td colspan="${cols}" class="p-8 text-center text-gray-400 text-xs font-bold uppercase">${msg}</td></tr>`;

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/api/dashboard', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        const d = await res.json();

        // ── Stats ──────────────────────────────────────────────
        document.getElementById('statTotalAnak').innerText    = d.total_anak ?? 0;
        document.getElementById('statSaldoKas').innerText     = formatRp(d.total_saldo ?? 0);
        document.getElementById('statTotalTrx').innerText     = `${d.total_transaksi ?? 0} transaksi`;
        document.getElementById('statBarang').innerText       = `${d.total_item ?? 0} Item`;
        document.getElementById('statBarangKritis').innerText = `${d.barang_kritis ?? 0} item kritis`;
        document.getElementById('statKunjungan').innerText    = d.total_kunjungan ?? 0;
        document.getElementById('statKunjunganBulan').innerText = `${d.kunjungan_bulan_ini ?? 0} bulan ini`;
        document.getElementById('statSuratMasuk').innerText   = d.total_surat_masuk ?? 0;
        document.getElementById('statSuratKeluar').innerText  = d.total_surat_keluar ?? 0;
        document.getElementById('statAudit').innerText        = d.total_audit ?? 0;
        document.getElementById('statPemasukan').innerText    = formatRp(d.pemasukan ?? 0);
        document.getElementById('statPengeluaran').innerText  = `Keluar: ${formatRp(d.pengeluaran ?? 0)}`;

        // ── Recent Anak ─────────────────────────────────────────
        const ra = document.getElementById('recentAnak');
        ra.innerHTML = (d.recent_anak?.length)
            ? d.recent_anak.map(a => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-bold text-slate-800">${a.nama_lengkap}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase ${a.jenis_kelamin === 'Laki-laki' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700'}">
                            ${a.jenis_kelamin === 'Laki-laki' ? 'L' : 'P'}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">${a.tempat_tgl_lahir || '-'}</td>
                </tr>`).join('') : empty(3);

        // ── Recent Keuangan ─────────────────────────────────────
        const rk = document.getElementById('recentKeuangan');
        rk.innerHTML = (d.recent_keuangan?.length)
            ? d.recent_keuangan.map(t => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-slate-700">${t.keterangan || t.kategori || '-'}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase ${t.jenis_transaksi === 'Pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
                            ${t.jenis_transaksi}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-right font-black ${t.jenis_transaksi === 'Pemasukan' ? 'text-emerald-600' : 'text-rose-600'}">
                        ${t.jenis_transaksi === 'Pemasukan' ? '+' : '-'} ${formatRp(t.jumlah_nominal)}
                    </td>
                </tr>`).join('') : empty(3);

        // ── Recent Inventaris ───────────────────────────────────
        const ri = document.getElementById('recentInventaris');
        ri.innerHTML = (d.recent_inventaris?.length)
            ? d.recent_inventaris.map(b => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-bold text-slate-800">${b.nama_barang}</td>
                    <td class="px-5 py-3 text-gray-500">${b.kategori || '-'}</td>
                    <td class="px-5 py-3 text-right">
                        <span class="px-2 py-1 rounded-lg text-[10px] font-black uppercase ${b.stok < 10 ? 'bg-rose-50 text-rose-700' : 'bg-emerald-50 text-emerald-700'}">
                            ${b.stok} stok
                        </span>
                    </td>
                </tr>`).join('') : empty(3);

        // ── Recent Kunjungan ────────────────────────────────────
        const rkj = document.getElementById('recentKunjungan');
        rkj.innerHTML = (d.recent_kunjungan?.length)
            ? d.recent_kunjungan.map(k => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-bold text-slate-800">${k.nama_tamu}</td>
                    <td class="px-5 py-3 text-gray-500 max-w-[160px] truncate">${k.judul_kegiatan || '-'}</td>
                    <td class="px-5 py-3 text-gray-500 whitespace-nowrap">${fmtDate(k.tanggal_pelaksanaan)}</td>
                </tr>`).join('') : empty(3);

        // ── Recent Surat Masuk ──────────────────────────────────
        const rsm = document.getElementById('recentSuratMasuk');
        rsm.innerHTML = (d.recent_surat_masuk?.length)
            ? d.recent_surat_masuk.map(s => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3"><span class="font-black text-orange-600 text-[10px]">${s.kode_surat}</span></td>
                    <td class="px-5 py-3 text-slate-700 max-w-[160px] truncate">${s.perihal}</td>
                    <td class="px-5 py-3 text-gray-500">${s.pengirim}</td>
                </tr>`).join('') : empty(3);

        // ── Recent Surat Keluar ─────────────────────────────────
        const rsk = document.getElementById('recentSuratKeluar');
        rsk.innerHTML = (d.recent_surat_keluar?.length)
            ? d.recent_surat_keluar.map(s => `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3"><span class="font-black text-cyan-600 text-[10px]">${s.kode_surat}</span></td>
                    <td class="px-5 py-3 text-slate-700 max-w-[160px] truncate">${s.perihal}</td>
                    <td class="px-5 py-3 text-gray-500">${s.tujuan}</td>
                </tr>`).join('') : empty(3);

        lucide.createIcons();

    } catch (e) {
        console.error('Dashboard error:', e);
    }
});
</script>
@endpush
@endsection
