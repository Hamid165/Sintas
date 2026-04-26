@extends('errors.layout')

@section('title', '503 - Dalam Pemeliharaan')
@section('code', '503')
@section('heading', 'Sedang Dalam Pemeliharaan')
@section('description', 'Sistem CareHub sedang dalam proses pemeliharaan dan peningkatan layanan. Kami akan segera kembali. Terima kasih atas kesabaranmu!')

@section('code-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('badge-bg', 'linear-gradient(135deg, #EFF6FF, #EEF2FF)')
@section('btn-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('blob1-color', '#3B82F6')
@section('blob2-color', '#6366F1')
@section('blob3-color', '#93C5FD')

@section('icon')
    <i data-lucide="wrench" style="width:32px;height:32px;color:#3B82F6;"></i>
@endsection
