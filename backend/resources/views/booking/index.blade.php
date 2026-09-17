@extends('layouts.app')

@section('title', 'Booking')

@section('content')
<div class="bg-[#4A4A4A] min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-6 py-10">

        <!-- Title -->
        <h1 class="text-center text-5xl font-serif italic text-[#F3D9DC] mb-3">Booking</h1>
        <div class="border-b-2 border-[#F3D9DC] w-full mx-auto mb-10"></div>

        <!-- Search & Filter -->
        <form method="GET" action="{{ route('booking.index') }}" class="flex flex-col md:flex-row gap-4 mb-10">
            
            <!-- Input Search Text -->
            <div class="flex-1 relative">
                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-neutral-400">
                    [S]
                </span>
                <input type="text" 
                    name="q" 
                    value="{{ request('q') }}"
                    placeholder="Search facilities..."
                    class="w-full rounded-full pl-12 pr-5 py-3 bg-[#FCF1F0] text-neutral-700 placeholder-neutral-400 focus:outline-none border-0 focus:ring-2 focus:ring-[#EDD3D6]">
            </div>

            <!-- Dropdown Filter Category (Type) -->
            <div class="w-full md:w-56">
                <select name="type" 
                        class="w-full rounded-full px-5 py-3 bg-[#FCF1F0] text-neutral-700 focus:outline-none border-0 focus:ring-2 focus:ring-[#EDD3D6] cursor-pointer font-medium">
                    <option value="">All Categories</option>
                    <option value="ruangan" {{ request('type') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                    <option value="lab" {{ request('type') == 'lab' ? 'selected' : '' }}>Lab</option>
                    <option value="gedung" {{ request('type') == 'gedung' ? 'selected' : '' }}>Gedung</option>
                    <option value="alat" {{ request('type') == 'alat' ? 'selected' : '' }}>Alat</option>
                    <option value="area terbuka" {{ request('type') == 'area terbuka' ? 'selected' : '' }}>Area Terbuka</option>
                </select>
            </div>

            <!-- Tombol Submit Filter -->
            <button type="submit" 
                    class="bg-[#EDD3D6] hover:bg-[#e4bece] rounded-full px-8 py-3 font-semibold text-neutral-800 flex items-center justify-center gap-2 whitespace-nowrap transition">
                Filter
            </button>
            
            <!-- Tombol Reset Filter -->
            @if(request('q') || request('type'))
                <a href="{{ route('booking.index') }}" 
                   class="border border-white bg-neutral-600 hover:bg-neutral-700 text-white rounded-full px-6 py-3 font-semibold flex items-center justify-center transition text-sm">
                        Reset
                </a>
            @endif
        </form>
        

        <!-- Grid Fasilitas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($facilities as $facility)

                <div class="bg-[#FCF1F0] rounded-3xl overflow-hidden flex flex-col p-4 shadow-sm border border-[#EDD3D6]/40 hover:shadow-md transition">
                    
                    <div class="w-full h-48 rounded-2xl overflow-hidden mb-4 relative">
                        <img src="{{ $facility->image ? asset('storage/'.$facility->image) : 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=600&q=80' }}"
                            alt="{{ $facility->name }}"
                            class="w-full h-full object-cover">
                        
                        @if($facility->status !== 'active')
                            <span class="absolute top-3 right-3 bg-rose-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                                {{ $facility->status }}
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col flex-1 px-1">
                        <h3 class="font-bold text-lg text-neutral-800 mb-1 leading-snug">
                            {{ $facility->name }}
                        </h3>
                        
                        <p class="text-xs text-neutral-600 mb-6 flex-1 leading-relaxed line-clamp-3">
                            {{ $facility->description }}
                        </p>

                        <div class="flex justify-between items-center text-xs text-neutral-700 font-medium pt-3 border-t border-neutral-300/40">
                            <span>Capacity : {{ $facility->capacity }} {{ $facility->type === 'alat' ? 'pcs' : 'pax' }}</span>
                            <a href="{{ route('booking.show', $facility) }}" class="font-bold text-neutral-800 flex items-center gap-1 hover:underline">
                                Details <span>›</span>
                            </a>
                        </div>
                    </div>

                </div>

            @empty
                <div class="col-span-1 md:col-span-3 text-center py-20">
                    <p class="text-lg font-medium text-[#F3D9DC]">Belum ada fasilitas yang ditambahkan.</p>
                </div>
            @endforelse
        </div>

        @if($facilities->hasPages())
            <div class="mt-10 flex justify-center text-[#F3D9DC] [&_a]:text-[#F3D9DC] [&_span]:text-[#F3D9DC]">
                {{ $facilities->links() }}
            </div>
        @endif

        @if($facilities->isNotEmpty() && $facilities->onLastPage())
            <p class="text-center text-[#F3D9DC] mt-10">Nothing else to see here ~</p>
        @endif

    </div>
</div>
@endsection