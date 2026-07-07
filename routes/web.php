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


    /*
    |--------------------------------------------------------------------------
    | Time Logs
    |--------------------------------------------------------------------------
    */

    Route::get('/time-logs', [TimeLogController::class, 'index'])
        ->name('time-logs.index');

    Route::post('/time-logs', [TimeLogController::class, 'store'])
        ->name('time-logs.store');

    Route::delete('/time-logs/{timeLog}', [TimeLogController::class, 'destroy'])
        ->name('time-logs.destroy');


    /*
    |--------------------------------------------------------------------------
    | Leave
    |--------------------------------------------------------------------------
    */

    Route::get('/leaves', [LeaveController::class, 'index'])
        ->name('leaves.index');

    Route::post('/leaves', [LeaveController::class, 'store'])
        ->name('leaves.store');

    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy'])
        ->name('leaves.destroy');




});

require __DIR__.'/auth.php';
