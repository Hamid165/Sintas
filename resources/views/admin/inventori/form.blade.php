@extends('layouts.admin')
@section('title', 'Inventaris - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.inventori') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Inventaris
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="package" size="32"></i>
            </div>
            <div>
                <h2 id="formTitle" class="text-2xl font-black uppercase tracking-tighter">Tambah Barang Inventaris</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">SINTAS Inventory System</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">SINTAS</p>
            <p id="tanggalHari" class="text-white font-black text-sm mt-1"></p>
        </div>
    </div>

    {{-- Form Full Width --}}
    {{-- Kolom DB: nama_barang | stok | kondisi | kategori | gambar (nullable) --}}
    <div class="bg-white rounded-[2rem] border shadow-sm p-10">
        <form id="formInventori" class="space-y-6">

            {{-- Row 1: Nama Barang --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Barang <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_barang" placeholder="Contoh: Beras Premium, Sabun Mandi, dll..."
                    class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm" required>
            </div>

            {{-- Row 2: Stok + Kondisi + Kategori --}}
            <div class="grid grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Jumlah Stok <span class="text-rose-500">*</span></label>
                    <input type="number" id="stok" placeholder="0" min="0"
                        class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-xl" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Kondisi Barang <span class="text-rose-500">*</span></label>
                    <select id="kondisi" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none appearance-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all">
                        <option value="Baik">Baik</option>
                        <option value="Cukup Baik">Cukup Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Kategori <span class="text-rose-500">*</span></label>
                    <select id="kategori" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none appearance-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all">
                        <option value="Sembako">Sembako / Dapur</option>
                        <option value="Kebutuhan Mandi">Kebutuhan Mandi & Kebersihan</option>
                        <option value="Pakaian">Pakaian & Tekstil</option>
                        <option value="Pendidikan">Alat Tulis & Buku</option>
                        <option value="Kesehatan">Obat-obatan & Kesehatan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            {{-- Row 3: Upload Gambar --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Foto Barang <span class="text-gray-300 normal-case font-bold">(Opsional)</span></label>
                <div id="dropzoneInventori" onclick="document.getElementById('gambar').click()"
                    class="w-full border-2 border-dashed border-gray-200 rounded-2xl p-8 text-center cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-all group">
                    <div id="previewWrap" class="hidden mb-4">
                        <img id="previewImg" src="" alt="preview" class="h-36 object-cover rounded-xl mx-auto">
                    </div>
                    <div id="uploadPlaceholder" class="space-y-2">
                        <div class="w-14 h-14 bg-gray-100 group-hover:bg-blue-100 rounded-2xl flex items-center justify-center mx-auto transition-colors">
                            <i data-lucide="image-plus" size="28" class="text-gray-300 group-hover:text-blue-400 transition-colors"></i>
                        </div>
                        <p class="font-black text-sm text-gray-400 group-hover:text-blue-500 transition-colors">Klik untuk upload foto barang</p>
                        <p class="text-[10px] text-gray-300 font-bold uppercase">JPG, PNG, WEBP • Maks. 10MB</p>
                    </div>
                    <input type="file" id="gambar" accept="image/*" class="hidden" onchange="previewGambar(this)">
                </div>
                <p id="namaFile" class="text-[10px] text-gray-400 font-bold hidden"></p>
            </div>

            {{-- Actions --}}
            <div class="border-t border-gray-100 pt-6 flex items-center gap-4">
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center gap-2">
                    <i data-lucide="save" size="16"></i>
                    <span id="btnText">Simpan Barang</span>
                </button>
                <a href="{{ route('admin.inventori') }}"
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
                document.getElementById('namaFile').textContent = '📎 ' + file.name;
                document.getElementById('namaFile').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();
        document.getElementById('tanggalHari').innerText = new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'});

        if(editId) {
            document.getElementById('formTitle').innerText = 'Edit Barang Inventaris';
            document.getElementById('btnText').innerText = 'Simpan Perubahan';
            try {
                const res = await fetch(`/api/inventaris/${editId}`, { headers: getAuthHeaders() });
                const data = await res.json();
                document.getElementById('nama_barang').value = data.nama_barang || '';
                document.getElementById('stok').value = data.stok || '';
                document.getElementById('kondisi').value = data.kondisi || 'Baik';
                document.getElementById('kategori').value = data.kategori || 'Sembako';
                // Tampilkan gambar lama jika ada
                if (data.gambar) {
                    document.getElementById('previewImg').src = '/storage/' + data.gambar;
                    document.getElementById('previewWrap').classList.remove('hidden');
                    document.getElementById('uploadPlaceholder').classList.add('hidden');
                    document.getElementById('namaFile').textContent = '📷 Gambar sudah ada (upload baru untuk mengganti)';
                    document.getElementById('namaFile').classList.remove('hidden');
                }
            } catch(e) { console.error(e); }
        }
    });

    document.getElementById('formInventori').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSimpan');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" size="16" class="animate-spin"></i><span>Menyimpan...</span>';
        lucide.createIcons();

        // Gunakan FormData karena ada file upload
        const formData = new FormData();
        formData.append('nama_barang', document.getElementById('nama_barang').value);
        formData.append('stok', document.getElementById('stok').value);
        formData.append('kondisi', document.getElementById('kondisi').value);
        formData.append('kategori', document.getElementById('kategori').value);

        const fileInput = document.getElementById('gambar');
        if (fileInput.files.length > 0) {
            formData.append('gambar', fileInput.files[0]);
        }

        // Untuk PUT/update, Laravel butuh method spoofing karena FormData tidak support PUT
        let url = '/api/inventaris';
        let method = 'POST';
        if (editId) {
            formData.append('_method', 'PUT');
            url = `/api/inventaris/${editId}`;
        }

        try {
            const res = await fetch(url, { method, headers: getAuthHeaders(), body: formData });
            if(res.ok) {
                const msg = editId ? 'Barang berhasil diperbarui!' : 'Barang baru berhasil ditambahkan!';
                window.location.href = '/admin/inventori?toast=' + encodeURIComponent(msg);
            } else {
                const err = await res.json();
                showToast(err.message || 'Gagal menyimpan barang.', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="save" size="16"></i><span>' + (editId ? 'Simpan Perubahan' : 'Simpan Barang') + '</span>';
                lucide.createIcons();
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
    });
</script>
@endsection
