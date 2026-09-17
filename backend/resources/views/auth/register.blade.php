<x-guest-layout>
    {{-- Wordmark kecil di atas kartu (boleh dihapus kalau tak mau) --}}
    <p class="text-chloe-700 font-serif italic text-2xl mb-6">Chloe</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm text-chloe-700 mb-1">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}"
                   required autofocus autocomplete="name"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        {{-- Institution Email --}}
        <div>
            <label for="email" class="block text-sm text-chloe-700 mb-1">Institution Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   required autocomplete="username"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        {{-- Phone Number --}}
        <div>
            <label for="phone" class="block text-sm text-chloe-700 mb-1">Phone Number</label>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                   required autocomplete="tel"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('phone')" class="mt-1" />
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm text-chloe-700 mb-1">Password</label>
            <input id="password" name="password" type="password"
                   required autocomplete="new-password"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm text-chloe-700 mb-1">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   required autocomplete="new-password"
                   class="w-full rounded-full border-chloe-300 bg-white/70 px-5 py-2.5 text-sm
                          focus:border-chloe-500 focus:ring-chloe-400">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

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
</x-guest-layout>