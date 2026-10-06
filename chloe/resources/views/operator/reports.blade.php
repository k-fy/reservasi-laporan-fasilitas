@extends('layouts.operator')

@section('title', 'Report Queue - Chloe')

@section('content')
@php
    $statusLabels = [
        \App\Models\Report::STATUS_NEW      => 'New',
        \App\Models\Report::STATUS_PROGRESS => 'In Progress',
        \App\Models\Report::STATUS_RESOLVED => 'Resolved',
        \App\Models\Report::STATUS_REJECTED => 'Rejected',
    ];

    $badgeClasses = [
        \App\Models\Report::STATUS_NEW      => 'new',
        \App\Models\Report::STATUS_PROGRESS => 'progress',
        \App\Models\Report::STATUS_RESOLVED => 'resolved',
        \App\Models\Report::STATUS_REJECTED => 'rejected',
    ];
@endphp

<div x-data="{ pick: null, mode: null }">
    <h1>Report Queue</h1>
    <p class="sub">Review, update, and resolve facility reports</p>

    @if (session('success'))
        <div class="flash">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="flash">{{ $errors->first() }}</div>
    @endif

    <div class="toolbar">
        @foreach ($statusLabels as $value => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $value]) }}"
               class="chip" aria-pressed="{{ $status === $value ? 'true' : 'false' }}">{{ $label }}</a>
        @endforeach
        <span class="grow"></span>
        <form method="GET" style="display:contents">
            <input type="hidden" name="status" value="{{ $status }}">
            <input class="search" type="search" name="q" placeholder="Cari judul, fasilitas, pelapor" value="{{ request('q') }}">
            <select class="sel" name="facility_id" onchange="this.form.submit()">
                <option value="">Facility</option>
                @foreach ($facilities as $f)
                    <option value="{{ $f->id }}" {{ request('facility_id') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                @endforeach
            </select>
            <input class="sel" type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()">
        </form>
    </div>

    <div class="cards">
        @forelse ($reports as $r)
            <div class="rc" :class="pick === {{ $r->id }} ? 'picked' : ''">
                <div class="thumb">
                    @if ($r->photo)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($r->photo) }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h3l2-2h6l2 2h3v11H4z"/><circle cx="12" cy="13" r="3.5"/></svg>
                    @endif
                </div>
                <div>
                    <h4>{{ $r->facility->name ?? '-' }}</h4>
                    <p>{{ $r->description }}
                        @if ($r->resolution_notes)<br><em>Catatan: {{ $r->resolution_notes }}</em>@endif
                    </p>
                    <div class="foot">
                        <span>{{ $r->user->name ?? '-' }} · {{ $r->created_at->translatedFormat('d M Y') }}</span>

                        {{-- Tab New: cuma tombol Start --}}
                        @if ($r->status === \App\Models\Report::STATUS_NEW)
                            <form method="POST" action="{{ route('petugas.reports.start', $r) }}" style="margin-left:auto">
                                @csrf
                                <button type="submit" class="mini">Start</button>
                            </form>
                        @endif
                    </div>

                    {{-- Tab In Progress: 2 tombol Resolved & Reject --}}
                    @if ($r->status === \App\Models\Report::STATUS_PROGRESS)
                        <div class="foot" style="margin-top:8px">
                            <button type="button" class="mini go"
                                x-on:click="pick = {{ $r->id }}; mode = 'resolve'">Resolved</button>
                            <button type="button" class="mini no"
                                x-on:click="pick = {{ $r->id }}; mode = 'reject'">Reject</button>
                        </div>

                        @if ($r->facility)
                            @php $fs = $r->facility->status; @endphp
                            <div class="foot" style="margin-top:8px;flex-wrap:wrap;gap:8px">
                                <span>Status fasilitas:
                                    <strong>{{ ['active' => 'Aktif', 'maintenance' => 'Dalam perbaikan', 'inactive' => 'Nonaktif'][$fs] ?? ucfirst($fs) }}</strong>
                                </span>
                                @if ($fs !== 'maintenance')
                                    <form method="POST" action="{{ route('petugas.facility-status.set', $r->facility) }}"
                                          onsubmit="return confirm('Tandai fasilitas ini sebagai Dalam perbaikan?')">
                                        @csrf
                                        <input type="hidden" name="status" value="maintenance">
                                        <button type="submit" class="mini no">Dalam perbaikan</button>
                                    </form>
                                @endif
                                @if ($fs !== 'active')
                                    <form method="POST" action="{{ route('petugas.facility-status.set', $r->facility) }}"
                                          onsubmit="return confirm('Kembalikan fasilitas ini ke Aktif?')">
                                        @csrf
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit" class="mini go">Aktifkan</button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
                @php
                    $badgeClass = $badgeClasses[$r->status] ?? 'new';
                    $badgeLabel = $statusLabels[$r->status] ?? ucfirst($r->status);
                @endphp
                <span class="badge b-{{ $badgeClass }}">{{ $badgeLabel }}</span>
            </div>
        @empty
            <div class="card empty" style="grid-column:1/-1;min-height:0;text-align:center">Tidak ada laporan di tab ini.</div>
        @endforelse
    </div>

    {{-- Panel Resolution Notes: cuma muncul di tab In Progress --}}
    @if ($status === \App\Models\Report::STATUS_PROGRESS)
        <section class="reason" x-show="pick" x-cloak x-transition>
            <h3>Resolution Notes</h3>
            <div class="target"
                 x-text="mode === 'reject' ? 'Menolak laporan #' + pick : 'Menyelesaikan laporan #' + pick"></div>

            <form method="POST"
                  x-bind:action="pick ? ('/petugas/reports/' + pick + '/' + (mode === 'reject' ? 'reject' : 'resolve')) : '#'">
                @csrf
                <textarea name="resolution_notes"
                    x-bind:placeholder="mode === 'reject' ? 'Alasan penolakan laporan ini' : 'Apa yang sudah diperbaiki?'"
                    required></textarea>
                <div class="btns">
                    <button type="button" class="btn ghost" x-on:click="pick = null; mode = null">Cancel</button>
                    <button type="submit" class="btn main"
                        x-text="mode === 'reject' ? 'Save & Reject' : 'Save & Resolved'"></button>
                </div>
            </form>
        </section>
    @endif
</div>
@endsection