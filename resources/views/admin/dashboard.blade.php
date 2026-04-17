@extends('layouts.admin')

@section('title', 'Dashboard - CareHub')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center bg-white p-8 rounded-[2rem] shadow-sm">
        <div>
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Ringkasan Operasional</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">CareHub {{ date('d F Y') }}</p>
        </div>
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
            <i data-lucide="layout-grid"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="users"></i>
                </div>
                <span class="text-[10px] font-black text-blue-500 bg-blue-50 px-3 py-1 rounded-full uppercase">Aktif</span>
            </div>
            <div class="mt-6">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Anak Asuh</p>
                <h3 id="statTotalAnak" class="text-4xl font-black text-slate-800">0</h3>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="wallet"></i>
                </div>
                <span class="text-[10px] font-black text-emerald-500 bg-emerald-50 px-3 py-1 rounded-full uppercase">Surplus</span>
            </div>
            <div class="mt-6">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Saldo Kas CareHub</p>
                <h3 id="statSaldoKas" class="text-3xl font-black text-slate-800">Rp 0</h3>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                    <i data-lucide="package"></i>
                </div>
                <span class="text-[10px] font-black text-rose-500 bg-rose-50 px-3 py-1 rounded-full uppercase">Perlu Restock</span>
            </div>
            <div class="mt-6">
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-widest">Total Barang Inventaris</p>
                <h3 class="text-4xl font-black text-slate-800"><span id="statBarang">0</span> <span class="text-sm text-gray-400 font-bold uppercase">Item</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm overflow-hidden">
        <div class="p-8 border-b border-[#D1D5DC] bg-gray-50/50 flex justify-between items-center">
            <h4 class="font-black text-xs uppercase tracking-[0.2em] text-slate-800">Aktivitas Keuangan Terbaru</h4>
            <a href="{{ route('admin.keuangan') }}" class="text-[10px] font-black text-blue-600 uppercase hover:underline">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-[10px] font-black text-slate-800 uppercase border-b border-[#D1D5DC]">
                    <tr>
                        <th class="px-6 py-4">#</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Kategori</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4">Jenis</th>
                        <th class="px-6 py-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody id="trxList" class="divide-y divide-gray-100 text-sm">
                    <tr><td colspan="6" class="p-12 text-center text-gray-400 text-xs font-bold uppercase">Memuat data dari API...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('auth_token');
    if(!token) window.location.href = '/login';

    document.addEventListener('DOMContentLoaded', async () => {
        try {
            const res = await fetch('/api/dashboard', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            if(res.status === 401) {
                window.location.href = '/login';
                return;
            }

            const data = await res.json();
            
            // Format Rupiah
            const formatRp = (angka) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka).split(',')[0];

            document.getElementById('statTotalAnak').innerText = data.total_anak;
            document.getElementById('statSaldoKas').innerText = formatRp(data.total_saldo);
            document.getElementById('statBarang').innerText = data.total_barang;

            // Fetch partial keuangan data directly
            const resKeu = await fetch('/api/keuangan', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const dKeu = await resKeu.json();
            
            const trxList = document.getElementById('trxList');
            if(dKeu.length === 0) {
                trxList.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-gray-400 text-xs font-bold uppercase">Belum ada transaksi tercatat.</td></tr>`;
            } else {
                trxList.innerHTML = dKeu.slice(0, 5).map((trx, idx) => `
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-gray-800 font-black text-xs">${idx + 1}</td>
                        <td class="px-6 py-4 text-gray-800 font-bold text-xs whitespace-nowrap">${new Date(trx.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'short', year:'numeric'})}</td>
                        <td class="px-6 py-4 font-black text-gray-800">${trx.kategori}</td>
                        <td class="px-6 py-4 text-gray-800 text-xs">${trx.keterangan || '-'}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase ${trx.jenis_transaksi === 'Pemasukan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'}">
                                ${trx.jenis_transaksi}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-black ${trx.jenis_transaksi === 'Pemasukan' ? 'text-emerald-600' : 'text-rose-600'}">
                            ${trx.jenis_transaksi === 'Pemasukan' ? '+' : '-'} ${formatRp(trx.jumlah_nominal)}
                        </td>
                    </tr>
                `).join('');
                lucide.createIcons();
            }
        } catch(e) {
            console.error('API Error:', e);
        }
    });
</script>
@endsection
