@extends('layouts.app')

@section('title', 'Booking')

@section('content')
<div class="bg-[#4A4A4A] min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-6 py-10">

        <!-- Title -->
        <h1 class="text-center text-5xl font-serif italic text-[#F3D9DC] mb-3">Booking</h1>
        <div class="border-b-2 border-[#F3D9DC] w-full mx-auto mb-10"></div>

        <!-- Search & Filter -->
        <div class="flex flex-col md:flex-row gap-4 mb-10">

            <!-- Input Search -->
            <div class="flex-1 relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                <input type="text"
                    id="search-input"
                    value="{{ request('q') }}"
                    placeholder="Search facilities..."
                    class="w-full rounded-full pl-11 pr-5 py-3 bg-[#FCF1F0] text-neutral-700 placeholder-neutral-400 focus:outline-none border-0 focus:ring-2 focus:ring-[#EDD3D6]">
            </div>

            <!-- Dropdown Category -->
            <div class="w-full md:w-56">
                <select id="type-filter"
                        class="w-full rounded-full px-5 py-3 bg-[#FCF1F0] text-neutral-700 focus:outline-none border-0 focus:ring-2 focus:ring-[#EDD3D6] cursor-pointer font-medium">
                    <option value="">All Categories</option>
                    <option value="ruangan" {{ request('type') == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
                    <option value="lab"     {{ request('type') == 'lab'     ? 'selected' : '' }}>Lab</option>
                    <option value="gedung"  {{ request('type') == 'gedung'  ? 'selected' : '' }}>Gedung</option>
                    <option value="alat"    {{ request('type') == 'alat'    ? 'selected' : '' }}>Alat</option>
                    <option value="area terbuka" {{ request('type') == 'area terbuka' ? 'selected' : '' }}>Area Terbuka</option>
                </select>
            </div>

            <!-- Reset -->
            <button type="button" id="reset-btn"
                    class="border border-white bg-neutral-600 hover:bg-neutral-700 text-white rounded-full px-6 py-3 font-semibold transition text-sm {{ request('q') || request('type') ? '' : 'hidden' }}">
                Reset
            </button>
        </div>

        <!-- Loading indicator -->
        <div id="loading" class="hidden text-center py-4">
            <svg class="animate-spin mx-auto w-8 h-8 text-[#F3D9DC]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
        </div>

        <!-- Grid Fasilitas -->
        <div id="facility-grid" class="grid grid-cols-1 md:grid-cols-3 gap-6 transition-opacity">
            @include('booking.partials.facility-cards', ['facilities' => $facilities])
        </div>

        <!-- Pagination -->
        <div id="pagination" class=" mt-10 flex justify-center text-[#F3D9DC] [&_a]:text-[#F3D9DC] [&_span]:text-[#F3D9DC] [&_p]:!text-white">
            @if($facilities->hasPages())
                {{ $facilities->links() }}
            @endif
        </div>

        <div id="nothing-else" class="{{ $facilities->isNotEmpty() && $facilities->onLastPage() ? '' : 'hidden' }} text-center text-[#F3D9DC] mt-10">
            Nothing else to see here ~
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const baseUrl     = @json(route('booking.index'));
    const searchInput = document.getElementById('search-input');
    const typeFilter  = document.getElementById('type-filter');
    const resetBtn    = document.getElementById('reset-btn');
    const grid        = document.getElementById('facility-grid');
    const pagination  = document.getElementById('pagination');
    const loading     = document.getElementById('loading');
    const nothingElse = document.getElementById('nothing-else');

    let debounceTimer;
    let currentRequest = null;

    function animateCards() {
        grid.querySelectorAll('.facility-card').forEach((card, i) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(16px)';
            setTimeout(() => {
                card.style.transition = 'opacity 0.8s ease, transform 1s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, i * 60);
        });
    }

    function fetchFacilities(page = 1) {
        const q    = searchInput.value.trim();
        const type = typeFilter.value;

        // Tombol reset hanya tampil kalau ada filter
        resetBtn.classList.toggle('hidden', !q && !type);

        // Susun query string
        const params = new URLSearchParams();
        if (q)        params.set('q', q);
        if (type)     params.set('type', type);
        if (page > 1) params.set('page', page);
        const query = params.toString() ? '?' + params.toString() : '';

        // Update URL di address bar tanpa reload
        window.history.replaceState({}, '', baseUrl + query);

        // Batalkan request sebelumnya yang masih jalan
        if (currentRequest) currentRequest.abort();
        const controller = new AbortController();
        currentRequest = controller;

        loading.classList.remove('hidden');
        grid.classList.add('opacity-30', 'pointer-events-none');

        fetch(baseUrl + query, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            signal: controller.signal,
        })
        .then(response => {
            if (!response.ok) throw new Error('HTTP ' + response.status);
            return response.json();
        })
        .then(data => {
            grid.innerHTML       = data.html;
            pagination.innerHTML = data.pagination;
            nothingElse.classList.toggle('hidden', !data.nothing_else);
            animateCards();
        })
        .catch(err => {
            if (err.name === 'AbortError') return;
            console.error('Gagal memuat fasilitas:', err);
            grid.innerHTML = '<p class="col-span-1 md:col-span-3 text-center text-[#F3D9DC] py-10">Terjadi kesalahan. Coba lagi.</p>';
        })
        .finally(() => {
            if (currentRequest !== controller) return;
            currentRequest = null;
            loading.classList.add('hidden');
            grid.classList.remove('opacity-30', 'pointer-events-none');
        });
    }

    // Search dengan debounce
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => fetchFacilities(), 400);
    });

    // Langsung fetch saat kategori diganti
    typeFilter.addEventListener('change', () => fetchFacilities());

    // Reset
    resetBtn.addEventListener('click', function () {
        searchInput.value = '';
        typeFilter.value  = '';
        fetchFacilities();
    });

    // Pagination: satu listener untuk semua link, tetap jalan setelah isi diganti
    pagination.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (!link) return;
        e.preventDefault();
        const page = new URL(link.href).searchParams.get('page') || 1;
        fetchFacilities(page);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    animateCards()
});
</script>
@endsection