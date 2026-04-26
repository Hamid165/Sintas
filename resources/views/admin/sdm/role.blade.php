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

    @foreach($roles as $role)
        @if($role->name === 'admin') @continue @endif {{-- Admin tidak perlu diatur --}}
        
        <div class="bg-white rounded-[2rem] border-0 shadow-sm p-8 relative overflow-hidden group">
            {{-- Decorative accent --}}
            <div class="absolute top-0 left-0 w-2 h-full {{ $role->name === 'sekretariat' ? 'bg-orange-500' : ($role->name === 'bendahara' ? 'bg-blue-600' : 'bg-emerald-500') }}"></div>

            <div class="mb-6 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center 
                        {{ $role->name === 'sekretariat' ? 'bg-orange-100 text-orange-600' : ($role->name === 'bendahara' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600') }}">
                        <i data-lucide="shield-check" size="24"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 uppercase tracking-tighter">Role: {{ ucfirst($role->name) }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $role->permissions->count() }} Hak Akses Aktif</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.role.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($permissions as $groupName => $groupPermissions)
                    <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest mb-4 border-b border-slate-200 pb-2">
                            Menu {{ $groupName }}
                        </h4>
                        <div class="space-y-3">
                            @foreach($groupPermissions as $perm)
                            <label class="flex items-center gap-3 cursor-pointer group/item">
                                <div class="relative flex items-center">
                                    <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" 
                                        {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }}
                                        class="peer sr-only">
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all 
                                        {{ $role->name === 'sekretariat' ? 'peer-checked:bg-orange-500' : ($role->name === 'bendahara' ? 'peer-checked:bg-blue-600' : 'peer-checked:bg-emerald-500') }}">
                                    </div>
                                </div>
                                @php
                                    // Parse name like 'create_anak' to 'Create'
                                    $action = explode('_', $perm->name)[0];
                                    $actionLabel = [
                                        'view' => 'Read (Lihat)',
                                        'create' => 'Create (Tambah)',
                                        'edit' => 'Update (Ubah)',
                                        'delete' => 'Delete (Hapus)'
                                    ][$action] ?? ucfirst($action);
                                @endphp
                                <span class="text-xs font-bold text-slate-600 group-hover/item:text-slate-900 transition-colors">
                                    {{ $actionLabel }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                    <button type="submit" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-black uppercase text-[10px] tracking-widest shadow-lg hover:bg-slate-900 transition-all active:scale-[0.98] flex items-center gap-2">
                        <i data-lucide="save" size="16"></i> Simpan Hak Akses {{ ucfirst($role->name) }}
                    </button>
                </div>
            </form>
        </div>
    @endforeach

</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
        @if(session('toast'))
            showToast("{{ session('toast') }}", "{{ session('toast_type', 'success') }}");
        @endif
    });
</script>
@endpush
@endsection
