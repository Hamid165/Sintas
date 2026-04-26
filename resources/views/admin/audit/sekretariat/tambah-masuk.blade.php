@extends('layouts.admin')
@section('title', 'Surat Masuk - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.audit.sekretariat') }}" class="flex items-center gap-2 text-gray-400 hover:text-orange-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Rekap Sekretariat
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-orange-500 to-amber-500 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="inbox" size="32"></i>
            </div>
            <div>
                <h2 id="formTitle" class="text-2xl font-black uppercase tracking-tighter">Tambah Surat Masuk</h2>
                <p class="text-orange-100 text-xs font-bold uppercase tracking-widest mt-1">Rekap Kesekretariatan</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-orange-100 text-[10px] uppercase font-black tracking-widest">CareHub</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-[2rem] border-0 shadow-sm p-10">
        <form id="formSuratMasuk" class="space-y-6">

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Kode Surat <span class="text-rose-500">*</span></label>
                <input type="text" id="kode_surat" placeholder="Contoh: SM-APR-2026-001"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all text-sm" required>
                <p class="text-[10px] text-gray-400 ml-1">Harus unik. Format: SM-BLN-TAHUN-URUTAN</p>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Perihal <span class="text-rose-500">*</span></label>
                <input type="text" id="perihal" placeholder="Topik / judul surat"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all text-sm" required>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Pengirim <span class="text-rose-500">*</span></label>
                <input type="text" id="pengirim" placeholder="Nama instansi / organisasi pengirim"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all text-sm" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tanggal Surat <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal_surat"
                        class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tanggal Diterima <span class="text-rose-500">*</span></label>
                    <input type="date" id="tanggal_diterima"
                        class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all" required>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Keterangan</label>
                <textarea id="keterangan" rows="3" placeholder="Catatan tambahan (opsional)"
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-orange-100 transition-all text-sm resize-none"></textarea>
            </div>

            {{-- Action Buttons --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.audit.sekretariat') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="bg-orange-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-orange-100 hover:bg-orange-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="save" size="16"></i>
                    <span id="btnText">Simpan</span>
                </button>
                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest hidden lg:block ml-auto">Bidang bertanda <span class="text-rose-400">*</span> wajib diisi</p>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const urlParams = new URLSearchParams(window.location.search);
const editId = urlParams.get('id');

document.addEventListener('DOMContentLoaded', async () => {
    lucide.createIcons();
    document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
    });

    if (editId) {
        document.getElementById('formTitle').innerText = 'Edit Surat Masuk';
        document.getElementById('btnText').innerText = 'Simpan Perubahan';
        try {
            const res = await fetch(`/api/surat-masuk/${editId}`, {
                headers: { 'Authorization': 'Bearer ' + (localStorage.getItem('auth_token') || ''), 'Accept': 'application/json' }
            });
            const data = await res.json();
            document.getElementById('kode_surat').value = data.kode_surat || '';
            document.getElementById('perihal').value = data.perihal || '';
            document.getElementById('pengirim').value = data.pengirim || '';
            document.getElementById('tanggal_surat').value = data.tanggal_surat || '';
            document.getElementById('tanggal_diterima').value = data.tanggal_diterima || '';
            document.getElementById('keterangan').value = data.keterangan || '';
        } catch(e) { showToast('Gagal memuat data surat.', 'error'); }
    }
});

document.getElementById('formSuratMasuk').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSimpan');
    btn.disabled = true;
    btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Menyimpan...</span>';
    lucide.createIcons();

    const payload = {
        kode_surat: document.getElementById('kode_surat').value,
        perihal: document.getElementById('perihal').value,
        pengirim: document.getElementById('pengirim').value,
        tanggal_surat: document.getElementById('tanggal_surat').value,
        tanggal_diterima: document.getElementById('tanggal_diterima').value,
        keterangan: document.getElementById('keterangan').value,
    };

    const method = editId ? 'PUT' : 'POST';
    const url = editId ? `/api/surat-masuk/${editId}` : '/api/surat-masuk';

    try {
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + (localStorage.getItem('auth_token') || ''),
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        if (res.ok) {
            const msg = editId ? 'Surat masuk berhasil diperbarui!' : 'Surat masuk berhasil ditambahkan!';
            window.location.href = '{{ route("admin.audit.sekretariat") }}?toast=' + encodeURIComponent(msg);
        } else {
            const errs = result.errors ? Object.values(result.errors).flat().join(' | ') : (result.message || 'Gagal menyimpan');
            showToast(errs, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="save" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Simpan') + '</span>';
            lucide.createIcons();
        }
    } catch(e) {
        showToast('Terjadi kesalahan koneksi.', 'error');
        btn.disabled = false;
        btn.innerHTML = '<i data-lucide="save" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Simpan') + '</span>';
        lucide.createIcons();
    }
});
</script>
@endpush
@endsection
