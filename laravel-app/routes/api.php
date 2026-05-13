<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Chat uses session auth (Inertia SPA with cookie-based sessions)
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');
});
