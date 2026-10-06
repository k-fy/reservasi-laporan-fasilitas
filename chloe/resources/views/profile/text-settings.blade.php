@extends('layouts.app')
@section('title', 'Text Settings - Chloe')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-5xl mx-auto flex gap-10">

        @include('profile.partials.sidebar')

        <div class="flex-1">
            <div class="bg-white rounded-2xl border border-[#EDD3D6] shadow-sm p-8">

                <h2 class="text-xl font-bold text-[#4b4848] font-['Poppins',sans-serif] mb-1">Text Settings</h2>
                <div class="border-b border-[#EDD3D6] mb-8"></div>

                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 rounded-full bg-[#FCF1F0] border-2 border-[#EDD3D6] flex items-center justify-center mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-[#c9a0a8]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-[#4b4848] font-['Poppins',sans-serif] mb-2">Text Settings</h3>
                    <p class="text-sm text-[#9b7d84] font-['Poppins',sans-serif] max-w-xs leading-relaxed">
                        Atur ukuran font, jenis huruf, dan keterbacaan teks di seluruh aplikasi.
                    </p>
                    <span class="mt-6 inline-block bg-[#FCF1F0] border border-[#EDD3D6] text-[#c9a0a8] text-xs font-semibold px-4 py-2 rounded-full font-['Poppins',sans-serif]">
                        Coming Soon
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection