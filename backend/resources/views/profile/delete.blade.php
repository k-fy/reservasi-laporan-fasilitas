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

                @include('profile.partials.delete-user-form')

            </div>
        </div>

    </div>
</div>

@endsection