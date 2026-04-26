@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')
@section('code', '404')
@section('heading', 'Halaman Tidak Ditemukan')
@section('description', 'Maaf, halaman yang kamu cari tidak ada atau mungkin sudah dipindahkan. Periksa kembali URL yang kamu masukkan.')

@section('code-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('badge-bg', 'linear-gradient(135deg, #EFF6FF, #EEF2FF)')
@section('btn-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('blob1-color', '#3B82F6')
@section('blob2-color', '#6366F1')
@section('blob3-color', '#93C5FD')

@section('icon')
    <i data-lucide="map-pin-off" style="width:32px;height:32px;color:#3B82F6;"></i>
@endsection
