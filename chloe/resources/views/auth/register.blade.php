@php use App\Support\FormRules; @endphp

<x-guest-layout>
    {{-- Wordmark kecil di atas kartu --}}
    <p class="text-chloe-700 font-serif italic text-2xl mb-6">Chloe</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Full name --}}
        <div>
            <label for="name" class="block text-sm text-chloe-700 mb-1">Full Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   required autofocus autocomplete="name" maxlength="100"
                   pattern="{{ FormRules::NAME_PATTERN }}"
                   title="Hanya huruf, spasi, titik, atau tanda hubung"
                   placeholder="e.g. Bagus Tri Prakoso"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- NIM / NIP --}}
        <div>
            <label for="nim_nip" class="block text-sm text-chloe-700 mb-1">Student ID / Employee ID (NIM/NIP)</label>
            <input id="nim_nip" name="nim_nip" type="text" value="{{ old('nim_nip') }}"
                   required inputmode="numeric" maxlength="18"
                   pattern="{{ FormRules::NIM_NIP_PATTERN }}"
                   title="NIM 14 digit atau NIP 18 digit, hanya angka"
                   placeholder="14 digit (NIM) atau 18 digit (NIP)"
                   oninput="this.value = this.value.replace(/\D/g, '')"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('nim_nip')" class="mt-1" />
        </div>

        {{-- Campus Email --}}
        <div>
            <label for="email" class="block text-sm text-chloe-700 mb-1">Campus Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autocomplete="username" maxlength="255"
                   pattern="[^@\s]+@charm\.ac\.id"
                   title="Gunakan email institusi berakhiran {{ FormRules::EMAIL_DOMAIN }}"
                   placeholder="name{{ FormRules::EMAIL_DOMAIN }}"
                   oninput="this.value = this.value.toLowerCase()"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm text-chloe-700 mb-1">Password</label>
            <div class="relative flex items-center">
                <input id="password" name="password" type="password"
                       required autocomplete="new-password" minlength="8"
                       placeholder="Min. 8 characters"
                       class="w-full rounded-full border-chloe-300 bg-white/70 pl-5 pr-12 py-2.5 text-sm
                              focus:border-chloe-500 focus:ring-chloe-400">

                <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-pass')"
                        aria-label="Show or hide password"
                        class="absolute right-4 text-chloe-600 hover:text-chloe-800 focus:outline-none cursor-pointer">
                    <svg id="eye-icon-pass" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm text-chloe-700 mb-1">Confirm Password</label>
            <div class="relative flex items-center">
                <input id="password_confirmation" name="password_confirmation" type="password"
                       required autocomplete="new-password" minlength="8"
                       class="w-full rounded-full border-chloe-300 bg-white/70 pl-5 pr-12 py-2.5 text-sm
                              focus:border-chloe-500 focus:ring-chloe-400">

                <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eye-icon-confirm')"
                        aria-label="Show or hide password"
                        class="absolute right-4 text-chloe-600 hover:text-chloe-800 focus:outline-none cursor-pointer">
                    <svg id="eye-icon-confirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 20px; height: 20px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <p class="text-xs text-chloe-600">Akun baru akan diverifikasi oleh Admin sebelum dapat digunakan.</p>

        {{-- Baris bawah: link + tombol --}}
        <div class="flex items-center justify-between pt-2">
            <a href="{{ route('login') }}"
               class="text-xs text-chloe-600 underline hover:text-chloe-700">
                Already own an Account?
            </a>

            <button type="submit"
                    class="rounded-full bg-chloe-500 px-8 py-2.5 text-sm font-semibold text-white
                           hover:bg-chloe-600 transition">
                Sign Up
            </button>
        </div>
    </form>

    {{-- Kembali ke halaman utama --}}
    <a href="{{ url('/') }}"
       class="mt-6 flex items-center gap-1 text-xs text-chloe-700 hover:text-chloe-600">
        &larr; Back to Dashboard
    </a>

    {{-- Script untuk menampilkan / menyembunyikan password --}}
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            const openEyePath = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
            const closedEyePath = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />';

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = closedEyePath;
            } else {
                input.type = 'password';
                icon.innerHTML = openEyePath;
            }
        }
    </script>
</x-guest-layout>