@extends('layouts.app')
@section('title', 'Personalization - Chloe')
@section('content')

@php
    $prefs = $prefs ?? \App\Http\Controllers\ProfileController::preferences();

    $textSizes = [
        'small'  => ['Kecil',  'text-sm'],
        'normal' => ['Normal', 'text-base'],
        'large'  => ['Besar',  'text-lg'],
    ];
@endphp

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-5xl mx-auto flex gap-10">

        @include('profile.partials.sidebar')

        <div class="flex-1">
            <div class="bg-white rounded-2xl border border-[#EDD3D6] shadow-sm p-8 font-['Poppins',sans-serif]">

                <h2 class="text-xl font-bold text-[#4b4848] mb-1">Personalization</h2>
                <p class="text-sm text-[#9b7d84] mb-4">Atur tampilan aplikasi sesuai preferensimu. Pengaturan disimpan di browser ini.</p>
                <div class="border-b border-[#EDD3D6] mb-8"></div>

                @if (session('success'))
                    <div class="mb-6 flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.personalization.update') }}" class="space-y-8">
                    @csrf

                    {{-- ===== Ukuran tulisan ===== --}}
                    <div>
                        <h3 class="text-sm font-semibold text-[#4b4848]">Ukuran Tampilan</h3>
                        <p class="text-xs text-[#9b7d84] mb-3">Memperbesar atau memperkecil tulisan dan tampilan di seluruh halaman.</p>

                        <div class="grid grid-cols-3 gap-3 max-w-md">
                            @foreach ($textSizes as $value => [$label, $sample])
                                <label class="cursor-pointer">
                                    <input type="radio" name="text_size" value="{{ $value }}" class="peer sr-only"
                                           {{ $prefs['chloe_text_size'] === $value ? 'checked' : '' }}>
                                    <div class="rounded-xl border-2 border-[#EDD3D6] px-3 py-4 text-center transition
                                                peer-checked:border-[#814C5B] peer-checked:bg-[#FCF1F0] hover:border-[#c9a0a8]">
                                        <span class="block {{ $sample }} font-semibold text-[#4b4848]">Aa</span>
                                        <span class="block text-xs text-[#9b7d84] mt-1">{{ $label }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== Animasi ===== --}}
                    <div>
                        <h3 class="text-sm font-semibold text-[#4b4848]">Animasi</h3>
                        <p class="text-xs text-[#9b7d84] mb-3">Matikan jika kamu lebih nyaman tanpa efek gerak dan transisi.</p>

                        <div class="flex gap-3">
                            @foreach (['on' => 'Aktif', 'off' => 'Nonaktif'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="animations" value="{{ $value }}" class="peer sr-only"
                                           {{ $prefs['chloe_animations'] === $value ? 'checked' : '' }}>
                                    <span class="inline-block rounded-full border-2 border-[#EDD3D6] px-5 py-2 text-sm text-[#4b4848] transition
                                                 peer-checked:border-[#814C5B] peer-checked:bg-[#814C5B] peer-checked:text-white hover:border-[#c9a0a8]">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- ===== Ingat pencarian terakhir ===== --}}
                    <div>
                        <h3 class="text-sm font-semibold text-[#4b4848]">Ingat Pencarian Terakhir</h3>
                        <p class="text-xs text-[#9b7d84] mb-3">Kotak Availability Check di beranda otomatis terisi dengan fasilitas, tanggal, dan jam yang terakhir kamu cek.</p>

                        <div class="flex gap-3">
                            @foreach (['on' => 'Ya', 'off' => 'Tidak'] as $value => $label)
                                <label class="cursor-pointer">
                                    <input type="radio" name="remember_search" value="{{ $value }}" class="peer sr-only"
                                           {{ $prefs['chloe_remember_search'] === $value ? 'checked' : '' }}>
                                    <span class="inline-block rounded-full border-2 border-[#EDD3D6] px-5 py-2 text-sm text-[#4b4848] transition
                                                 peer-checked:border-[#814C5B] peer-checked:bg-[#814C5B] peer-checked:text-white hover:border-[#c9a0a8]">
                                        {{ $label }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 pt-4 border-t border-[#EDD3D6]">
                        <button type="submit"
                                class="bg-[#814C5B] hover:bg-[#6b3e4b] text-white text-sm font-semibold px-7 py-2.5 rounded-full transition">
                            Simpan
                        </button>
                    </div>
                </form>

                {{-- Kembalikan ke default (hapus cookie preferensi) --}}
                <form method="POST" action="{{ route('profile.personalization.reset') }}" class="mt-3"
                      onsubmit="return confirm('Kembalikan semua pengaturan tampilan ke awal?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-[#814C5B] underline hover:no-underline">
                        Kembalikan ke pengaturan awal
                    </button>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection