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
        <!-- Rekap Kesekretariatan Card -->
        <a href="{{ route('admin.audit.sekretariat') }}" class="group bg-white rounded-[2rem] shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-orange-500">
            <div class="h-32 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="file-text" size="120" class="absolute -right-10 -top-10 text-orange-700"></i>
                </div>
                <div class="relative z-10">
                    <i data-lucide="folder-open" size="48" class="text-white mx-auto mb-2"></i>
                </div>
            </div>
            <div class="p-6">
                <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Rekap Kesekretariatan</h4>
                <p class="text-xs text-gray-600 mb-4 leading-relaxed">Pengelolaan administrasi surat masuk dan surat keluar. Dokumentasi nomor surat, perihal, dan pengirim/tujuan.</p>
                <div class="flex items-center gap-2 text-orange-600 font-bold text-sm group-hover:translate-x-2 transition-transform">
                    <span>Buka Modul</span>
                    <i data-lucide="arrow-right" size="16"></i>
                </div>
            </div>
        </a>

        <!-- Audit Keuangan Card -->
        <a href="{{ route('admin.audit.keuangan') }}" class="group bg-white rounded-[2rem] shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 border-2 border-transparent hover:border-blue-600">
            <div class="h-32 bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity">
                    <i data-lucide="shield" size="120" class="absolute -right-10 -top-10 text-blue-800"></i>
                </div>
                <div class="relative z-10">
                    <i data-lucide="lock" size="48" class="text-white mx-auto mb-2"></i>
                </div>
            </div>
            <div class="p-6">
                <h4 class="text-lg font-black text-slate-800 uppercase tracking-tight mb-2">Audit Keuangan</h4>
                <p class="text-xs text-gray-600 mb-4 leading-relaxed">Menghubungkan surat resmi dengan pencatatan keuangan. Referensi dokumen untuk transparansi dan pertanggungjawaban hukum.</p>
                <div class="flex items-center gap-2 text-blue-600 font-bold text-sm group-hover:translate-x-2 transition-transform">
                    <span>Buka Modul</span>
                    <i data-lucide="arrow-right" size="16"></i>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection
