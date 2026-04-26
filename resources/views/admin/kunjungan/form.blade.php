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
                <i data-lucide="users-round" size="32"></i>
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
                <div class="relative w-full group">
                    <input type="file" id="foto_kegiatan" accept="image/*" onchange="previewGambar(this)"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="">

                    <div id="uploadPlaceholder" class="w-full border-2 border-dashed border-gray-200 rounded-3xl p-10 flex flex-col items-center justify-center text-center bg-gray-50/50 group-hover:bg-gray-50 group-hover:border-blue-300 transition-colors">
                        <div class="w-14 h-14 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                            <i data-lucide="image-plus" size="24"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-500">Klik untuk upload foto kegiatan</p>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">JPG, PNG, WEBP • MAKS. 10MB</p>
                    </div>

                    <div id="previewWrap" class="hidden relative w-full border-2 border-dashed border-blue-200 rounded-3xl p-2 bg-blue-50/50 max-h-80 box-border overflow-hidden group-hover:border-blue-400 transition-colors">
                        <img id="previewImg" src="" alt="Preview" class="w-full h-48 md:h-64 object-cover rounded-[1.25rem]">
                        <div class="absolute top-4 right-4 z-20 flex gap-2">
                            <button type="button" onclick="hapusGambar(event)" class="w-10 h-10 bg-white/90 backdrop-blur text-rose-500 hover:bg-rose-500 hover:text-white rounded-xl shadow-sm flex items-center justify-center transition-colors">
                                <i data-lucide="trash-2" size="18"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div id="fileInfo" class="hidden items-center gap-2 mt-3 px-4 py-3 bg-blue-50 rounded-xl border border-blue-100">
                    <i data-lucide="image" size="16" class="text-blue-500"></i>
                    <p id="namaFile" class="text-xs font-bold text-blue-700 truncate"></p>
                </div>
            </div>

            {{-- Deskripsi Laporan --}}
            <div class="space-y-2 relative">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Deskripsi Laporan <span class="text-rose-500">*</span></label>
                    <button type="button" onclick="generateDeskripsiAI()" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-indigo-500 hover:text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-all w-fit shadow-sm border border-indigo-100">
                        <i data-lucide="sparkles" size="12"></i> Auto-Generate Laporan
                    </button>
                </div>
                <textarea id="deskripsi_laporan" rows="12" placeholder="Tuliskan laporan kegiatan kunjungan tamu..."
                    class="w-full p-5 bg-gray-50 border-0 border-gray-200 rounded-2xl text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm leading-relaxed resize-none" required></textarea>
                <div class="flex justify-end">
                    <p id="charCount" class="text-[10px] text-gray-300 font-bold">0 karakter</p>
                </div>
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

    function hapusGambar(e) {
        if (e) {
            e.stopPropagation();
            e.preventDefault();
        }
        document.getElementById('foto_kegiatan').value = '';
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
                const res = await fetch(`/api/kunjungan-tamu/${editId}`, { headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await res.json();
                document.getElementById('judul_kegiatan').value = data.judul_kegiatan || '';
                document.getElementById('nama_tamu').value = data.nama_tamu || '';
                document.getElementById('tanggal_pelaksanaan').value = data.tanggal_pelaksanaan || '';
                document.getElementById('deskripsi_laporan').value = data.deskripsi_laporan || '';
                document.getElementById('charCount').innerText = (data.deskripsi_laporan || '').length + ' karakter';
                // Tampilkan gambar lama jika ada
                if (data.foto_url) {
                    document.getElementById('previewImg').src = data.foto_url;
                    document.getElementById('previewWrap').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    document.getElementById('namaFile').textContent = 'Foto terlampir sebelumnya';
                    document.getElementById('fileInfo').classList.remove('hidden');
                    document.getElementById('fileInfo').classList.add('flex');
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

        const fileInput = document.getElementById('foto_kegiatan');
        if (fileInput.files.length > 0) {
            formData.append('foto_kegiatan', fileInput.files[0]);
        }

        // Method spoofing untuk PUT jika didukung, tetapi route kita POST
        let url = '/api/kunjungan-tamu';
        let method = 'POST';
        if (editId) {
            formData.append('_method', 'POST'); // sesuai routes web.php yg pake post /kunjungan-tamu/{id}
            url = `/api/kunjungan-tamu/${editId}`;
        }

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch(url, { 
                method, 
                headers: { 
                    'Authorization': `Bearer ${token}`, 
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }, 
                body: formData 
            });
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

    // Fitur AI Generate (Real AI via Google Gemini API)
    async function generateDeskripsiAI() {
        const judul = document.getElementById('judul_kegiatan').value.trim() || 'Kunjungan rutin';
        const tamu = document.getElementById('nama_tamu').value.trim() || 'Tamu Panti';
        let tanggal = document.getElementById('tanggal_pelaksanaan').value;
        if (tanggal) {
            tanggal = new Date(tanggal).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
        } else {
            tanggal = 'hari ini';
        }

        const poin = `Judul: ${judul}, Tamu: ${tamu}, Tanggal: ${tanggal}`;

        const btn = document.querySelector('button[onclick="generateDeskripsiAI()"]');
        const originalHTML = btn.innerHTML;
        
        btn.innerHTML = '<i data-lucide="loader" class="animate-spin" size="12"></i> AI Sedang Menulis...';
        btn.disabled = true;
        lucide.createIcons();
        
        try {
            const res = await fetch('/api/kunjungan-tamu/generate-ai', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ poin_poin: poin })
            });

            const json = await res.json();
            
            if (res.ok) {
                const textarea = document.getElementById('deskripsi_laporan');
                textarea.value = json.data.trim();
                document.getElementById('charCount').textContent = json.data.trim().length + ' karakter';
                showToast('AI Berhasil merangkai deskripsi laporan!', 'success');
            } else {
                showToast(json.message || 'Gagal terhubung ke AI', 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan jaringan.', 'error');
        } finally {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            lucide.createIcons();
        }
    }
</script>
@endsection
