<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('booking')->name('booking.')->group(function () {
    Route::get('/', [BookingController::class, 'index'])->name('index');
    Route::get('/history', [BookingController::class, 'history'])->name('history');
    Route::get('/{facility}', [BookingController::class, 'show'])->name('show');
    
    Route::middleware('auth')->group(function () {
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::post('/{reservation}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });
});

require __DIR__.'/auth.php';

