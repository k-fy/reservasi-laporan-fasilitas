<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
Route::get('/booking/{facility}', [BookingController::class, 'show'])->name('booking.show');

Route::middleware('auth')->group(function () {
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking-history', [BookingController::class, 'history'])->name('booking.history');
    Route::delete('/booking/{reservation}', [BookingController::class, 'cancel'])->name('booking.cancel');
});
