<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/facilities', [FacilityController::class, 'index']);