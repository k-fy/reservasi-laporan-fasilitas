@extends('layouts.operator')

@section('title', 'Dashboard Petugas - Chloe')

@section('content')
<div class="bg-white shadow-sm rounded-2xl p-8 max-w-4xl">
    <h1 class="text-2xl font-semibold text-neutral-800 mb-2">
        Halo, {{ Auth::user()->name }} 👋
    </h1>
    <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-blue-100 text-blue-600 mb-4">
        Petugas
    </span>
    <p class="text-gray-600">
        Di sini nanti Anda memproses antrian reservasi &amp; laporan kerusakan yang masuk.
    </p>
    <p class="text-sm text-gray-400 mt-3">
        Fitur antrian akan ditambahkan di tahap berikutnya.
    </p>
</div>
@endsection