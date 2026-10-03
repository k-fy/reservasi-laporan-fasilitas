@php
    // Preferensi tampilan dari cookie (diatur di halaman Personalization)
    $prefs = \App\Http\Controllers\ProfileController::preferences();
    $zoom  = ['small' => 0.9, 'normal' => 1, 'large' => 1.12][$prefs['chloe_text_size']];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      class="{{ $prefs['chloe_animations'] === 'off' ? 'reduce-motion' : '' }}"
      data-remember-search="{{ $prefs['chloe_remember_search'] }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CHLOE') }}</title>

        <!-- Favicon (file: public/images/favicon.png). "?v=2" memaksa browser memuat ulang ikon terbaru -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=2">
        <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=2">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Sembunyikan scrollbar (halaman tetap bisa di-scroll) -->
        <style>
            html { scrollbar-width: none; }      /* Firefox */
            ::-webkit-scrollbar { display: none; } /* Chrome, Edge, Safari */

            /* Preferensi ukuran tulisan (cookie chloe_text_size) */
            body { zoom: {{ $zoom }}; }

            /* Preferensi animasi dimatikan (cookie chloe_animations = off) */
            .reduce-motion *, .reduce-motion *::before, .reduce-motion *::after {
                animation: none !important;
                transition: none !important;
            }
            .reduce-motion .reveal { opacity: 1 !important; transform: none !important; }
        </style>
    </head>

    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content', $slot ?? '')
            </main>

            <!-- Page Footer -->
            <x-footer />
        </div>

        <!-- Konfirmasi Log Out -->
        <x-logout-confirm />
    </body>
</html>