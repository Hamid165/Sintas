@extends('layouts.admin')
@section('title', 'Audit - CareHub')

@section('content')
<div class="space-y-6 w-full">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center bg-white p-6 lg:p-8 rounded-[2rem] shadow-sm gap-4">
        <div class="w-full lg:w-auto">
            <h3 class="text-xl font-black text-slate-800 uppercase tracking-tighter">Menu Audit</h3>
            <p class="text-xs text-gray-500 mt-1 uppercase font-bold tracking-widest">Manajemen Sekretariat & Audit Keuangan</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'sekretariat')
        <!-- Rekap Kesekretariatan Card -->
        <a href="{{ route('admin.audit.sekretariat') }}" class="group bg-white rounded-[2rem] shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-orange-500">
            <div class="h-40 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-start px-8 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="file-text" size="140" class="absolute -right-10 -top-10 text-orange-700"></i>
                </div>
                <div class="relative z-10 flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 rounded-[1.5rem] flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="folder-open" size="40" class="text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-white uppercase tracking-tighter leading-tight drop-shadow-sm">Audit<br>Sekretariat</h4>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <p class="text-xs font-bold text-orange-500 mb-2 uppercase tracking-widest">Rekap Kesekretariatan</p>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed font-medium">Pengelolaan administrasi surat masuk dan surat keluar. Dokumentasi referensi nomor surat, perihal, dan instansi pengirim atau tujuan.</p>
                <div class="flex items-center gap-2 text-orange-600 font-bold text-sm group-hover:translate-x-2 transition-transform">
                    <span>Buka Modul</span>
                    <i data-lucide="arrow-right" size="16"></i>
                </div>
            </div>
        </a>
        @endif

        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara')
        <!-- Audit Keuangan Card -->
        <a href="{{ route('admin.audit.keuangan') }}" class="group bg-white rounded-[2rem] shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-blue-600">
            <div class="h-40 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-start px-8 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="circle-dollar-sign" size="140" class="absolute -right-10 -top-10 text-blue-800"></i>
                </div>
                <div class="relative z-10 flex items-center gap-6">
                    <div class="w-20 h-20 bg-white/20 rounded-[1.5rem] flex items-center justify-center backdrop-blur-sm">
                        <i data-lucide="circle-dollar-sign" size="40" class="text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-white uppercase tracking-tighter leading-tight drop-shadow-sm">Audit<br>Keuangan</h4>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <p class="text-xs font-bold text-blue-600 mb-2 uppercase tracking-widest">Verifikasi Transaksi</p>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed font-medium">Menghubungkan surat resmi dengan pencatatan keuangan. Referensi dan verifikasi dokumen pengeluaran untuk dasar pertanggungjawaban.</p>
                <div class="flex items-center gap-2 text-blue-600 font-bold text-sm group-hover:translate-x-2 transition-transform">
                    <span>Buka Modul</span>
                    <i data-lucide="arrow-right" size="16"></i>
                </div>
            </div>
        </a>
        @endif
    </div>
</div>
@endsection
