<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Rotas públicas
Route::get('/', [EventController::class, 'index'])->name('events.index');

// Rotas fixas primeiro (admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/update/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
});

// Rotas públicas dinâmicas
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

// Área logada (não-admin)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');
    Route::post('/events/join/{id}', [EventController::class, 'joinEvent'])->name('events.join');
    Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent'])->name('events.leave');
});

// Página aberta
Route::get('/contact', fn() => view('contact'))->name('contact');
