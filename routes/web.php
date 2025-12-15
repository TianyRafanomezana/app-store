<?php

use App\Http\Controllers\HabitController;
use App\Http\Controllers\HabitLogController;
use App\Http\Controllers\HabitUserController;
use App\Http\Controllers\YoutubeVideoController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [HabitController::class, 'index'])->name('dashboard');

    // Routes pour gérer les habitudes
    Route::post('habits/store', [HabitController::class, 'store'])->name('habits.store');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])->name('habits.archive');
    
        // Routes pour gérer l'état des habitudes par utilisateur et date
    Route::prefix('habit-user')->group(function () {
        Route::post('/store-or-update', [HabitUserController::class, 'storeOrUpdate']);
        Route::get('/show', [HabitUserController::class, 'show']);
        Route::get('/history', [HabitUserController::class, 'history']);
    });
});




require __DIR__.'/settings.php';
