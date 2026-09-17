@extends('layouts.app')

@section('title', 'Dashboard Admin - Chloe')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-sm rounded-2xl p-8">
        <h1 class="text-2xl font-semibold text-chloe-700 mb-2">
            Halo, {{ Auth::user()->name }} 👋
        </h1>
        <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-600 mb-4">
            Admin
        </span>
        <p class="text-gray-600">
            Di sini nanti Anda mengelola data fasilitas, akun pengguna &amp; petugas, serta melihat rekap.
        </p>
        <p class="text-sm text-gray-400 mt-3">
            Fitur manajemen akan ditambahkan di tahap berikutnya.
        </p>
    </div>
</div>
@endsection