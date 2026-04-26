@extends('errors.layout')

@section('title', '403 - Akses Ditolak')
@section('code', '403')
@section('heading', 'Akses Ditolak')
@section('description', 'Kamu tidak memiliki izin untuk mengakses halaman ini. Silakan hubungi administrator jika kamu merasa ini adalah kesalahan.')

@section('code-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('badge-bg', 'linear-gradient(135deg, #EFF6FF, #EEF2FF)')
@section('btn-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('blob1-color', '#3B82F6')
@section('blob2-color', '#6366F1')
@section('blob3-color', '#93C5FD')

@section('icon')
    <i data-lucide="shield-x" style="width:32px;height:32px;color:#3B82F6;"></i>
@endsection
