@extends('layouts.admin')
@section('title', 'Manajemen Hak Akses (RBAC) - CareHub')

@section('content')
<div class="space-y-6 w-full">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Role & Permissions</h1>
            <p class="text-sm text-slate-500 font-medium">Atur hak akses (Create, Read, Update, Delete) untuk tiap Role</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-blue-600 text-[10px] uppercase font-black tracking-widest">CareHub Security</p>
        </div>
    </div>

    <div id="rolesContainer" class="space-y-6">
        <div class="text-center py-12 text-gray-400 font-bold uppercase tracking-widest text-xs">
            Memuat data roles...
        </div>
    </div>

</div>

@push('scripts')
<script>
    const token = localStorage.getItem('auth_token');

    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        fetchRoles();
    });

    async function fetchRoles() {
        try {
            const res = await fetch('/api/roles-permissions', {
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
            const data = await res.json();
            renderRoles(data.roles, data.permissions);
        } catch (e) {
            console.error(e);
            showToast('Gagal memuat roles', 'error');
        }
    }

    function renderRoles(roles, allPermissions) {
        const container = document.getElementById('rolesContainer');
        container.innerHTML = '';

        roles.forEach(role => {
            if (role.name === 'admin') return;

            const accentColor = role.name === 'sekretariat' ? 'bg-orange-500' : (role.name === 'bendahara' ? 'bg-blue-600' : 'bg-emerald-500');
            const iconBg = role.name === 'sekretariat' ? 'bg-orange-100 text-orange-600' : (role.name === 'bendahara' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600');
            const peerCheckedColor = role.name === 'sekretariat' ? 'peer-checked:bg-orange-500' : (role.name === 'bendahara' ? 'peer-checked:bg-blue-600' : 'peer-checked:bg-emerald-500');

            // Find role permissions
            const rolePerms = role.permissions.map(p => p.name);

            let groupsHtml = '';
            for (const groupName in allPermissions) {
                let permsHtml = '';
                allPermissions[groupName].forEach(perm => {
                    const isChecked = rolePerms.includes(perm.name) ? 'checked' : '';
                    const action = perm.name.split('_')[0];
                    const actionLabel = {
                        'view': 'Read (Lihat)',
                        'create': 'Create (Tambah)',
                        'edit': 'Update (Ubah)',
                        'delete': 'Delete (Hapus)'
                    }[action] || (action.charAt(0).toUpperCase() + action.slice(1));

                    permsHtml += `
                        <label class="flex items-center gap-3 cursor-pointer group/item">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="permissions[]" value="${perm.name}" ${isChecked} class="peer sr-only">
                                <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all ${peerCheckedColor}"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-600 group-hover/item:text-slate-900 transition-colors">
                                ${actionLabel}
                            </span>
                        </label>
                    `;
                });

                groupsHtml += `
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2">
                            Menu ${groupName}
                        </h4>
                        <div class="space-y-3">
                            ${permsHtml}
                        </div>
                    </div>
                `;
            }

            const html = `
                <div class="bg-white rounded-[2rem] border-0 shadow-sm p-8 relative overflow-hidden group">
                    <div class="absolute top-0 left-0 w-2 h-full ${accentColor}"></div>
                    <div class="mb-6 flex justify-between items-center">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center ${iconBg}">
                                <i data-lucide="shield-check" size="24"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Role: ${role.name.charAt(0).toUpperCase() + role.name.slice(1)}</h3>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">${role.permissions.length} Hak Akses Aktif</p>
                            </div>
                        </div>
                    </div>

                    <form onsubmit="updateRole(event, ${role.id})">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            ${groupsHtml}
                        </div>
                        <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98] flex items-center gap-2">
                                <i data-lucide="save" size="16"></i> Simpan Hak Akses ${role.name.charAt(0).toUpperCase() + role.name.slice(1)}
                            </button>
                        </div>
                    </form>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
        lucide.createIcons();
    }

    async function updateRole(e, roleId) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const permissions = formData.getAll('permissions[]');

        try {
            const res = await fetch(`/api/roles-permissions/${roleId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ permissions })
            });

            const result = await res.json();
            if (res.ok) {
                showToast(result.message, 'success');
                fetchRoles(); // Refresh data
            } else {
                showToast(result.message || 'Gagal menyimpan', 'error');
            }
        } catch (err) {
            showToast('Terjadi kesalahan', 'error');
        }
    }
</script>
@endpush
@endsection
