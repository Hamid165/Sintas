@extends('layouts.admin')
@section('title', 'Artikel & CMS - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.artikel') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Artikel
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="pen-tool" size="32"></i>
            </div>
            <div>
                <h2 id="formTitle" class="text-2xl font-black uppercase tracking-tighter">Tulis Artikel Baru</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">SINTAS CMS Engine</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">SINTAS</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form Full Width --}}
    {{-- Kolom DB: judul | deskripsi_konten | gambar_konten (nullable) --}}
    <div class="bg-white rounded-[2rem] border shadow-sm p-10">
        <form id="formArtikel" class="space-y-6">

            {{-- Judul --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Judul Artikel <span class="text-rose-500">*</span></label>
                <input type="text" id="judul" placeholder="Masukkan judul artikel yang menarik perhatian..."
                    class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Deskripsi Konten --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Isi Konten / Deskripsi <span class="text-rose-500">*</span></label>
                <textarea id="deskripsi_konten" rows="12" placeholder="Tuliskan isi berita, kegiatan, atau pengumuman panti asuhan di sini..."
                    class="w-full p-5 bg-gray-50 border border-gray-200 rounded-2xl text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm leading-relaxed resize-none" required></textarea>
                <div class="flex justify-end">
                    <p id="charCount" class="text-[10px] text-gray-300 font-bold">0 karakter</p>
                </div>
            </div>

            {{-- Upload Gambar --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Gambar Artikel <span class="text-gray-300 normal-case font-bold">(Opsional, dari perangkat)</span></label>
                <div id="dropzoneArtikel" onclick="document.getElementById('gambar_konten_file').click()"
                    class="w-full border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all group">
                    <div id="previewWrap" class="hidden mb-4">
                        <img id="previewImg" src="" alt="preview" class="h-48 object-cover rounded-2xl mx-auto shadow-md">
                    </div>
                    <div id="uploadPlaceholder" class="space-y-3">
                        <div class="w-16 h-16 bg-gray-100 group-hover:bg-blue-100 rounded-2xl flex items-center justify-center mx-auto transition-colors">
                            <i data-lucide="image-plus" size="30" class="text-gray-300 group-hover:text-blue-400 transition-colors"></i>
                        </div>
                        <p class="font-black text-sm text-gray-400 group-hover:text-blue-500 transition-colors">Klik untuk upload gambar artikel</p>
                        <p class="text-[10px] text-gray-300 font-bold uppercase">JPG, PNG, WEBP • Maks. 10MB</p>
                    </div>
                    <input type="file" id="gambar_konten_file" accept="image/*" class="hidden" onchange="previewGambar(this)">
                </div>
                <div id="fileInfo" class="hidden flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-2xl px-4 py-3">
                    <i data-lucide="image" size="16" class="text-blue-500"></i>
                    <span id="namaFile" class="text-xs font-black text-blue-700"></span>
                    <button type="button" onclick="hapusGambar()" class="ml-auto text-rose-400 hover:text-rose-600 transition-colors">
                        <i data-lucide="x" size="16"></i>
                    </button>
                </div>
            </div>

            {{-- Actions --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.artikel') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="send" size="16"></i>
                    <span id="btnText">Publish</span>
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
        const kontenEl = document.getElementById('deskripsi_konten');
        kontenEl.addEventListener('input', () => {
            document.getElementById('charCount').innerText = kontenEl.value.length + ' karakter';
        });

        if(editId) {
            document.getElementById('formTitle').innerText = 'Edit Artikel';
            document.getElementById('btnText').innerText = 'Simpan Perubahan';
            try {
                const res = await fetch(`/api/artikel/${editId}`, { headers: getAuthHeaders() });
                const data = await res.json();
                document.getElementById('judul').value = data.judul || '';
                document.getElementById('deskripsi_konten').value = data.deskripsi_konten || '';
                document.getElementById('charCount').innerText = (data.deskripsi_konten || '').length + ' karakter';
                // Tampilkan gambar lama jika ada
                if (data.gambar_konten) {
                    document.getElementById('previewImg').src = '/storage/' + data.gambar_konten;
                    document.getElementById('previewWrap').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    document.getElementById('namaFile').textContent = 'Gambar tersimpan (upload baru untuk mengganti)';
                    document.getElementById('fileInfo').classList.remove('hidden');
                    document.getElementById('fileInfo').classList.add('flex');
                }
            } catch(e) { console.error(e); }
        }
    });

    document.getElementById('formArtikel').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Memproses...</span>';
        lucide.createIcons();

        // Gunakan FormData karena ada file upload
        const formData = new FormData();
        formData.append('judul', document.getElementById('judul').value);
        formData.append('deskripsi_konten', document.getElementById('deskripsi_konten').value);

        const fileInput = document.getElementById('gambar_konten_file');
        if (fileInput.files.length > 0) {
            formData.append('gambar_konten', fileInput.files[0]);
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
                const msg = editId ? 'Artikel berhasil diperbarui!' : 'Artikel baru berhasil dipublish!';
                window.location.href = '/admin/artikel?toast=' + encodeURIComponent(msg);
            } else {
                const err = await res.json();
                showToast(err.message || 'Gagal mempublish artikel.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="send" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Publish Artikel') + '</span>';
                lucide.createIcons();
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
    });
</script>
@endsection
