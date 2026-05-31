<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

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
});

Route::middleware('auth')->group(function () {

    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('/tickets/create', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])
        ->name('tickets.edit');

    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])
        ->name('tickets.update');

    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');

        //detail ticket
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    Route::post('/tickets', [TicketController::class, 'store'])
        ->name('tickets.store');
});


//role
Route::middleware(['auth', 'role:admin,technician'])->group(function () {
    // route khusus admin/technician
     Route::get('/staff/tickets', [TicketController::class, 'staffIndex'])
        ->name('staff.tickets.index');

    Route::patch('/staff/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('staff.tickets.updateStatus');

    Route::post('/tickets/{ticket}/comments', [TicketController::class, 'storeComment'])
        ->name('tickets.comments.store');
});


require __DIR__.'/auth.php';
