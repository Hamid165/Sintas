@extends('layouts.admin')
@section('title', 'Profil Admin - SINTAS')

@section('content')
<div class="space-y-6 w-full">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="user-circle-2" size="32"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter">Profil Admin</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Informasi Akun & Keamanan</p>
            </div>
        </div>
        <div class="text-right hidden md:block">
            <p class="text-blue-100 text-[10px] uppercase font-black tracking-widest">Role</p>
            <p class="text-white font-black text-sm mt-1">🔐 Super Admin</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Avatar Card --}}
        <div class="bg-white rounded-[2rem] border shadow-sm p-8 flex flex-col items-center text-center gap-5 h-fit self-start">
            <div class="relative group cursor-pointer" onclick="document.getElementById('inputFoto').click()">
                <div class="w-32 h-32 rounded-[2rem] overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-100 border-4 border-white shadow-xl relative">
                    <img id="avatarImg" src="https://api.dicebear.com/7.x/avataaars/svg?seed=Admin" alt="Avatar" class="w-full h-full object-cover">
                    {{-- Hover Overlay --}}
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="camera" class="text-white" size="24"></i>
                    </div>
                </div>
                <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg border-2 border-white" title="Ganti Foto">
                    <i data-lucide="pencil" size="14" class="text-white"></i>
                </div>
                <input type="file" id="inputFoto" class="hidden" accept="image/*">
            </div>
            <div>
                <h3 id="namaDisplay" class="text-xl font-black text-slate-800">Administrator</h3>
                <p id="emailDisplay" class="text-xs text-gray-400 font-bold mt-1">admin@sintas.id</p>
                <span class="mt-3 inline-block bg-blue-50 text-blue-700 text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest">Super Admin</span>
            </div>
            <div class="w-full border-t pt-5 space-y-3">
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400 font-bold uppercase tracking-widest">Status Akun</span>
                    <span class="text-emerald-600 font-black">● Aktif</span>
                </div>
                <div class="flex justify-between items-center text-xs">
                    <span class="text-gray-400 font-bold uppercase tracking-widest">Login Terakhir</span>
                    <span id="loginTerakhir" class="font-black text-gray-700">—</span>
                </div>
            </div>
        </div>

        {{-- Right: Forms --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Edit Profil --}}
            <div class="bg-white rounded-[2rem] border shadow-sm p-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <i data-lucide="user" size="18"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Edit Informasi Akun</h4>
                        <p class="text-[10px] text-gray-400 font-bold">Ubah nama dan email admin</p>
                    </div>
                </div>

                <form id="formProfil" class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Lengkap</label>
                            <input type="text" id="inputNama" placeholder="Nama Admin"
                                class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Email</label>
                            <input type="email" id="inputEmail" placeholder="admin@email.com"
                                class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                        </div>
                    </div>
                    <div id="alertProfil" class="hidden p-4 rounded-2xl text-sm font-bold"></div>
                    <button type="submit" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all flex items-center gap-2">
                        <i data-lucide="save" size="16"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Ganti Password --}}
            <div class="bg-white rounded-[2rem] border shadow-sm p-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center">
                        <i data-lucide="lock" size="18"></i>
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 uppercase tracking-widest text-xs">Ganti Password</h4>
                        <p class="text-[10px] text-gray-400 font-bold">Perbarui kata sandi akun Anda</p>
                    </div>
                </div>

                <form id="formPassword" class="space-y-5">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" id="passwordLama" placeholder="••••••••"
                                class="w-full p-4 pr-12 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                            <button type="button" onclick="togglePass('passwordLama', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                <i data-lucide="eye" size="18"></i>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Password Baru</label>
                            <div class="relative">
                                <input type="password" id="passwordBaru" placeholder="Min. 8 karakter"
                                    class="w-full p-4 pr-12 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                                <button type="button" onclick="togglePass('passwordBaru', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                    <i data-lucide="eye" size="18"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" id="passwordKonfirmasi" placeholder="Ulangi password baru"
                                    class="w-full p-4 pr-12 bg-gray-50 border border-gray-200 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400 transition-all text-sm">
                                <button type="button" onclick="togglePass('passwordKonfirmasi', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-500 transition-colors">
                                    <i data-lucide="eye" size="18"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="alertPassword" class="hidden p-4 rounded-2xl text-sm font-bold"></div>
                    <button type="submit" class="bg-rose-500 text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest shadow-xl shadow-rose-100 hover:bg-rose-600 transition-all flex items-center gap-2">
                        <i data-lucide="key" size="16"></i> Update Password
                    </button>
                </form>
            </div>


        </div>
    </div>
</div>

<script>
    const token = localStorage.getItem('auth_token');
    if(!token) { window.location.href = '/login'; }

    const getAuthHeaders = (isJson = true) => {
        const h = { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' };
        if (isJson) h['Content-Type'] = 'application/json';
        return h;
    };

    function showAlert(elId, msg, isSuccess) {
        const el = document.getElementById(elId);
        el.className = `p-4 rounded-2xl text-sm font-bold ${isSuccess ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'}`;
        el.innerText = msg;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 4000);
    }

    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        btn.innerHTML = `<i data-lucide="${showing ? 'eye' : 'eye-off'}" size="18"></i>`;
        lucide.createIcons();
    }

    document.getElementById('inputFoto').addEventListener('change', function(e) {
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('avatarImg').src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });

    document.addEventListener('DOMContentLoaded', async () => {
        lucide.createIcons();
        document.getElementById('loginTerakhir').innerText = new Date().toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});

        // Load user info dari API
        try {
            const res = await fetch('/api/user', { headers: getAuthHeaders() });
            if(res.ok) {
                const user = await res.json();
                document.getElementById('namaDisplay').innerText = user.name || 'Administrator';
                document.getElementById('emailDisplay').innerText = user.email || 'admin@sintas.id';
                document.getElementById('inputNama').value = user.name || '';
                document.getElementById('inputEmail').value = user.email || '';
                
                // Update avatar
                if (user.foto) {
                    const photoUrl = `/storage/${user.foto}`;
                    document.getElementById('avatarImg').src = photoUrl;
                    const topBtn = document.querySelector('nav img[alt="Avatar"]');
                    if(topBtn) topBtn.src = photoUrl;
                } else {
                    const seed = encodeURIComponent(user.name || 'Admin');
                    const dicebear = `https://api.dicebear.com/7.x/avataaars/svg?seed=${seed}`;
                    document.getElementById('avatarImg').src = dicebear;
                }
                
                localStorage.setItem('user_name', user.name);
                localStorage.setItem('user_email', user.email);
            }
        } catch(e) { console.error(e); }
    });

    // Form edit profil
    document.getElementById('formProfil').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalHtml = btn.innerHTML;
        
        const nama = document.getElementById('inputNama').value.trim();
        const email = document.getElementById('inputEmail').value.trim();
        const foto = document.getElementById('inputFoto').files[0];

        if (!nama || !email) { showAlert('alertProfil', 'Nama dan email wajib diisi.', false); return; }

        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader" class="animate-spin" size="16"></i> Menyimpan...';
        lucide.createIcons();

        const formData = new FormData();
        formData.append('name', nama);
        formData.append('email', email);
        if (foto) formData.append('foto', foto);

        try {
            const res = await fetch('/api/user/profile', {
                method: 'POST', // Route.post handles it
                headers: getAuthHeaders(false), // No JSON header for FormData
                body: formData
            });
            if(res.ok) {
                const user = await res.json();
                document.getElementById('namaDisplay').innerText = user.name;
                document.getElementById('emailDisplay').innerText = user.email;
                if (user.foto) {
                    const url = `/storage/${user.foto}`;
                    document.getElementById('avatarImg').src = url;
                    const topBtn = document.querySelector('nav img[alt="Avatar"]');
                    if(topBtn) topBtn.src = url;
                }
                localStorage.setItem('user_name', user.name);
                showToast('Profil berhasil diperbarui!');
            } else {
                const err = await res.json();
                showToast(err.message || 'Gagal memperbarui profil.', 'error');
            }
        } catch(e) { showToast('Terjadi kesalahan koneksi.', 'error'); }
        finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            lucide.createIcons();
        }
    });

    // Form ganti password
    document.getElementById('formPassword').addEventListener('submit', async function(e) {
        e.preventDefault();
        const lama = document.getElementById('passwordLama').value;
        const baru = document.getElementById('passwordBaru').value;
        const konfirmasi = document.getElementById('passwordKonfirmasi').value;

        if (!lama || !baru || !konfirmasi) { showAlert('alertPassword', 'Semua field password wajib diisi.', false); return; }
        if (baru.length < 8) { showAlert('alertPassword', 'Password baru minimal 8 karakter.', false); return; }
        if (baru !== konfirmasi) { showAlert('alertPassword', 'Konfirmasi password tidak cocok!', false); return; }

        try {
            const res = await fetch('/api/user/password', {
                method: 'PUT',
                headers: getAuthHeaders(),
                body: JSON.stringify({ current_password: lama, password: baru, password_confirmation: konfirmasi })
            });
            if(res.ok) {
                showAlert('alertPassword', '✅ Password berhasil diubah!', true);
                document.getElementById('formPassword').reset();
            } else {
                const err = await res.json();
                showAlert('alertPassword', err.message || 'Gagal mengubah password.', false);
            }
        } catch(e) { showAlert('alertPassword', 'Terjadi kesalahan koneksi.', false); }
    });
</script>
@endsection
