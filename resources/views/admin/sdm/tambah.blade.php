@extends('layouts.admin')
@section('title', 'Tambah Anggota SDM - CareHub')

@section('content')
<div class="space-y-6 w-full">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.struktur') }}" class="flex items-center gap-2 text-gray-400 hover:text-blue-600 transition-colors font-black text-xs uppercase tracking-widest">
            <i data-lucide="arrow-left" size="16"></i> Kembali ke Struktur SDM
        </a>
    </div>

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-10 rounded-[2rem] text-white flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i data-lucide="user-plus" size="32"></i>
            </div>
            <div>
                <h2 class="text-2xl font-black uppercase tracking-tighter">Tambah Anggota</h2>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mt-1">Staf Baru CareHub</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-[2rem] border-0 shadow-sm p-10">
        <form action="{{ route('admin.struktur.simpan') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="space-y-2">
                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required
                    class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required
                        class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Password <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="inputPassword" required
                            class="w-full p-4 pr-12 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm">
                        <button type="button" id="togglePassword"
                            onclick="togglePasswordVisibility()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-blue-600 transition-colors">
                            <i id="eyeIcon" data-lucide="eye-off" size="18"></i>
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 ml-1">Minimal 6 karakter.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Role (RBAC) <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm appearance-none">
                        @foreach(\Spatie\Permission\Models\Role::all() as $r)
                            <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Jabatan Aktual <span class="text-rose-500">*</span></label>
                    <input type="text" name="jabatan" placeholder="Contoh: Kepala Divisi / Staf" required
                        class="w-full p-4 bg-gray-50 border-0 rounded-2xl font-bold text-gray-800 outline-none focus:ring-4 focus:ring-blue-100 transition-all text-sm">
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="border-t border-gray-100 pt-6 flex flex-row flex-wrap items-center gap-2 md:gap-4 w-full">
                <a href="{{ route('admin.struktur') }}"
                    class="px-4 py-3 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest border-2 border-gray-200 text-gray-400 hover:border-gray-400 hover:text-gray-600 transition-all flex items-center justify-center gap-2">
                    <i data-lucide="x" size="16"></i> Batal
                </a>
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-3 md:px-10 md:py-4 rounded-xl md:rounded-2xl font-black uppercase text-[10px] md:text-xs tracking-widest shadow-xl shadow-blue-100 hover:bg-blue-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i data-lucide="save" size="16"></i>
                    <span>Simpan Data Anggota</span>
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });

    function togglePasswordVisibility() {
        const input = document.getElementById('inputPassword');
        const icon  = document.getElementById('eyeIcon');
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        // Ganti ikon: mata terbuka ↔ mata tertutup
        icon.setAttribute('data-lucide', isHidden ? 'eye' : 'eye-off');
        lucide.createIcons();
    }
</script>
@endpush
@endsection
