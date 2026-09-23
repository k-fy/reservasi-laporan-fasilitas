@extends('layouts.app')
@section('title', 'Edit Profile - Chloe')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl p-10">

        <h1 class="text-center font-['Georgia',serif] italic text-3xl text-[#4b4848] mb-2">Edit Profile</h1>
        <div class="border-b border-[#c9a0a8] mb-8"></div>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
              class="space-y-5 font-['Poppins',sans-serif]">
            @csrf
            @method('PATCH')

            <div class="flex flex-col items-center gap-3 mb-4">
                <div class="w-20 h-20 rounded-full bg-[#814C5B] overflow-hidden">
                    @if($user->photo)
                        <img src="{{ asset('storage/'.$user->photo) }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <label class="cursor-pointer text-sm text-[#814C5B] underline">
                    Ganti Foto
                    <input type="file" name="photo" accept="image/*" class="hidden">
                </label>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">NIM/NIP</label>
                <input type="text" name="nim_nip" value="{{ old('nim_nip', $user->nim_nip) }}"
                       class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] resize-none focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Password Baru <span class="text-gray-400 font-normal">(kosongkan jika tidak diganti)</span></label>
                <input type="password" name="password"
                       class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#4b4848] mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation"
                       class="w-full rounded-xl border border-[#c9a0a8] px-4 py-2.5 text-[#4b4848] focus:outline-none focus:ring-2 focus:ring-[#c9a0a8]">
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('profile.show') }}"
                   class="flex-1 text-center border border-[#c9a0a8] text-[#814C5B] font-semibold py-3 rounded-full hover:bg-[#FCF1F0] transition">
                    Batal
                </a>
                <button type="submit"
                        class="flex-1 bg-[#814C5B] hover:bg-[#6b3e4b] text-white font-semibold py-3 rounded-full transition">
                    Simpan
                </button>
            </div>
        </form>

    </div>
</div>

@endsection