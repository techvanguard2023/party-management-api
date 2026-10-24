<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartyConfigController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->name('v1.')->group(function () {
    Route::get('/party', [PartyConfigController::class, 'show'])->name('party.show');
    Route::post('/rsvps', [RsvpController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('rsvps.store');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::put('/party', [PartyConfigController::class, 'update'])->name('party.update');
        Route::get('/rsvps', [RsvpController::class, 'index'])->name('rsvps.index');
        Route::delete('/rsvps/{rsvp}', [RsvpController::class, 'destroy'])->name('rsvps.destroy');
    });
});
