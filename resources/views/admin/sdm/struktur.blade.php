@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Struktur Organisasi</h1>
            <p class="text-sm text-slate-500 font-medium">Hierarki kepengurusan dan manajemen SDM CareHub</p>
        </div>
        
        @if(Auth::user()->role == 'admin')
        <button onclick="openModalTambah()" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-blue-100 transition-all flex items-center gap-2">
            <i data-lucide="plus-circle" size="18"></i>
            Tambah Anggota
        </button>
        @endif
    </div>

    {{-- Container Bagan --}}
    <div class="bg-white p-12 rounded-[3rem] shadow-sm flex justify-center overflow-x-auto min-h-[600px] border border-slate-100">
        @if($kepala)
            <div class="flex flex-col items-center">
                {{-- 1. LEVEL ATAS (ROOT) --}}
                <div class="relative group">
                    <div class="bg-slate-900 text-white p-6 rounded-[2rem] w-60 text-center shadow-2xl border-4 border-white">
                        <p class="text-[10px] uppercase font-black tracking-[0.2em] text-blue-400 mb-1">{{ $kepala->jabatan }}</p>
                        <h3 class="font-black text-lg leading-tight">{{ $kepala->name }}</h3>
                    </div>
                    
                    @if(Auth::user()->role == 'admin')
                    <div class="absolute -top-3 -right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-all">
                        <form action="{{ route('admin.struktur.hapus', $kepala->id) }}" method="POST" onsubmit="return confirm('Hapus pimpinan tertinggi?')">
                            @csrf @method('DELETE')
                            <button class="bg-rose-500 text-white p-2 rounded-xl shadow-lg hover:bg-rose-600">
                                <i data-lucide="trash-2" size="14"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </div>

                @if($kepala->bawahan->count() > 0)
                    <div class="h-12 w-1 bg-slate-200"></div>
                    <div class="flex gap-12 items-start">
                        @foreach($kepala->bawahan as $staf)
                            <div class="flex flex-col items-center relative">
                                <div class="h-8 w-1 bg-slate-200"></div>

                                {{-- 2. LEVEL STAFF (Bawah Pimpinan) --}}
                                <div class="relative group">
                                    <div class="bg-white border-2 border-slate-100 p-5 rounded-[1.5rem] w-52 text-center shadow-sm hover:shadow-xl hover:border-blue-100 transition-all">
                                        <p class="text-[9px] text-blue-600 uppercase font-black tracking-widest mb-1">{{ $staf->jabatan }}</p>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $staf->name }}</h4>
                                        <p class="text-[10px] text-slate-400 mt-1 uppercase">{{ $staf->role }}</p>
                                    </div>

                                    @if(Auth::user()->role == 'admin')
                                    <div class="absolute -top-2 -right-2 flex gap-1 opacity-0 group-hover:opacity-100 transition-all">
                                        <form action="{{ route('admin.struktur.hapus', $staf->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $staf->name }}?')">
                                            @csrf @method('DELETE')
                                            <button class="bg-rose-500 text-white p-1.5 rounded-lg shadow-md hover:bg-rose-600">
                                                <i data-lucide="trash-2" size="12"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>

                                {{-- 3. LEVEL SUB-STAFF (Anak Buah Staff) --}}
                                @if($staf->bawahan->count() > 0)
                                    <div class="h-8 w-1 bg-slate-200"></div>
                                    <div class="space-y-3">
                                        @foreach($staf->bawahan as $subStaf)
                                            <div class="relative group">
                                                <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl w-44 text-center shadow-sm hover:bg-white hover:border-blue-200 transition-all">
                                                    <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">{{ $subStaf->jabatan }}</p>
                                                    <h5 class="text-xs font-bold text-slate-700">{{ $subStaf->name }}</h5>
                                                </div>

                                                @if(Auth::user()->role == 'admin')
                                                <div class="absolute -top-2 -right-2 opacity-0 group-hover:opacity-100 transition-all">
                                                    <form action="{{ route('admin.struktur.hapus', $subStaf->id) }}" method="POST" onsubmit="return confirm('Hapus {{ $subStaf->name }}?')">
                                                        @csrf @method('DELETE')
                                                        <button class="bg-rose-500 text-white p-1 rounded-lg shadow-md hover:bg-rose-600">
                                                            <i data-lucide="trash-2" size="10"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="flex flex-col items-center justify-center text-center">
                <i data-lucide="sitemap" class="text-slate-200 mb-4" size="64"></i>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Belum ada struktur organisasi</p>
            </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH (ADMIN ONLY) --}}
@if(Auth::user()->role == 'admin')
<div id="modalTambah" class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white rounded-[2.5rem] w-full max-w-md p-8 shadow-2xl animate-modal relative">
        <button onclick="closeModalTambah()" class="absolute top-6 right-6 text-slate-300 hover:text-slate-600">
            <i data-lucide="x" size="24"></i>
        </button>

        <div class="mb-6">
            <h3 class="text-xl font-black text-slate-800">Tambah Anggota</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">Staf Baru CareHub</p>
        </div>

        <form action="{{ route('admin.struktur.simpan') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Nama Lengkap</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Password</label>
                    <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Role</label>
                    <select name="role" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
                        <option value="admin">Admin</option>
                        <option value="sekretariat">Sekretariat</option>
                        <option value="bendahara">Bendahara</option>
                        <option value="karyawan">Karyawan</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Jabatan</label>
                    <input type="text" name="jabatan" placeholder="Kepala Divisi" required class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
                </div>
            </div>

            <div>
                <label class="text-[10px] font-black uppercase text-slate-400 ml-1">Atasan Langsung</label>
                <select name="parent_id" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-none focus:ring-2 focus:ring-blue-500 text-sm font-bold">
                    <option value="">-- Pucuk Pimpinan --</option>
                    @foreach(\App\Models\User::all() as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->jabatan }})</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-100 transition-all text-xs uppercase tracking-widest mt-4">
                Simpan & Update Bagan
            </button>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    function openModalTambah() {
        const modal = document.getElementById('modalTambah');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModalTambah() {
        const modal = document.getElementById('modalTambah');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>
@endpush
@endsection