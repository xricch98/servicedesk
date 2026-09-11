<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\TicketActionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/queue', [QueueController::class, 'index'])->name('queue.index');
    Route::patch('/tickets/{ticket}/assign', [TicketActionController::class, 'assign'])->name('tickets.assign');
    Route::patch('/tickets/{ticket}/status', [TicketActionController::class, 'status'])->name('tickets.status');
    Route::post('/tickets/{ticket}/comments', [TicketActionController::class, 'comment'])->name('tickets.comment');
    Route::patch('/tickets/{ticket}/close', [TicketActionController::class, 'close'])->name('tickets.close');
    Route::patch('/tickets/{ticket}/reopen', [TicketActionController::class, 'reopen'])->name('tickets.reopen');

});

require __DIR__.'/auth.php';
