@extends('layouts.operator')

@section('title', 'Facility Status - Chloe')

@section('content')
<h1>Facility Status</h1>
<p class="sub">Update availability so students only book what can be used</p>

@if (session('success'))
    <div class="flash">{{ session('success') }}</div>
@endif

<div class="legend">
    <span>{{ $facilities->where('status', 'available')->count() }} available</span>
    <span>{{ $facilities->where('status', 'inuse')->count() }} in use</span>
    <span>{{ $facilities->where('status', 'repair')->count() }} under repair</span>
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
                    {{ ['available'=>'Available','inuse'=>'In use','repair'=>'Under repair'][$f->status] ?? ucfirst($f->status) }}
                </span>
            </header>
            <div class="seg" role="group">
                @foreach (['available' => 'Available', 'inuse' => 'In use', 'repair' => 'Under repair'] as $value => $label)
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