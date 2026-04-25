@extends('layouts.admin')
@section('title', 'Kunjungan Tamu - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.kunjungan') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Kunjungan Tamu
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="pen-tool" size="32"></i>
            </div>
            <div>
                <h2 id="formTitle" class="text-2xl font-black uppercase tracking-tighter">Tambah Kunjungan Tamu</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Database Kunjungan</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">CareHub</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form Full Width --}}
    {{-- Kolom DB: judul_kegiatan | nama_tamu | tanggal_pelaksanaan | foto_kegiatan | deskripsi_laporan | nomor_surat_ref --}}
    <div class="bg-white rounded-[2rem] border-0 shadow-sm p-10">
        <form id="formKunjungan" class="space-y-6">

            {{-- Judul Kegiatan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Judul Kegiatan <span class="text-rose-500">*</span></label>
                <input type="text" id="judul_kegiatan" placeholder="Masukkan judul kegiatan kunjungan..."
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Nama Tamu --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Tamu <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_tamu" placeholder="Masukkan nama tamu yang berkunjung..."
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Tanggal Pelaksanaan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Tanggal Pelaksanaan <span class="text-rose-500">*</span></label>
                <input type="date" id="tanggal_pelaksanaan"
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Foto Kegiatan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Foto Kegiatan</label>
                <input type="file" id="foto_kegiatan" accept="image/*"
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
            </div>

            {{-- Deskripsi Laporan --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Deskripsi Laporan <span class="text-rose-500">*</span></label>
                <textarea id="deskripsi_laporan" rows="12" placeholder="Tuliskan laporan kegiatan kunjungan tamu..."
                    class="w-full p-5 bg-gray-50 border-0 border-gray-200 rounded-2xl text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm leading-relaxed resize-none" required></textarea>
                <div class="flex justify-end">
                    <p id="charCount" class="text-[10px] text-gray-300 font-bold">0 karakter</p>
                </div>
            </div>

            {{-- Nomor Surat Ref --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nomor Surat Referensi</label>
                <input type="text" id="nomor_surat_ref" placeholder="Masukkan nomor surat referensi jika ada..."
                    class="w-full p-4 bg-gray-50 border-0 border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
            </div>

            {{-- Actions --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.kunjungan') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="send" size="16"></i>
                    <span id="btnText">Simpan</span>
                </button>
                <p class="text-[10px] text-gray-300 font-bold uppercase tracking-widest hidden lg:block ml-auto text-center sm:text-left">Bidang bertanda <span class="text-rose-400">*</span> wajib diisi</p>
            </div>
        </form>
    </div>
</div>

<script>
    const token = localStorage.getItem('auth_token');
    if(!token) { window.location.href = '/login'; }

    const getAuthHeaders = () => ({ 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' });

    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');

    function previewGambar(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('previewWrap').classList.remove('hidden');
                document.getElementById('uploadPlaceholder').classList.add('hidden');
                document.getElementById('namaFile').textContent = file.name;
                document.getElementById('fileInfo').classList.remove('hidden');
                document.getElementById('fileInfo').classList.add('flex');
            };
            reader.readAsDataURL(file);
        }
    }

    function hapusGambar() {
        document.getElementById('gambar_konten_file').value = '';
        document.getElementById('previewImg').src = '';
        document.getElementById('previewWrap').classList.add('hidden');
        document.getElementById('uploadPlaceholder').classList.remove('hidden');
        document.getElementById('fileInfo').classList.add('hidden');
        document.getElementById('fileInfo').classList.remove('flex');
    }

    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();
        document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});

        // Karakter counter
        const kontenEl = document.getElementById('deskripsi_laporan');
        kontenEl.addEventListener('input', () => {
            document.getElementById('charCount').innerText = kontenEl.value.length + ' karakter';
        });

        if(editId) {
            document.getElementById('formTitle').innerText = 'Edit Kunjungan Tamu';
            document.getElementById('btnText').innerText = 'Simpan Perubahan';
            try {
                const res = await fetch(`/api/artikel/${editId}`, { headers: getAuthHeaders() });
                const data = await res.json();
                document.getElementById('judul_kegiatan').value = data.judul_kegiatan || '';
                document.getElementById('nama_tamu').value = data.nama_tamu || '';
                document.getElementById('tanggal_pelaksanaan').value = data.tanggal_pelaksanaan || '';
                document.getElementById('deskripsi_laporan').value = data.deskripsi_laporan || '';
                document.getElementById('nomor_surat_ref').value = data.nomor_surat_ref || '';
                document.getElementById('charCount').innerText = (data.deskripsi_laporan || '').length + ' karakter';
                // Tampilkan gambar lama jika ada
                if (data.foto_kegiatan) {
                    // Assuming similar preview logic, but since we removed the preview, maybe skip or add back if needed
                }
            } catch(e) { console.error(e); }
        }
    });

    document.getElementById('formKunjungan').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Memproses...</span>';
        lucide.createIcons();

        // Gunakan FormData karena ada file upload
        const formData = new FormData();
        formData.append('judul_kegiatan', document.getElementById('judul_kegiatan').value);
        formData.append('nama_tamu', document.getElementById('nama_tamu').value);
        formData.append('tanggal_pelaksanaan', document.getElementById('tanggal_pelaksanaan').value);
        formData.append('deskripsi_laporan', document.getElementById('deskripsi_laporan').value);
        formData.append('nomor_surat_ref', document.getElementById('nomor_surat_ref').value);

        const fileInput = document.getElementById('foto_kegiatan');
        if (fileInput.files.length > 0) {
            formData.append('foto_kegiatan', fileInput.files[0]);
        }

        // Method spoofing untuk PUT
        let url = '/api/artikel';
        let method = 'POST';
        if (editId) {
            formData.append('_method', 'PUT');
            url = `/api/artikel/${editId}`;
        }

        try {
            const res = await fetch(url, { method, headers: getAuthHeaders(), body: formData });
            if(res.ok) {
                const msg = editId ? 'Data kunjungan berhasil disimpan!' : 'Data kunjungan berhasil ditambahkan!';
                window.location.href = '/admin/kunjungan?toast=' + encodeURIComponent(msg);
            } else {
                const err = await res.json();
                showToast(err.message || 'Gagal menyimpan kunjungan.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="send" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Simpan') + '</span>';
                lucide.createIcons();
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
    });
</script>
@endsection
