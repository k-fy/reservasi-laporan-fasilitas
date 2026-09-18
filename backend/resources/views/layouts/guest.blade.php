<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Chloe') }}</title>

    <!-- Fonts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <div class="min-h-screen w-full bg-cover bg-center relative flex items-center justify-center p-6"
         style="background-image: url('{{ asset('images/campus.png') }}');">

        <!-- Lapisan gelap tipis di atas foto biar teks terbaca -->
        <div class="absolute inset-0 bg-chloe-700/40"></div>

        <!-- Isi utama: dua kolom -->
        <div class="relative z-10 w-full max-w-5xl grid md:grid-cols-2 gap-8 items-center">

            <!-- Kolom kiri: kartu form -->
            <div class="bg-chloe-100/90 backdrop-blur-sm rounded-3xl shadow-2xl p-8 sm:p-10">
                {{ $slot }}
            </div>

            <!-- Kolom kanan: teks sambutan -->
            <div class="text-white text-center md:text-left px-2">
                <h1 class="font-serif italic text-4xl sm:text-5xl mb-3">Welcome to Chloe.</h1>
                <p class="font-serif italic text-lg sm:text-xl leading-relaxed opacity-90">
                    CHLOE (Campus Hall &amp;<br>Location Online E-booking)
                </p>
            </div>

        </div>
    </div>
</body>
</html>