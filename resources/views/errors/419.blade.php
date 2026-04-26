@extends('errors.layout')

@section('title', '419 - Sesi Kedaluwarsa')
@section('code', '419')
@section('heading', 'Sesi Kedaluwarsa')
@section('description', 'Token keamanan halamanmu sudah tidak valid atau sesimu telah berakhir. Silakan muat ulang halaman dan coba lagi.')

@section('code-gradient', 'linear-gradient(135deg, #0EA5E9, #2563EB)')
@section('badge-bg', 'linear-gradient(135deg, #E0F2FE, #DBEAFE)')
@section('btn-gradient', 'linear-gradient(135deg, #0EA5E9, #2563EB)')
@section('blob1-color', '#0EA5E9')
@section('blob2-color', '#2563EB')
@section('blob3-color', '#38BDF8')

@section('icon')
    <i data-lucide="clock" style="width:32px;height:32px;color:#0EA5E9;"></i>
@endsection
