<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // User Tickets
    Route::get('/tickets', [TicketController::class, 'index'])
        ->name('tickets.index');

    Route::get('/tickets/create', [TicketController::class, 'create'])
        ->name('tickets.create');

    Route::post('/tickets', [TicketController::class, 'store'])
        ->name('tickets.store');

    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])
        ->name('tickets.show');

    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])
        ->name('tickets.edit');

    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])
        ->name('tickets.update');

    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy'])
        ->name('tickets.destroy');

    // Comments
    Route::post('/tickets/{ticket}/comments', [TicketController::class, 'storeComment'])
        ->name('tickets.comments.store');
});

// Staff Routes
Route::middleware(['auth', 'role:admin,technician'])->group(function () {

    Route::get('/staff/tickets', [TicketController::class, 'staffIndex'])
        ->name('staff.tickets.index');

    Route::get('/assigned-tickets', [TicketController::class, 'assignedTickets'])
        ->name('assigned.tickets');

    Route::get('/staff/email-tickets', [TicketController::class, 'emailTickets'])
        ->name('staff.email-tickets.index');

    Route::patch('/staff/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])
        ->name('staff.tickets.updateStatus');

    Route::patch('/staff/tickets/{ticket}/assign', [TicketController::class, 'assignTechnician'])
        ->name('staff.tickets.assign');

    Route::post('/staff/tickets/{ticket}/internal-notes', [TicketController::class, 'storeInternalNote'])
        ->name('staff.tickets.internal-notes.store');

    Route::post('/staff/tickets/{ticket}/email-reply', [TicketController::class, 'sendEmailReply'])
        ->name('staff.tickets.email-reply.store');
});

require __DIR__.'/auth.php';
