@extends('layouts.admin')
@section('title', 'Tambah Audit Keuangan - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.audit.keuangan') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Audit Keuangan
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="shield-check" size="32"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter">Tambah Audit Keuangan</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Verifikasi Transaksi Keuangan</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">CareHub</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-[2rem] border-0 shadow-sm p-10">
        <form id="formAuditKeuangan" class="space-y-6">

            {{-- Pilih Transaksi Keuangan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Transaksi Keuangan <span class="text-rose-500">*</span></label>
                <select id="keuangan_id"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all appearance-none" required>
                    <option value="">— Pilih Transaksi —</option>
                    @foreach($transaksiList as $t)
                    <option value="{{ $t->id }}">
                        {{ $t->created_at->format('d/m/Y') }} · {{ $t->jenis_transaksi }} · {{ $t->keterangan }} · Rp {{ number_format($t->jumlah_nominal, 0, ',', '.') }}
                    </option>
                    @endforeach
                </select>
                <p class="text-[10px] text-gray-400 ml-1">Pilih transaksi yang akan diverifikasi / diaudit</p>
            </div>

            {{-- Jenis Audit --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Jenis Audit <span class="text-rose-500">*</span></label>
                <select id="jenis_audit"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all appearance-none" required>
                    <option value="">— Pilih Jenis —</option>
                    <option value="MASUK">Pemasukan</option>
                    <option value="KELUAR">Pengeluaran</option>
                </select>
            </div>

            {{-- Kode Dokumen --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Kode Dokumen Surat <span class="text-rose-500">*</span></label>
                <select id="kode_dokumen"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all appearance-none" required>
                    <option value="">— Pilih Kode Surat —</option>
                    @if($suratMasukList->count() > 0)
                    <optgroup label="── Surat Masuk ──">
                        @foreach($suratMasukList as $kode)
                        <option value="{{ $kode }}">{{ $kode }} (Surat Masuk)</option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if($suratKeluarList->count() > 0)
                    <optgroup label="── Surat Keluar ──">
                        @foreach($suratKeluarList as $kode)
                        <option value="{{ $kode }}">{{ $kode }} (Surat Keluar)</option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
                <p class="text-[10px] text-gray-400 ml-1">Kode surat referensi dari Rekap Sekretariat</p>
            </div>

            {{-- Keterangan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Keterangan</label>
                <textarea id="keterangan" rows="3" placeholder="Catatan audit (opsional)"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm resize-none"></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.audit.keuangan') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="save" size="16"></i>
                    <span id="btnText">Simpan Audit</span>
                </button>
                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest hidden lg:block ml-auto">Bidang bertanda <span class="text-rose-400">*</span> wajib diisi</p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
    document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });
});

document.getElementById('formAuditKeuangan').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Menyimpan...</span>';
    lucide.createIcons();

    const payload = {
        keuangan_id: document.getElementById('keuangan_id').value,
        jenis_audit: document.getElementById('jenis_audit').value,
        kode_dokumen: document.getElementById('kode_dokumen').value,
        keterangan: document.getElementById('keterangan').value,
    };

    try {
        const token = localStorage.getItem('auth_token') || '';
        const res = await fetch('/api/audit-keuangan', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (res.ok) {
            window.location.href = '{{ route("admin.audit.keuangan") }}?toast=' + encodeURIComponent('Audit keuangan berhasil ditambahkan!');
        } else {
            const errs = result.errors ? Object.values(result.errors).flat().join(' | ') : (result.message || 'Gagal menyimpan');
            showToast(errs, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" size="16"></i><span>Simpan Audit</span>';
            lucide.createIcons();
        }
    } catch(e) {
        showToast('Terjadi kesalahan koneksi.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="save" size="16"></i><span>Simpan Audit</span>';
        lucide.createIcons();
    }
});
</script>
@endpush
@endsection
