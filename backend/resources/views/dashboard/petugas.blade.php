@extends('layouts.operator')

@section('title', 'Dashboard - Chloe')

@section('content')
<h1>Welcome back.</h1>
<p class="sub">Berikut yang perlu kamu tinjau hari ini.</p>

<div class="stats">
    <a href="{{ route('petugas.reservations') }}" class="stat">
        <span>Reservations pending</span>
        <strong>{{ $pendingReservations }}</strong>
    </a>
    <a href="{{ route('petugas.reports') }}" class="stat">
        <span>New reports</span>
        <strong>{{ $newReports }}</strong>
    </a>
    <a href="{{ route('petugas.facility-status') }}" class="stat">
        <span>Under repair</span>
        <strong>{{ $underRepair }}</strong>
    </a>
</div>

<div class="split">
    <section class="card">
        <h2>Approved bookings</h2>
        @if ($approvedBookings->isEmpty())
            <div class="empty">Belum ada reservasi yang disetujui.</div>
        @else
            <ul class="list">
                @foreach ($approvedBookings as $r)
                    <li>
                        <span>{{ $r->facility->name ?? '-' }}<small>{{ $r->user->name ?? '-' }} · {{ $r->purpose }}</small></span>
                        <span style="text-align:right">
                            {{ \Carbon\Carbon::parse($r->reservation_date)->isToday() ? 'Hari ini' : \Carbon\Carbon::parse($r->reservation_date)->translatedFormat('d M Y') }}
                            <small>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</small>
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <section class="card dark">
        <h2>Facility snapshot</h2>
        <ul class="list">
            @foreach ($facilitySnapshot as $f)
                <li>
                    <span>{{ $f->name }}</span>
                    <span>
                        <i class="dot" style="background: {{ $f->status === 'available' ? '#8fd3a0' : ($f->status === 'inuse' ? '#f1c27a' : '#f28aa0') }}"></i>
                        {{ ['available'=>'Available','inuse'=>'In use','repair'=>'Under repair'][$f->status] ?? ucfirst($f->status) }}
                    </span>
                </li>
            @endforeach
        </ul>
    </section>
</div>

<div class="pillrow">
    <a href="{{ route('petugas.reservations') }}" class="pill">Review Reservations</a>
    <a href="{{ route('petugas.reports') }}" class="pill light">Review Reports</a>
    <a href="{{ route('petugas.facility-status') }}" class="pill grey">Facility Status</a>
</div>
@endsection