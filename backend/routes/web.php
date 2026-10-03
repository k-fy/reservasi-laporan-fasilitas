<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\RecapController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('home');

// Home / Dashboard publik — pengunjung & pengguna sama-sama bisa lihat
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Cek ketersediaan fasilitas dari kotak "Availability Check" di beranda (publik, dibatasi 60x/menit)
Route::get('/availability', [DashboardController::class, 'availability'])
    ->middleware('throttle:60,1')
    ->name('availability.check');

// Dashboard khusus role — wajib login + role sesuai
Route::middleware('auth')->group(function () {
    Route::get('/dashboard/petugas', [DashboardController::class, 'petugas'])
        ->middleware('role:petugas')->name('dashboard.petugas');

    // Admin tidak punya dashboard sendiri: alamat lama ini selalu dilempar ke Accounts.
    // Nama 'dashboard.admin' dipertahankan supaya kode lama yang masih memanggilnya tidak error.
    Route::get('/dashboard/admin', fn () => redirect()->route('admin.accounts'))
        ->middleware('role:admin')->name('dashboard.admin');
});

// Halaman kelola milik admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // /admin dan /admin/dashboard diarahkan ke halaman Accounts
    Route::get('/', fn () => redirect()->route('admin.accounts'));
    Route::get('/dashboard', fn () => redirect()->route('admin.accounts'))->name('dashboard');

    // Accounts
    Route::get('/accounts', [AdminController::class, 'accounts'])->name('accounts');
    Route::post('/accounts', [AdminController::class, 'storeUser'])->name('users.store');
    Route::patch('/users/{user}/role', [AdminController::class, 'updateRole'])->name('users.update-role');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('/users/{user}/verify', [AdminController::class, 'verifyUser'])->name('users.verify');
    Route::patch('/users/{user}/reject', [AdminController::class, 'rejectUser'])->name('users.reject');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/accounts', [AdminController::class, 'accounts'])->name('accounts');

    // Facilities
    Route::get('/facilities', [AdminController::class, 'facilities'])->name('facilities');
    Route::post('/facilities', [AdminController::class, 'storeFacility'])->name('facilities.store');
    Route::put('/facilities/{facility}', [AdminController::class, 'updateFacility'])->name('facilities.update');
    Route::patch('/facilities/{facility}/toggle-status', [AdminController::class, 'toggleFacilityStatus'])->name('facilities.toggle-status');

    // Recap
    Route::get('/recap', [RecapController::class, 'index'])->name('recap');
    Route::get('/recap/export', [RecapController::class, 'exportRecap'])->name('recap.export');
});

// Profil akun (semua role yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/personalization', [ProfileController::class, 'personalization'])->name('profile.personalization');
    Route::get('/profile/text-settings', [ProfileController::class, 'textSettings'])->name('profile.text-settings');
    Route::get('/profile/tts', [ProfileController::class, 'tts'])->name('profile.tts');
    Route::get('/profile/permissions', [ProfileController::class, 'permissions'])->name('profile.permissions');
    Route::get('/profile/biometrics', [ProfileController::class, 'biometrics'])->name('profile.biometrics');
    Route::get('/profile/delete', [ProfileController::class, 'deleteAccount'])->name('profile.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Booking fasilitas
Route::prefix('booking')->name('booking.')->group(function () {
    // Publik — pengunjung bisa lihat daftar fasilitas tanpa login
    Route::get('/', [BookingController::class, 'index'])->name('index');

    // Wajib login (didefinisikan sebelum /{facility} supaya tidak tertangkap sebagai ID fasilitas)
    Route::middleware('auth')->group(function () {
        Route::get('/success', [BookingController::class, 'success'])->name('reserve.success');
        Route::get('/history', [BookingController::class, 'history'])->name('history');
    });

    // Publik — detail & ketersediaan slot
    Route::get('/{facility}/booked-slots', [BookingController::class, 'getBookedSlots']);
    Route::get('/{facility}', [BookingController::class, 'show'])->name('show');
    Route::get('/booking/{facility}/slots', [BookingController::class, 'getBookedSlots'])->name('booking.slots');

    // Wajib login — reservasi
    Route::middleware('auth')->group(function () {
        Route::get('/{facility}/reserve', [BookingController::class, 'reserve'])->name('reserve');
        Route::post('/', [BookingController::class, 'store'])->name('store');
        Route::post('/{reservation}/cancel', [BookingController::class, 'cancel'])->name('cancel');
    });
});

// Laporan kerusakan (pengguna)
Route::middleware('auth')->group(function () {
    Route::get('/reports', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/history', [ReportController::class, 'history'])->name('reports.history');
});

// Panel petugas
Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/reservations', [OperatorController::class, 'reservations'])->name('reservations');
    Route::post('/reservations/{reservation}/approve', [OperatorController::class, 'approveReservation'])->name('reservations.approve');
    Route::post('/reservations/{reservation}/reject', [OperatorController::class, 'rejectReservation'])->name('reservations.reject');
    Route::post('/reservations/{reservation}/cancel', [OperatorController::class, 'cancelReservation'])->name('reservations.cancel');

    Route::get('/reports', [OperatorController::class, 'reports'])->name('reports');
    Route::post('/reports/{report}/start', [OperatorController::class, 'startReport'])->name('reports.start');
    Route::post('/reports/{report}/resolve', [OperatorController::class, 'resolveReport'])->name('reports.resolve');
    Route::post('/reports/{report}/reject', [OperatorController::class, 'rejectReport'])->name('reports.reject');

    Route::get('/facility-status', [OperatorController::class, 'facilityStatus'])->name('facility-status');
    Route::post('/facility-status/{facility}/set', [OperatorController::class, 'setFacilityStatus'])->name('facility-status.set');
});

require __DIR__.'/auth.php';