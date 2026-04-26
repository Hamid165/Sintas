@extends('errors.layout')

@section('title', '500 - Kesalahan Server')
@section('code', '500')
@section('heading', 'Terjadi Kesalahan Server')
@section('description', 'Ups! Ada sesuatu yang tidak beres di sisi server kami. Tim teknis kami sedang bekerja untuk memperbaikinya. Silakan coba lagi beberapa saat.')

@section('code-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('badge-bg', 'linear-gradient(135deg, #EFF6FF, #EEF2FF)')
@section('btn-gradient', 'linear-gradient(135deg, #3B82F6, #6366F1)')
@section('blob1-color', '#3B82F6')
@section('blob2-color', '#6366F1')
@section('blob3-color', '#93C5FD')

@section('icon')
    <i data-lucide="server-crash" style="width:32px;height:32px;color:#3B82F6;"></i>
@endsection
