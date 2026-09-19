@extends('layouts.app')
@section('title', 'Reservation - Chloe')
@section('content')

<div class="min-h-screen bg-[#4A4A4A] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-xl bg-[#FCF1F0] rounded-3xl shadow-xl p-10">

        {{-- Title --}}
        <h1 class="text-center font-['Georgia',serif] italic text-4xl text-[#4b4848] mb-2">Reservation</h1>
        <div class="border-b border-[#4b4848]/30 mb-8">
            @if($errors->any())
                <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="mb-6 space-y-2 text-sm text-[#4b4848] font-['Poppins',sans-serif]">
            <div class="flex justify-between">
                <span class="font-semibold">Facility</span>
                <span>{{ $facility->name }}</span>
            </div>
            <div class="border-b border-[#4b4848]/20"></div>
            <div class="flex justify-between">
                <span class="font-semibold">Date of Booking</span>
                <span>{{ \Carbon\Carbon::parse($date)->format('d F Y') }}</span>
            </div>
            <div class="border-b border-[#4b4848]/20"></div>
            <div class="flex justify-between items-center">
                <span class="font-semibold">Time Slot</span>
                <div class="flex items-center gap-2">
                    <span class="bg-[#c9a0a8] text-white px-4 py-1 rounded-full text-sm">{{ $start_time }}</span>
                    <span>-</span>
                    <span class="bg-[#c9a0a8] text-white px-4 py-1 rounded-full text-sm">{{ $end_time }}</span>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('booking.store') }}" class="space-y-4 font-['Poppins',sans-serif]">
            @csrf
            <input type="hidden" name="facility_id"      value="{{ $facility->id }}">
            <input type="hidden" name="reservation_date" value="{{ $date }}">
            <input type="hidden" name="start_time"       value="{{ $start_time }}">
            <input type="hidden" name="end_time"         value="{{ $end_time }}">

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Requester Name</label>
                <input type="text" name="requester_name"
                       value="{{ old('requester_name', auth()->user()->name) }}"
                       class="w-full rounded-xl border border-[#4b4848]/30 bg-[#e8d5d8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
                @error('requester_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">NIM/NIP</label>
                <input type="text" name="nim_nip"
                       value="{{ old('nim_nip', auth()->user()->nim_nip)  }}"
                       class="w-full rounded-xl border border-[#4b4848]/30 bg-[#e8d5d8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
                @error('nim_nip')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Active WhatsApp Number</label>
                <input type="text" name="whatsapp"
                       value="{{ old('whatsapp') }}"
                       placeholder="08xx-xxxx-xxxx"
                       class="w-full rounded-xl border border-[#4b4848]/30 bg-white px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
                @error('whatsapp')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Purpose</label>
                <textarea name="purpose" rows="5"
                          class="w-full rounded-xl border border-[#4b4848]/30 bg-white px-4 py-3 text-[#4b4848] resize-none focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]"
                          x-data="{ count: 0 }"
                          x-on:input="count = $event.target.value.trim() === '' ? 0 : $event.target.value.trim().split(/\s+/).length"
                          >{{ old('purpose') }}</textarea>
                <p class="text-right text-xs text-[#4b4848]/60 mt-1">Maximum 250 words</p>
                @error('purpose')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full bg-[#c9a0a8] hover:bg-[#b8888f] text-white font-semibold py-3 rounded-full text-base transition cursor-pointer">
                Apply for Booking
            </button>
        </form>

    </div>
</div>

@if(session('submitted'))
<div x-data="{ show: false }" 
     x-init="setTimeout(() => show = true, 50)"
    id="success-modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">

    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         class="absolute inset-0 bg-black/50">
    </div>

    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="relative bg-[#c9a0a8] rounded-3xl shadow-xl p-10 max-w-sm w-full text-center">
        <h1 class="font-['Georgia',serif] italic text-3xl text-[#4b4848] mb-6">
            Submitted Successfully!
        </h1>
        <p class="text-[#4b4848] text-base leading-relaxed mb-8">
            We have received your application.<br>
            You can track the <strong>status</strong> in your
            account profile or wait for updates via <strong>WhatsApp</strong>
        </p>
        <a href="{{ route('dashboard') }}">
            <button class="w-full bg-[#FCF1F0] text-[#4b4848] font-['Georgia',serif] italic text-lg py-3 rounded-full underline hover:bg-white transition">
                I understand!
            </button>
        </a>
    </div>
</div>
@endif

@endsection