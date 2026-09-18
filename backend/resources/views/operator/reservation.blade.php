@extends('layouts.operator')

@section('title', 'Reservation Queue - Chloe')

@section('content')
<div x-data="{ selected: null }">

    <h1 class="text-3xl font-serif italic text-white mb-1">Reservation Queue</h1>
    <p class="text-rose-100 mb-6">Approved, reject, or cancel incoming reservations</p>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700 bg-green-100 rounded-lg px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs + Filter --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex gap-2">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                <a href="{{ route('petugas.reservations', ['status' => $value]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-semibold transition
                          {{ $status === $value ? 'bg-rose-300 text-white' : 'bg-rose-100 text-neutral-600' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form method="GET" class="flex gap-2">
            <input type="hidden" name="status" value="{{ $status }}">
            <select name="facility_id" onchange="this.form.submit()"
                class="rounded-full text-sm px-4 py-1.5 bg-rose-100 text-neutral-600 font-semibold border-none">
                <option value="">Facility</option>
                @foreach ($facilities as $facility)
                    <option value="{{ $facility->id }}" {{ request('facility_id') == $facility->id ? 'selected' : '' }}>
                        {{ $facility->name }}
                    </option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" onchange="this.form.submit()"
                class="rounded-full text-sm px-4 py-1.5 bg-rose-100 text-neutral-600 font-semibold border-none">
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl overflow-hidden mb-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-neutral-500 border-b">
                    <th class="px-4 py-3">Requester</th>
                    <th class="px-4 py-3">Facility</th>
                    <th class="px-4 py-3">Time Slot</th>
                    <th class="px-4 py-3">Purpose</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $reservation)
                    <tr class="border-b last:border-0"
                        :class="selected === {{ $reservation->id }} ? 'bg-rose-50' : ''">
                        <td class="px-4 py-3">{{ $reservation->user->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $reservation->facility->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($reservation->reservation_date)->format('d/m/Y') }}<br>
                            <span class="text-neutral-400">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('H:i') }}
                                -
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-4 py-3">{{ $reservation->purpose }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-600">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($status === 'pending')
                                <div class="flex gap-2">
                                    <form method="POST" action="{{ route('petugas.reservations.approve', $reservation) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" x-on:click="selected = {{ $reservation->id }}"
                                        class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-xs font-semibold">
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-neutral-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-neutral-400">
                            Belum ada reservasi dengan status ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Panel reject reason --}}
    @if ($status === 'pending')
        <form method="POST"
              x-bind:action="selected ? `/petugas/reservations/${selected}/reject` : '#'"
              class="bg-rose-100 rounded-2xl p-6">
            @csrf
            <h2 class="font-semibold text-neutral-800 mb-3">Cancellation / Rejection Reason</h2>
            <textarea name="cancel_reason" rows="4" required
                class="w-full rounded-xl border-2 border-dashed border-rose-300 bg-white px-4 py-3
                       text-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-300"
                placeholder="Tulis alasan penolakan di sini..."></textarea>

            <div class="flex gap-3 mt-4">
                <button type="button" x-on:click="selected = null"
                    class="px-5 py-2 rounded-full bg-white text-neutral-600 font-semibold">
                    Cancel
                </button>
                <button type="submit" x-bind:disabled="!selected"
                    class="px-5 py-2 rounded-full bg-rose-400 text-white font-semibold disabled:opacity-50">
                    Confirm
                </button>
            </div>
        </form>
    @endif
</div>
@endsection