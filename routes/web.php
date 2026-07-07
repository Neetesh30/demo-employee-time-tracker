<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\TimeLogController;

use Illuminate\Support\Facades\Route;

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

    Route::resource('timelogs', TimeLogController::class);

    Route::resource('leave', LeaveController::class);
});

require __DIR__.'/auth.php';
