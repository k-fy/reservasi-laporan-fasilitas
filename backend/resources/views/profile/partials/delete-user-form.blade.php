@extends('layouts.app')
@section('title', 'Delete Account - Chloe')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] px-8 py-10">
    <div class="max-w-5xl mx-auto flex gap-10">

        @include('profile.partials.sidebar')

        <div class="flex-1">
            <div class="bg-white rounded-2xl border border-[#EDD3D6] shadow-sm p-8">

                <h2 class="text-xl font-bold text-red-600 font-['Poppins',sans-serif] mb-1">Delete My Data</h2>
                <div class="border-b border-[#EDD3D6] mb-6"></div>

                <p class="text-sm text-[#4b4848] font-['Poppins',sans-serif] mb-6 leading-relaxed">
                    Menghapus akun akan menghilangkan semua data kamu secara permanen termasuk
                    riwayat reservasi. Tindakan ini <strong>tidak bisa dibatalkan</strong>.
                </p>

                @if($errors->any())
                    <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 mb-4 text-sm font-['Poppins',sans-serif]">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Form + Modal Alpine --}}
                <div x-data="{ showModal: false }">

                    <form id="delete-form" method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-[#4b4848] mb-1 font-['Poppins',sans-serif]">
                                Konfirmasi Password
                            </label>
                            <input type="password" name="password" required
                                   class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-red-300 font-['Poppins',sans-serif]"
                                   placeholder="Masukkan password kamu">
                        </div>

                        {{-- Tombol trigger modal --}}
                        <button type="button" @click="showModal = true"
                                class="bg-red-500 hover:bg-red-600 text-white font-semibold py-3 px-8 rounded-full transition font-['Poppins',sans-serif]">
                            Hapus Akun Saya
                        </button>
                    </form>

                    {{-- Modal konfirmasi --}}
                    <div x-show="showModal"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="fixed inset-0 z-50 flex items-center justify-center px-4">

                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-black/40" @click="showModal = false"></div>

                        {{-- Card --}}
                        <div x-show="showModal"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="relative bg-white rounded-2xl shadow-xl p-8 max-w-sm w-full text-center z-10">

                            {{-- Ikon warning --}}
                            <div class="w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/>
                                </svg>
                            </div>

                            <h3 class="text-lg font-bold text-[#4b4848] mb-2 font-['Poppins',sans-serif]">
                                Are you sure?
                            </h3>
                            <p class="text-sm text-[#9b7d84] font-['Poppins',sans-serif] mb-6 leading-relaxed">
                                Akun dan semua data kamu akan dihapus secara permanen. 
                                Tindakan ini <strong class="text-red-500">tidak bisa dibatalkan</strong>.
                            </p>

                            <div class="flex gap-3">
                                <button type="button" @click="showModal = false"
                                        class="flex-1 border border-[#c9a0a8] text-[#814C5B] font-semibold py-2.5 rounded-full hover:bg-[#FCF1F0] transition font-['Poppins',sans-serif]">
                                    Batal
                                </button>
                                <button type="button" @click="document.getElementById('delete-form').submit()"
                                        class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2.5 rounded-full transition font-['Poppins',sans-serif]">
                                    Ya, Hapus
                                </button>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
</div>

@endsection