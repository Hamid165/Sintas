@extends('layouts.admin')
@section('title', 'Dashboard - CareHub')

@section('content')
<div class="space-y-6" id="dashboardRoot">

    {{-- Header --}}
    <div class="bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm flex items-center justify-between">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Dashboard</h3>
            <p class="text-xs text-gray-400 mt-1 font-bold">{{ date('l, d F Y') }}</p>
        </div>
        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="layout-grid" size="18"></i>
        </div>
    </div>

    {{-- 4 Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <a href="{{ route('admin.anak') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all">
                <i data-lucide="users" size="18"></i>
            </div>
            <h3 id="statTotalAnak" class="text-3xl font-black text-slate-800">—</h3>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Anak Asuh</p>
        </a>

        <a href="{{ route('admin.keuangan') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-all">
                <i data-lucide="wallet" size="18"></i>
            </div>
            <h3 id="statSaldoKas" class="text-xl font-black text-slate-800 leading-tight">—</h3>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Saldo Kas</p>
        </a>

        <a href="{{ route('admin.inventori') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-lucide="package" size="18"></i>
            </div>
            <h3 id="statBarang" class="text-3xl font-black text-slate-800">—</h3>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Inventaris</p>
        </a>

        <a href="{{ route('admin.kunjungan') }}" class="bg-white p-6 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all group">
            <div class="w-10 h-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-600 group-hover:text-white transition-all">
                <i data-lucide="calendar-check" size="18"></i>
            </div>
            <h3 id="statKunjungan" class="text-3xl font-black text-slate-800">—</h3>
            <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest mt-1">Kunjungan</p>
        </a>

    </div>

    {{-- 2 Recent Lists --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Anak Terbaru --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Anak Asuh Terbaru</h4>
                <a href="{{ route('admin.anak') }}" class="text-[10px] font-black text-blue-500 uppercase hover:text-blue-700 transition-colors">Lihat Semua →</a>
            </div>
            <div id="recentAnakList" class="divide-y divide-gray-50">
                <div class="px-6 py-10 text-center text-gray-400 text-xs font-bold">Memuat...</div>
            </div>
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                <h4 class="font-black text-xs uppercase tracking-[0.15em] text-slate-800">Transaksi Terbaru</h4>
                <a href="{{ route('admin.keuangan') }}" class="text-[10px] font-black text-blue-500 uppercase hover:text-blue-700 transition-colors">Lihat Semua →</a>
            </div>
            <div id="recentKeuanganList" class="divide-y divide-gray-50">
                <div class="px-6 py-10 text-center text-gray-400 text-xs font-bold">Memuat...</div>
            </div>
        </div>

    </div>

    {{-- Aksi Cepat --}}
    <div class="bg-white rounded-[2rem] shadow-sm p-6">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">Aksi Cepat</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            <a href="{{ route('admin.anak') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition-all group">
                <i data-lucide="users" size="20"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-center">Manajemen Anak</span>
            </a>
            <a href="{{ route('admin.keuangan') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white transition-all group">
                <i data-lucide="wallet" size="20"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-center">Keuangan</span>
            </a>
            <a href="{{ route('admin.inventori') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-amber-50 hover:bg-amber-600 text-amber-600 hover:text-white transition-all group">
                <i data-lucide="package" size="20"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-center">Inventaris</span>
            </a>
            <a href="{{ route('admin.kunjungan') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-violet-50 hover:bg-violet-600 text-violet-600 hover:text-white transition-all group">
                <i data-lucide="users-round" size="20"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-center">Kunjungan Tamu</span>
            </a>
            <a href="{{ route('admin.audit') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl bg-indigo-50 hover:bg-indigo-600 text-indigo-600 hover:text-white transition-all group">
                <i data-lucide="shield-check" size="20"></i>
                <span class="text-[10px] font-black uppercase tracking-widest text-center">Audit</span>
            </a>
        </div>
    </div>

</div>

@push('scripts')
<script>
const token = localStorage.getItem('auth_token');
if (!token) window.location.href = '/login';

const formatRp = (n) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(n);
const fmtDate  = (d) => d ? new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) : '-';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/api/dashboard', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (res.status === 401) { window.location.href = '/login'; return; }
        const d = await res.json();

        // Stats
        document.getElementById('statTotalAnak').innerText = d.total_anak ?? 0;
        document.getElementById('statSaldoKas').innerText  = formatRp(d.total_saldo ?? 0);
        document.getElementById('statBarang').innerText    = d.total_item ?? 0;
        document.getElementById('statKunjungan').innerText = d.total_kunjungan ?? 0;

        // Recent Anak
        const ra = document.getElementById('recentAnakList');
        ra.innerHTML = d.recent_anak?.length
            ? d.recent_anak.map(a => `
                <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-black
                        ${a.jenis_kelamin === 'Laki-laki' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'}">
                        ${a.nama_lengkap.charAt(0)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-800 text-xs truncate">${a.nama_lengkap}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">${a.tempat_tgl_lahir || '-'}</p>
                    </div>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-lg
                        ${a.jenis_kelamin === 'Laki-laki' ? 'bg-blue-50 text-blue-600' : 'bg-pink-50 text-pink-600'}">
                        ${a.jenis_kelamin === 'Laki-laki' ? 'L' : 'P'}
                    </span>
                </div>`).join('')
            : '<div class="px-6 py-10 text-center text-gray-400 text-xs font-bold">Belum ada data.</div>';

        // Recent Keuangan
        const rk = document.getElementById('recentKeuanganList');
        rk.innerHTML = d.recent_keuangan?.length
            ? d.recent_keuangan.map(t => `
                <div class="px-6 py-3.5 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0
                        ${t.jenis_transaksi === 'Pemasukan' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            ${t.jenis_transaksi === 'Pemasukan' ? '<path d="m18 15-6-6-6 6"/>' : '<path d="m6 9 6 6 6-6"/>'}
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-slate-800 text-xs truncate">${t.keterangan || t.kategori || '-'}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">${fmtDate(t.created_at)}</p>
                    </div>
                    <p class="font-black text-xs flex-shrink-0 ${t.jenis_transaksi === 'Pemasukan' ? 'text-emerald-600' : 'text-rose-600'}">
                        ${t.jenis_transaksi === 'Pemasukan' ? '+' : '-'} ${formatRp(t.jumlah_nominal)}
                    </p>
                </div>`).join('')
            : '<div class="px-6 py-10 text-center text-gray-400 text-xs font-bold">Belum ada transaksi.</div>';

        lucide.createIcons();
    } catch (e) {
        console.error('Dashboard error:', e);
    }
});
</script>
@endpush
@endsection
