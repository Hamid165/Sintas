@extends('layouts.admin')
@section('title', 'Manajemen SDM - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Header Section --}}
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Manajemen SDM</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Daftar Akun Pengurus & Karyawan</p>
        </div>

        @if(Auth::user()->role == 'admin')
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <a href="{{ route('admin.struktur.tambah') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2 w-full sm:w-auto border-2 border-transparent">
                <i data-lucide="plus" size="18"></i>
                <span>Tambah Anggota</span>
            </a>
        </div>
        @endif
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-gray-100/50">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-[10px] text-gray-400 uppercase bg-gray-50/50 border-b border-gray-100 tracking-widest font-black">
                    <tr>
                        <th scope="col" class="px-6 py-5 rounded-tl-[2rem]">Profil & Nama</th>
                        <th scope="col" class="px-6 py-5">Jabatan</th>
                        <th scope="col" class="px-6 py-5">Role Akses</th>
                        <th scope="col" class="px-6 py-5">Email</th>
                        <th scope="col" class="px-6 py-5">Password (Admin)</th>
                        <th scope="col" class="px-6 py-5 text-center rounded-tr-[2rem]">Aksi</th>
                    </tr>
                </thead>
                <tbody id="strukturTableBody" class="divide-y divide-gray-50/50">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-bold uppercase tracking-widest text-xs">Memuat data anggota...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');
    const currentUserRole = '{{ Auth::user()->role }}';
    const currentUserId = {{ Auth::user()->id }};
    const csrfToken = '{{ csrf_token() }}';

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        fetchSdmData();
    });

    async function fetchSdmData() {
        try {
            const res = await fetch('/api/sdm', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const users = await res.json();
            renderTable(users);
        } catch (error) {
            console.error('Error fetching SDM data:', error);
            showToast('Gagal mengambil data struktur SDM', 'error');
        }
    }

    function renderTable(users) {
        const tbody = document.getElementById('strukturTableBody');
        if (!users.length) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center text-gray-400">
                            <i data-lucide="users" size="48" class="mb-4 text-gray-200"></i>
                            <p class="font-bold uppercase tracking-widest text-xs">Belum ada data anggota</p>
                        </div>
                    </td>
                </tr>
            `;
            lucide.createIcons();
            return;
        }

        tbody.innerHTML = users.map(u => {
            const imgHtml = u.foto 
                ? `<img src="/storage/${u.foto}" alt="Foto" class="w-full h-full object-cover">`
                : `<i data-lucide="user" size="20"></i>`;
                
            const rolesHtml = (u.spatie_roles || []).map(r => {
                const colorClass = r === 'admin' ? 'bg-emerald-100 text-emerald-700' 
                                : (r === 'sekretariat' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700');
                return `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest ${colorClass}">${r}</span>`;
            }).join(' ');

            let passwordHtml = '<span class="text-gray-400 font-medium italic">Rahasia</span>';
            if (currentUserRole === 'admin') {
                const safePw = (u.plain_password || 'N/A').replace(/"/g, '&quot;');
                passwordHtml = `
                    <div class="flex items-center gap-2">
                        <span class="password-field text-gray-500 font-mono text-sm tracking-widest"
                                data-password="${safePw}"
                                data-visible="0">
                            &#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;
                        </span>
                        <button type="button" onclick="togglePassword(this)"
                                class="text-gray-400 hover:text-blue-500 transition-colors"
                                title="Lihat Password">
                            <i data-lucide="eye-off" size="14"></i>
                        </button>
                    </div>
                `;
            }

            let actionHtml = '';
            if (currentUserRole === 'admin' && u.id !== currentUserId) {
                actionHtml = `
                    <button onclick="konfirmasiHapus(${u.id}, '${u.name.replace(/'/g, "\\'")}')" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                        <i data-lucide="trash-2" size="14"></i>
                    </button>
                `;
            }

            return `
                <tr class="hover:bg-blue-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 overflow-hidden shrink-0">
                                ${imgHtml}
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">${u.name}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">ID: ${String(u.id).padStart(4, '0')}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-bold text-gray-600">${u.jabatan || '-'}</td>
                    <td class="px-6 py-4">${rolesHtml}</td>
                    <td class="px-6 py-4 text-gray-500 font-medium">${u.email}</td>
                    <td class="px-6 py-4">${passwordHtml}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-2">
                            ${actionHtml}
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        lucide.createIcons();
    }
    // ── Toggle Password ─────────────────────────────────────────────────────
    function togglePassword(btn) {
        const span = btn.previousElementSibling;
        const isVisible = span.getAttribute('data-visible') === '1';

        if (isVisible) {
            // Sembunyikan → kembalikan ke titik-titik dan icon mata tercoret
            span.textContent = '\u2022\u2022\u2022\u2022\u2022\u2022\u2022\u2022';
            span.setAttribute('data-visible', '0');
            btn.innerHTML = '<i data-lucide="eye-off" size="14"></i>';
        } else {
            // Tampilkan → lihat password dan icon mata terbuka
            span.textContent = span.getAttribute('data-password');
            span.setAttribute('data-visible', '1');
            btn.innerHTML = '<i data-lucide="eye" size="14"></i>';
        }
        
        // Render ulang icon baru yang baru saja disisipkan
        lucide.createIcons();
    }

    // ── Konfirmasi Hapus ────────────────────────────────────────────────────
    async function konfirmasiHapus(id, nama) {
        const confirmed = await showConfirm(`Hapus ${nama} secara permanen?`);
        if (confirmed) {
            try {
                const res = await fetch(`/api/sdm/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                if (res.ok) {
                    showToast(`${nama} berhasil dihapus dari struktur.`, 'success');
                    fetchSdmData();
                } else {
                    showToast('Gagal menghapus anggota.', 'error');
                }
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan.', 'error');
            }
        }
    }

    // ── Toast dari query param setelah redirect ─────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        const params = new URLSearchParams(window.location.search);
        const toast = params.get('toast');
        const toastType = params.get('toast_type') || 'success';
        if (toast) {
            showToast(decodeURIComponent(toast), toastType);
            // Bersihkan query param dari URL tanpa reload
            const url = new URL(window.location);
            url.searchParams.delete('toast');
            url.searchParams.delete('toast_type');
            window.history.replaceState({}, '', url);
        }
    });
</script>
@endpush
@endsection