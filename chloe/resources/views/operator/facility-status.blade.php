@extends('layouts.operator')

@section('title', 'Facility Status - Chloe')

@section('content')
<h1>Facility Status</h1>
<p class="sub">Update availability so students only book what can be used</p>

@if (session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif

@if (session('error'))
    <div class="flash" style="background:#fff1f2;color:#9b2f45;border-color:#fecdd3">{{ session('error') }}</div>
@endif

<div class="legend">
    <span>{{ $facilities->where('status', 'active')->count() }} active</span>
    <span>{{ $facilities->where('status', 'maintenance')->count() }} under repair</span>
    <span>{{ $facilities->where('status', 'inactive')->count() }} inactive (diatur admin)</span>
</div>

<div class="fgrid">
    @foreach ($facilities as $f)
        <article class="fc" @if ($f->status === 'inactive') style="opacity:.75" @endif>
            <header>
                <div>
                    <h4>{{ $f->name }}</h4>
                    <small>Kapasitas {{ $f->capacity }} {{ $f->type === 'alat' ? 'pcs' : 'orang' }}
                        @if ($f->open_reports_count) · {{ $f->open_reports_count }} laporan terbuka @endif
                    </small>
                </div>
                <span class="badge s-{{ $f->status }}">
                    {{ ['active' => 'Active', 'maintenance' => 'Under repair', 'inactive' => 'Inactive'][$f->status] ?? ucfirst($f->status) }}
                </span>
            </header>

            @if ($f->status === 'inactive')
                {{-- Fasilitas yang dinonaktifkan hanya bisa diaktifkan kembali oleh admin --}}
                <p style="margin:0;padding:10px 14px;border-radius:12px;background:#f5f2f2;color:#7c6367;font-size:13px;display:flex;align-items:center;gap:8px">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Dinonaktifkan oleh admin
                </p>
            @else
                {{-- Petugas hanya dapat mengubah status antara Active dan Under repair --}}
                <div class="seg" role="group" style="grid-template-columns:repeat(2,1fr)">
                    @foreach (['active' => 'Active', 'maintenance' => 'Under repair'] as $value => $label)
                        <form method="POST" action="{{ route('petugas.facility-status.set', $f) }}">
                            @csrf
                            <input type="hidden" name="status" value="{{ $value }}">
                            <button type="submit" aria-pressed="{{ $f->status === $value ? 'true' : 'false' }}">
                                {{ $label }}
                            </button>
                        </form>
                    @endforeach
                </div>
            @endif
        </article>
    @endforeach
</div>
@endsection