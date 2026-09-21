@extends('layouts.operator')

@section('title', 'Reservation Queue - Chloe')

@section('content')
<div x-data="{ pick: null, mode: null }">
    <h1>Reservation Queue</h1>
    <p class="sub">Approve, reject, or cancel incoming reservations</p>

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    <div class="toolbar">
        @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $value]) }}"
               class="chip" aria-pressed="{{ $status === $value ? 'true' : 'false' }}">{{ $label }}</a>
        @endforeach
        <span class="grow"></span>
        <form method="GET" style="display:contents">
            <input type="hidden" name="status" value="{{ $status }}">
            <input class="search" type="search" name="q" placeholder="Cari nama, fasilitas, keperluan" value="{{ request('q') }}">
            <select class="sel" name="facility_id" onchange="this.form.submit()">
                <option value="">Facility</option>
                @foreach ($facilities as $f)
                    <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
            <input class="sel" type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()">
        </form>
    </div>

    <div class="tablewrap">
        <table>
            <thead>
                <tr><th>Requester</th><th>Facility</th><th>Time Slot</th><th>Purpose</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse ($reservations as $r)
                    <tr :class="pick === {{ $r->id }} ? 'sel-row' : ''">
                        <td>{{ $r->user->name ?? '-' }}</td>
                        <td>{{ $r->facility->name ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($r->reservation_date)->translatedFormat('d M Y') }}
                            <small>{{ \Carbon\Carbon::parse($r->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($r->end_time)->format('H:i') }}</small>
                        </td>
                        <td>{{ $r->purpose }}</td>
                        <td><span class="badge b-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        <td>
                            @if ($r->status === 'pending')
                                <div class="act">
                                    <form method="POST" action="{{ route('petugas.reservations.approve', $r) }}">
                                        @csrf
                                        <button type="submit" class="mini go">Approve</button>
                                    </form>
                                    <button type="button" class="mini no"
                                        x-on:click="pick = {{ $r->id }}; mode = 'reject'">Reject</button>
                                </div>
                            @elseif ($r->status === 'approved')
                                <button type="button" class="mini no"
                                    x-on:click="pick = {{ $r->id }}; mode = 'cancel'">Cancel</button>
                            @else
                                <small>{{ $r->cancel_reason ?? '—' }}</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:#7c6367;padding:28px">Tidak ada reservasi di tab ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($status !== 'rejected')
        <section class="reason">
            <h3>Cancellation / Rejection Reason</h3>
            <div class="target" x-text="pick ? ((mode === 'cancel' ? 'Membatalkan' : 'Menolak') + ' reservasi #' + pick) : 'Pilih Reject atau Cancel pada salah satu reservasi untuk menulis alasan.'"></div>
            <form method="POST" x-bind:action="pick ? ('/petugas/reservations/' + pick + '/' + mode) : '#'">
                @csrf
                <textarea name="cancel_reason" placeholder="Tulis alasan yang akan dilihat pemohon" x-bind:disabled="!pick" required></textarea>
                <div class="btns">
                    <button type="button" class="btn ghost" x-on:click="pick = null; mode = null" x-bind:disabled="!pick">Cancel</button>
                    <button type="submit" class="btn main" x-bind:disabled="!pick">Confirm</button>
                </div>
            </form>
        </section>
    @endif
</div>
@endsection