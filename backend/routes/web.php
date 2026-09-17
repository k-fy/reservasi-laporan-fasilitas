<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacilityController;

Route::get('/', [DashboardController::class, 'index'])->name('home');

// Home / Dashboard publik — pengunjung & pengguna sama-sama bisa lihat
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/roles', [AdminController::class, 'roles'])->name('roles');
    Route::get('/facilities', [AdminController::class, 'facilities'])->name('facilities');
    Route::get('/summary', [AdminController::class, 'summary'])->name('summary');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard');

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

Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

require __DIR__.'/auth.php';