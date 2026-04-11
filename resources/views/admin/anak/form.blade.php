@extends('layouts.admin')
@section('title', 'Manajemen Anak - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.anak') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Daftar Anak
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="user-plus" size="32"></i>
            </div>
            <div>
                <h2 id="formTitle" class="text-2xl font-black uppercase tracking-tighter">Tambah Anak Baru</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">SINTAS Database System</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">SINTAS</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form Full Width --}}
    <div class="bg-white rounded-[2rem] border shadow-sm p-10">
        <form id="formAnak" class="space-y-6">

            {{-- Row 1: Nama --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="nama" placeholder="Masukkan nama lengkap anak..."
                    class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Row 2: Usia + Gender + TTL --}}
            <div class="grid grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Usia <span class="text-rose-500">*</span></label>
                    <input type="number" id="usia" placeholder="0" min="0" max="99"
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Jenis Kelamin <span class="text-rose-500">*</span></label>
                    <select id="jenis_kelamin" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none appearance-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all">
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tempat / Tanggal Lahir <span class="text-rose-500">*</span></label>
                    <input type="text" id="tempat_tgl_lahir" placeholder="Purwokerto, 12 Mei 2012"
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
                </div>
            </div>

            {{-- Row 3: Pendidikan + Kesehatan --}}
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Pendidikan / Sekolah</label>
                    <input type="text" id="info_pendidikan" placeholder="SD Negeri 1 Purwokerto..."
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Riwayat Kesehatan</label>
                    <input type="text" id="riwayat_kesehatan" placeholder="Sehat / Alergi debu / dll..."
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-gray-100 pt-6 flex items-center gap-4">
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center gap-2">
                    <i data-lucide="save" size="16"></i>
                    <span id="btnText">Simpan Data</span>
                </button>
                <a href="{{ route('admin.anak') }}"
                    class="px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest ml-2">Bidang bertanda <span class="text-rose-400">*</span> wajib diisi</p>
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

    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');

    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();
        document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});

        if (editId) {
            document.getElementById('formTitle').innerText = 'Edit Data Anak';
            document.getElementById('btnText').innerText = 'Simpan Perubahan';
            try {
                const res = await fetch(`/api/anak/${editId}`, { headers: getAuthHeaders() });
                const data = await res.json();
                document.getElementById('nama').value = data.nama_lengkap || '';
                document.getElementById('usia').value = data.usia || '';
                document.getElementById('jenis_kelamin').value = data.jenis_kelamin || 'Laki-laki';
                document.getElementById('tempat_tgl_lahir').value = data.tempat_tgl_lahir || '';
                document.getElementById('info_pendidikan').value = data.info_pendidikan || '';
                document.getElementById('riwayat_kesehatan').value = data.riwayat_kesehatan || '';
            } catch(e) { console.error(e); }
        }
    });

    document.getElementById('formAnak').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Menyimpan...</span>';
        lucide.createIcons();

        const payload = {
            nama_lengkap: document.getElementById('nama').value,
            usia: parseInt(document.getElementById('usia').value),
            jenis_kelamin: document.getElementById('jenis_kelamin').value,
            tempat_tgl_lahir: document.getElementById('tempat_tgl_lahir').value,
            info_pendidikan: document.getElementById('info_pendidikan').value,
            riwayat_kesehatan: document.getElementById('riwayat_kesehatan').value
        };

        const method = editId ? 'PUT' : 'POST';
        const url = editId ? `/api/anak/${editId}` : '/api/anak';

        try {
            const res = await fetch(url, { method, headers: getAuthHeaders(), body: JSON.stringify(payload) });
            if(res.ok) {
                const msg = editId ? 'Data anak berhasil diperbarui!' : 'Data anak baru berhasil disimpan!';
                window.location.href = '/admin/anak?toast=' + encodeURIComponent(msg);
            } else {
                const err = await res.json();
                showToast((err.message || 'Gagal menyimpan data.'), 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="save" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Simpan Data') + '</span>';
                lucide.createIcons();
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
    });
</script>
@endsection
