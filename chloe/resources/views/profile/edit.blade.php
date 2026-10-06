@extends('layouts.app')
@section('title', 'Edit Profile - Chloe')
@section('content')

<div class="min-h-screen bg-[#FCF1F0] flex items-center justify-center py-12 px-4">
    @include('profile.partials.account-form', [
        'user'      => $user,
        'cancelUrl' => route('profile.show'),
    ])
</div>

@endsection