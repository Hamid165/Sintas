@extends('layouts.admin')
@section('title', 'Keuangan - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.keuangan') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Keuangan
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="wallet" size="32"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter">Tambah Transaksi</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Keuangan Database</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">CareHub</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form Full Width --}}
    <div class="bg-white rounded-[2rem] border-0 shadow-sm p-10">
        <form id="formKeuangan" class="space-y-6">

            {{-- Pilih Jenis Transaksi --}}
            <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Jenis Transaksi <span class="text-rose-500">*</span></label>
                <input type="hidden" id="jenis_transaksi" value="">
                <div class="grid grid-cols-1 gap-4">
                    <button type="button" onclick="pilihJenis('Pemasukan')" id="btnPemasukan"
                        class="py-5 rounded-2xl border-2 border-emerald-200 bg-emerald-50 text-emerald-700 font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all">
                        <i data-lucide="arrow-down-left" size="20"></i> Pemasukan
                    </button>
                    <button type="button" onclick="pilihJenis('Pengeluaran')" id="btnPengeluaran"
                        class="py-5 rounded-2xl border-2 border-gray-200 bg-gray-50 text-gray-400 font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all">
                        <i data-lucide="arrow-up-right" size="20"></i> Pengeluaran
                    </button>
                </div>
            </div>

            {{-- Row: Kategori + Nominal --}}
            <div class="grid grid-cols-1 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" id="kategori" placeholder="Donasi, Sembako, Gaji Pengurus, dll..."
                        class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nominal (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-gray-400 text-sm">Rp</span>
                        <input type="number" id="jumlah_nominal" placeholder="0" min="0"
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-black text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-xl" required>
                    </div>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Keterangan (Opsional)</label>
                <textarea id="keterangan" placeholder="Keterangan singkat tentang transaksi ini..." rows="3"
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm resize-none"></textarea>
            </div>

            {{-- Actions --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.keuangan') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="save" size="16"></i> Simpan
                </button>
                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest hidden lg:block ml-auto text-center sm:text-left">Pilih jenis transaksi sebelum menyimpan</p>
            </div>
        </form>
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

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});
    });

    function pilihJenis(jenis) {
        document.getElementById('jenis_transaksi').value = jenis;
        const btnP = document.getElementById('btnPemasukan');
        const btnK = document.getElementById('btnPengeluaran');
        if(jenis === 'Pemasukan') {
            btnP.className = 'py-5 rounded-2xl border-2 border-emerald-500 bg-emerald-500 text-white font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 transition-all ring-4 ring-emerald-100';
            btnK.className = 'py-5 rounded-2xl border-2 border-gray-200 bg-gray-50 text-gray-400 font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all';
        } else {
            btnK.className = 'py-5 rounded-2xl border-2 border-rose-500 bg-rose-500 text-white font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 transition-all ring-4 ring-rose-100';
            btnP.className = 'py-5 rounded-2xl border-2 border-gray-200 bg-gray-50 text-gray-400 font-black text-sm uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all';
        }
    }

    document.getElementById('formKeuangan').addEventListener('submit', async function(e) {
        e.preventDefault();
        const jenis = document.getElementById('jenis_transaksi').value;
        if(!jenis) { showToast('Pilih jenis transaksi (Pemasukan/Pengeluaran) terlebih dahulu!', 'warning'); return; }

        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Menyimpan...</span>';
        lucide.createIcons();

        const payload = {
            jenis_transaksi: jenis,
            kategori: document.getElementById('kategori').value,
            jumlah_nominal: document.getElementById('jumlah_nominal').value,
            keterangan: document.getElementById('keterangan').value,
        };

        try {
            const res = await fetch('/api/keuangan', { method: 'POST', headers: getAuthHeaders(), body: JSON.stringify(payload) });
            if(res.ok) {
                window.location.href = '/admin/keuangan?toast=' + encodeURIComponent('Data transaksi berhasil ditambahkan!');
            } else {
                showToast('Gagal menyimpan transaksi.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="save" size="16"></i> Simpan Transaksi';
                lucide.createIcons();
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
    });
</script>
@endsection
