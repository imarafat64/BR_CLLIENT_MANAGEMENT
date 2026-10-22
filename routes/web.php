<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

Route::get('/', [ClientController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/home', [ClientController::class, 'index'])->name('home')->middleware('auth');

Route::middleware('auth')->prefix('clients')->group(function () {
    Route::get('/list', [ClientController::class, 'list'])->name('clients.list');
    Route::post('/', [ClientController::class, 'store'])->name('clients.store');
    Route::get('/{client}', [ClientController::class, 'show'])->name('clients.show');
    Route::put('/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
});

Auth::routes();
