@extends('layouts.operator')

@section('title', 'Facility Status - Chloe')

@section('content')
<h1>Facility Status</h1>
<p class="sub">Update availability so students only book what can be used</p>

@if (session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif

<div class="legend">
    <span>{{ $facilities->where('status', 'active')->count() }} active</span>
    <span>{{ $facilities->where('status', 'maintenance')->count() }} under repair</span>
    <span>{{ $facilities->where('status', 'inactive')->count() }} inactive</span>
</div>

<div class="fgrid">
    @foreach ($facilities as $f)
        <article class="fc">
            <header>
                <div>
                    <h4>{{ $f->name }}</h4>
                    <small>Kapasitas {{ $f->capacity }} orang
                        @if ($f->open_reports_count) · {{ $f->open_reports_count }} laporan terbuka @endif
                    </small>
                </div>
                <span class="badge s-{{ $f->status }}">
                {{ ['active'=>'Active','maintenance'=>'Under repair','inactive'=>'Inactive'][$f->status] ?? ucfirst($f->status) }}                  </span>
            </header>
            <div class="seg" role="group">
                @foreach (['active' => 'Active', 'maintenance' => 'Under repair', 'inactive' => 'Inactive'] as $value => $label)
                    <form method="POST" action="{{ route('petugas.facility-status.set', $f) }}">
                        @csrf
                        <input type="hidden" name="status" value="{{ $value }}">
                        <button type="submit" aria-pressed="{{ $f->status === $value ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    </form>
                @endforeach
            </div>
        </article>
    @endforeach
</div>
@endsection