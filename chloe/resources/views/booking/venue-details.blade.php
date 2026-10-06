@extends('layouts.app')

@section('title', ($venue->name ?? $facility->name) . ' - Detail Venue')

@section('content')
<div class="bg-[#4A4A4A] min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-6">
        
        @include('booking.partials.gallery', ['venue' => $venue ?? $facility])
        @include('booking.partials.info', ['facility' => $facility ?? $venue, 'venue' => $venue ?? $facility, 'amenitiesList' => $amenitiesList ?? []])
        @include('booking.partials.scheduler', ['venue' => $venue ?? $facility, 'bookedSlots' => $booked ?? []])

    </div>
</div>
@endsection