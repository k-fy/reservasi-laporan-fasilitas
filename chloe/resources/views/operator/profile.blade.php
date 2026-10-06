@extends('layouts.operator')

@section('title', 'Edit Profile - Chloe')

@section('content')
<h1>My Account</h1>
<p class="sub">Update your profile photo, personal data, and password.</p>

{{-- Form Edit Account (dipakai bersama dengan pengguna & admin) --}}
<div style="display:flex;justify-content:center">
    @include('profile.partials.account-form', [
        'user'      => $user,
        'cancelUrl' => route('dashboard.petugas'),
    ])
</div>
@endsection