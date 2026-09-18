@extends('layouts.operator')

@section('title', 'Reports Queue - Chloe')

@section('content')
<div x-data="{ selected: null }">

    <h1 class="text-3xl font-serif italic text-white mb-1">Reports Queue</h1>
    <p class="text-rose-100 mb-6">Review dan tandai laporan kerusakan yang masuk sebagai selesai.</p>

    @if (session('success'))
        <div class="mb-4 text-sm text-green-700 bg-green-100 rounded-lg px-4 py-2">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabs status --}}
    <div class="flex gap-2 mb-6">
        @foreach (['baru' => 'New', 'diproses' => 'In Progress', 'selesai' => 'Resolved'] as $value => $label)
            <a href="{{ route('petugas.reports', ['status' => $value]) }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold transition
                      {{ $status === $value ? 'bg-rose-300 text-white' : 'bg-rose-100 text-neutral-600' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Grid report cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        @forelse ($reports as $report)
            <button type="button" x-on:click="selected = {{ $report->id }}"
                class="text-left bg-white rounded-2xl p-4 shadow-sm border-2 transition"
                :class="selected === {{ $report->id }} ? 'border-rose-400' : 'border-transparent'">
                <div class="flex items-start justify-between mb-2">
                    <div>
                        <p class="font-semibold text-neutral-800">{{ $report->facility->name ?? '-' }}</p>
                        <p class="text-xs text-neutral-400">oleh {{ $report->user->name ?? '-' }}</p>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-blue-100 text-blue-600">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                <p class="text-sm text-neutral-600 line-clamp-3">{{ $report->description }}</p>
            </button>
        @empty
            <p class="text-rose-100 col-span-2">Belum ada report dengan status ini.</p>
        @endforelse
    </div>

    {{-- Panel resolusi --}}
    @if ($status !== 'selesai')
        <form method="POST"
              x-bind:action="selected ? `/petugas/reports/${selected}/resolve` : '#'"
              class="bg-rose-100 rounded-2xl p-6">
            @csrf
            <h2 class="font-semibold text-neutral-800 mb-3">Resolution Notes</h2>
            <textarea name="resolution_notes" rows="4" required
                class="w-full rounded-xl border-2 border-dashed border-rose-300 bg-white px-4 py-3
                       text-neutral-700 focus:outline-none focus:ring-2 focus:ring-rose-300"
                placeholder="Tulis catatan penyelesaian di sini..."></textarea>

            <div class="flex gap-3 mt-4">
                <button type="button" x-on:click="selected = null"
                    class="px-5 py-2 rounded-full bg-white text-neutral-600 font-semibold">
                    Cancel
                </button>
                <button type="submit" x-bind:disabled="!selected"
                    class="px-5 py-2 rounded-full bg-rose-400 text-white font-semibold disabled:opacity-50">
                    Save &amp; Resolved
                </button>
            </div>
        </form>
    @endif
</div>
@endsection